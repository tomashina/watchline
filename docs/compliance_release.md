# Sidrene cijene, raskid ugovora i zakonsko jamstvo — produkcija

Ove izmjene za OpenCart 2.3.0.2 puštaju se kontrolirano. Git push ne mijenja
produkciju i ne izvršava SQL. Prije početka treba napraviti provjerenu potpunu
kopiju baze i datoteka, uključujući `system/storage/modification/`,
`system/storage/download/` i VQMod stanje.

## Redoslijed

1. U čistom produkcijskom checkoutu povući odobreni commit s `main` granom.
2. Izvršiti idempotentnu migraciju obrasca:

   ```sh
   mysql < sql/2026_09_29_unilateral_termination.sql
   ```

3. Izvršiti migracije sidrenih cijena točno ovim redoslijedom:

   ```sh
   mysql < sql/2026_09_28_anchor_price_01_schema.sql
   mysql < sql/2026_09_28_anchor_price_02_activation.sql
   mysql < sql/2026_09_28_anchor_price_03_backfill.sql
   mysql < sql/2026_09_29_anchor_price_single_location.sql
   mysql < sql/2026_09_28_anchor_price_04_verify_read_only.sql
   ```

   Prve tri skripte mijenjaju bazu. Četvrta je samo za čitanje; svaki popis
   nepravilnosti mora biti prazan prije prve objave cjenika.
4. U administraciji otvoriti **Extensions > Modifications** i odabrati
   **Refresh**. Zatim očistiti theme/OpenCart cache i VQMod cache. Ovaj korak je
   obvezan jer Watchline ima HuntBee i Basel OCMOD izmjene nad istim rutama.
5. Provjeriti javni obrazac `/obrazac-za-povrat`, spremanje zahtjeva, potvrdu
   kupcu i obavijest administratoru. Provjeriti i popravljen link iz stranice
   „Uvjeti kupovine”. SMTP test treba napraviti s produkcijskom konfiguracijom.
6. Provjeriti obavijest o zakonskom jamstvu u footeru, checkoutu, proizvodu i
   početnoj potvrdi narudžbe. Usporediti kontrolne zbrojeve službenih asseta s
   `docs/eu-legal-guarantee-notice.md`.
7. U administraciji otvoriti **Catalog > Sidrene cijene**. Potvrditi da svaki
   aktivni proizvod ima sidrenu cijenu. Aktivni novi artikli potvrđuju se
   automatski s datumom prve objave; neispravan ili prazan barkod ne blokira CSV.
8. Ručno stvoriti prvi jedinstveni Watchline cjenik i provjeriti javnu
   stranicu `index.php?route=information/price_list`, broj redaka i SHA-256.

## Pravilo početnog punjenja

Za artikle koji su postojali do 10. 9. 2026. backfill sprema tadašnju cijenu s
referentnim datumom 10. 9. 2026. Aktivni artikli dobivaju status `confirmed`, a
neaktivni `pending`. Artikli prvi put objavljeni poslije tog datuma automatski
koriste stvarni datum prve objave.

## Zakazani posao

Nakon uspješne ručne objave postaviti radni-dan poziv u 00:30 po zoni
`Europe/Zagreb`, koristeći tajno HTTP zaglavlje, nikad query parametar:

```sh
curl --fail --silent --show-error \
  --header 'X-Anchor-Price-Key: <KLJUC_IZ_ADMINISTRACIJE>' \
  'https://<PRODUKCIJSKA_DOMENA>/index.php?route=extension/module/anchor_price/cron'
```

Ključ spremiti u privatnu Curl konfiguraciju izvan web-korijena s ovlastima
`600`; ne zapisivati ga u Git ni log. Ako scheduler ne podržava zagrebačku
vremensku zonu, raspored mora eksplicitno pratiti promjene ljetnog/zimskog sata.
Ponovni poziv istog dana koristi postojeći valjani cjenik, a arhiva se čuva 30 dana.

## Završna kontrola

- Provjeriti proizvod s akcijom i bez akcije, kategoriju, pretragu, quickview,
  live-search, wishlist, usporedbu, mini-košaricu, košaricu i oba checkouta.
- Provjeriti da prikaz sidrene cijene ne mijenja iznos narudžbe.
- Provjeriti admin uređivanje uz obvezan razlog i revizijski trag.
- Potvrditi točno jedan dnevni Watchline cjenik.
- Ponovno pokrenuti read-only SQL provjeru i sačuvati rezultat uz zapis deploya.

Ako bilo koja blokirajuća provjera ne uspije, ne uključivati cron i ne objaviti
nepotpun cjenik; vratiti datoteke i bazu iz prethodno provjerene kopije.
