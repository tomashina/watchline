-- Sidrene cijene - 02/04: aktivacija modula, postavke i eventi
--
-- Pokrenuti nakon 2026_09_28_anchor_price_01_schema.sql.
-- Skripta je idempotentna i namjerno ne koristi DELETE, REPLACE niti
-- brisanje cijele setting grupe. Postojeci cron kljuc ostaje nepromijenjen.
-- Status, referentni datum i zadana jedinica uskladuju se s dogovorenim
-- produkcijskim vrijednostima.

START TRANSACTION;

-- Registracija instaliranog OpenCart modula (bez stvaranja duplikata).
INSERT INTO `oc_extension` (`type`, `code`)
SELECT 'module', 'anchor_price'
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1
  FROM `oc_extension`
  WHERE `type` = 'module'
    AND `code` = 'anchor_price'
);

-- Modul mora biti aktivan.
INSERT INTO `oc_setting` (`store_id`, `code`, `key`, `value`, `serialized`)
SELECT 0, 'module_anchor_price', 'module_anchor_price_status', '1', 0
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1
  FROM `oc_setting`
  WHERE `store_id` = 0
    AND `code` = 'module_anchor_price'
    AND `key` = 'module_anchor_price_status'
);

UPDATE `oc_setting`
SET `value` = '1', `serialized` = 0
WHERE `store_id` = 0
  AND `code` = 'module_anchor_price'
  AND `key` = 'module_anchor_price_status';

-- Zakonski/dogovoreni bazni datum. Artikli dodani kasnije dobivaju datum
-- svoje prve objave u backfillu ili kroz admin event pri aktivaciji. Ako se
-- event zaobiđe, runtime sinkronizacija automatski snima prvu uočenu objavu.
INSERT INTO `oc_setting` (`store_id`, `code`, `key`, `value`, `serialized`)
SELECT 0, 'module_anchor_price', 'module_anchor_price_reference_date', '2026-09-10', 0
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1
  FROM `oc_setting`
  WHERE `store_id` = 0
    AND `code` = 'module_anchor_price'
    AND `key` = 'module_anchor_price_reference_date'
);

UPDATE `oc_setting`
SET `value` = '2026-09-10', `serialized` = 0
WHERE `store_id` = 0
  AND `code` = 'module_anchor_price'
  AND `key` = 'module_anchor_price_reference_date';

-- Zadana jedinica mjere za dnevne datoteke.
INSERT INTO `oc_setting` (`store_id`, `code`, `key`, `value`, `serialized`)
SELECT 0, 'module_anchor_price', 'module_anchor_price_default_unit', 'kom', 0
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1
  FROM `oc_setting`
  WHERE `store_id` = 0
    AND `code` = 'module_anchor_price'
    AND `key` = 'module_anchor_price_default_unit'
);

UPDATE `oc_setting`
SET `value` = 'kom', `serialized` = 0
WHERE `store_id` = 0
  AND `code` = 'module_anchor_price'
  AND `key` = 'module_anchor_price_default_unit';

-- MySQL 8 RANDOM_BYTES generira 256-bitni kljuc tek pri izvrsavanju.
-- Ako setting vec postoji, njegov se sadrzaj ne cita, ne ispisuje i ne mijenja.
SET @anchor_price_generated_cron_key = LOWER(HEX(RANDOM_BYTES(32)));

INSERT INTO `oc_setting` (`store_id`, `code`, `key`, `value`, `serialized`)
SELECT 0, 'module_anchor_price', 'module_anchor_price_cron_key', @anchor_price_generated_cron_key, 0
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1
  FROM `oc_setting`
  WHERE `store_id` = 0
    AND `code` = 'module_anchor_price'
    AND `key` = 'module_anchor_price_cron_key'
);

-- Ukloni generirani kljuc iz session varijable; pohranjena postavka ostaje.
SET @anchor_price_generated_cron_key = NULL;

-- OpenCart eventi. Svi koriste isti code kako bi standardni uninstall modula
-- mogao ukloniti tocno njegov skup eventa. Postojeci nepovezani eventi se ne diraju.
INSERT INTO `oc_event` (`code`, `trigger`, `action`, `status`, `date_added`)
SELECT 'anchor_price', 'admin/model/catalog/product/addProduct/after', 'extension/module/anchor_price/captureProduct', 1, NOW()
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM `oc_event`
  WHERE `code` = 'anchor_price'
    AND `trigger` = 'admin/model/catalog/product/addProduct/after'
    AND `action` = 'extension/module/anchor_price/captureProduct'
);

INSERT INTO `oc_event` (`code`, `trigger`, `action`, `status`, `date_added`)
SELECT 'anchor_price', 'admin/model/catalog/product/editProduct/after', 'extension/module/anchor_price/captureProduct', 1, NOW()
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM `oc_event`
  WHERE `code` = 'anchor_price'
    AND `trigger` = 'admin/model/catalog/product/editProduct/after'
    AND `action` = 'extension/module/anchor_price/captureProduct'
);

INSERT INTO `oc_event` (`code`, `trigger`, `action`, `status`, `date_added`)
SELECT 'anchor_price', 'admin/model/catalog/product_ext/quickEditProduct/after', 'extension/module/anchor_price/captureQuickEdit', 1, NOW()
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM `oc_event`
  WHERE `code` = 'anchor_price'
    AND `trigger` = 'admin/model/catalog/product_ext/quickEditProduct/after'
    AND `action` = 'extension/module/anchor_price/captureQuickEdit'
);

INSERT INTO `oc_event` (`code`, `trigger`, `action`, `status`, `date_added`)
SELECT 'anchor_price', 'catalog/view/*/before', 'extension/module/anchor_price/beforeView', 1, NOW()
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM `oc_event`
  WHERE `code` = 'anchor_price'
    AND `trigger` = 'catalog/view/*/before'
    AND `action` = 'extension/module/anchor_price/beforeView'
);

-- Ako je tocno dogovoreni event ranije bio deaktiviran, ponovno ga aktiviraj.
-- Ne mijenjaju se eventi koji samo dijele isti code, ali nemaju ovu rutu/akciju.
UPDATE `oc_event`
SET `status` = 1
WHERE `code` = 'anchor_price'
  AND (
    (`trigger` = 'admin/model/catalog/product/addProduct/after'
      AND `action` = 'extension/module/anchor_price/captureProduct')
    OR (`trigger` = 'admin/model/catalog/product/editProduct/after'
      AND `action` = 'extension/module/anchor_price/captureProduct')
    OR (`trigger` = 'admin/model/catalog/product_ext/quickEditProduct/after'
      AND `action` = 'extension/module/anchor_price/captureQuickEdit')
    OR (`trigger` = 'catalog/view/*/before'
      AND `action` = 'extension/module/anchor_price/beforeView')
  );

-- Administratorske dozvole. OpenCart 2.3 cuva permission kao JSON objekt s
-- poljima access i modify. Mijenjaju se samo Administrator grupa i/ili grupa
-- ID 1, i samo ako je postojeci JSON valjan. Nevaljani ili neocekivani JSON
-- ostaje netaknut te ce ga read-only provjera oznaciti za rucnu intervenciju.
UPDATE `oc_user_group`
SET `permission` = JSON_SET(`permission`, '$.access', JSON_ARRAY())
WHERE (`user_group_id` = 1 OR LOWER(TRIM(`name`)) = 'administrator')
  AND JSON_VALID(`permission`)
  AND JSON_TYPE(IF(JSON_VALID(`permission`), `permission`, '{}')) = 'OBJECT'
  AND JSON_EXTRACT(
    IF(JSON_VALID(`permission`), `permission`, '{}'),
    '$.access'
  ) IS NULL;

UPDATE `oc_user_group`
SET `permission` = JSON_SET(`permission`, '$.modify', JSON_ARRAY())
WHERE (`user_group_id` = 1 OR LOWER(TRIM(`name`)) = 'administrator')
  AND JSON_VALID(`permission`)
  AND JSON_TYPE(IF(JSON_VALID(`permission`), `permission`, '{}')) = 'OBJECT'
  AND JSON_EXTRACT(
    IF(JSON_VALID(`permission`), `permission`, '{}'),
    '$.modify'
  ) IS NULL;

UPDATE `oc_user_group`
SET `permission` = JSON_ARRAY_APPEND(
  `permission`,
  '$.access',
  'extension/module/anchor_price'
)
WHERE (`user_group_id` = 1 OR LOWER(TRIM(`name`)) = 'administrator')
  AND JSON_VALID(`permission`)
  AND JSON_TYPE(IF(JSON_VALID(`permission`), `permission`, '{}')) = 'OBJECT'
  AND JSON_TYPE(JSON_EXTRACT(
    IF(JSON_VALID(`permission`), `permission`, '{}'),
    '$.access'
  )) = 'ARRAY'
  AND JSON_CONTAINS(
    JSON_EXTRACT(
      IF(JSON_VALID(`permission`), `permission`, '{}'),
      '$.access'
    ),
    JSON_QUOTE('extension/module/anchor_price')
  ) = 0;

UPDATE `oc_user_group`
SET `permission` = JSON_ARRAY_APPEND(
  `permission`,
  '$.modify',
  'extension/module/anchor_price'
)
WHERE (`user_group_id` = 1 OR LOWER(TRIM(`name`)) = 'administrator')
  AND JSON_VALID(`permission`)
  AND JSON_TYPE(IF(JSON_VALID(`permission`), `permission`, '{}')) = 'OBJECT'
  AND JSON_TYPE(JSON_EXTRACT(
    IF(JSON_VALID(`permission`), `permission`, '{}'),
    '$.modify'
  )) = 'ARRAY'
  AND JSON_CONTAINS(
    JSON_EXTRACT(
      IF(JSON_VALID(`permission`), `permission`, '{}'),
      '$.modify'
    ),
    JSON_QUOTE('extension/module/anchor_price')
  ) = 0;

COMMIT;
