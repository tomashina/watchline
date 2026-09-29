-- Digitalni obrazac za jednostrani raskid ugovora (OpenCart 2.3 / Watchline).
-- Idempotentna produkcijska migracija: ne dira postojece povrate ni razloge povrata.

-- Legacy OpenCart stupac date_ordered koristi 0000-00-00 kao zadanu vrijednost.
-- MySQL 8 inace odbija svako ALTER preslagivanje te tablice. Promjena vrijedi
-- samo za ovu vezu i izvorni se session mode obvezno vraca prije izlaza.
SET @return_original_sql_mode := @@SESSION.sql_mode;
SET SESSION sql_mode = REPLACE(REPLACE(@@SESSION.sql_mode, 'NO_ZERO_IN_DATE', ''), 'NO_ZERO_DATE', '');

SET @return_invoice_number_exists := (
  SELECT COUNT(*) FROM `information_schema`.`columns`
  WHERE `table_schema` = DATABASE() AND `table_name` = 'oc_return' AND `column_name` = 'invoice_number'
);
SET @return_invoice_number_sql := IF(
  @return_invoice_number_exists = 0,
  'ALTER TABLE `oc_return` ADD `invoice_number` VARCHAR(64) NOT NULL DEFAULT '''' AFTER `order_id`',
  'SELECT 1'
);
PREPARE return_invoice_number_statement FROM @return_invoice_number_sql;
EXECUTE return_invoice_number_statement;
DEALLOCATE PREPARE return_invoice_number_statement;

SET @return_invoice_date_exists := (
  SELECT COUNT(*) FROM `information_schema`.`columns`
  WHERE `table_schema` = DATABASE() AND `table_name` = 'oc_return' AND `column_name` = 'invoice_date'
);
SET @return_invoice_date_sql := IF(
  @return_invoice_date_exists = 0,
  'ALTER TABLE `oc_return` ADD `invoice_date` DATE NULL DEFAULT NULL AFTER `invoice_number`',
  'SELECT 1'
);
PREPARE return_invoice_date_statement FROM @return_invoice_date_sql;
EXECUTE return_invoice_date_statement;
DEALLOCATE PREPARE return_invoice_date_statement;

SET @return_refund_iban_exists := (
  SELECT COUNT(*) FROM `information_schema`.`columns`
  WHERE `table_schema` = DATABASE() AND `table_name` = 'oc_return' AND `column_name` = 'refund_iban'
);
SET @return_refund_iban_sql := IF(
  @return_refund_iban_exists = 0,
  'ALTER TABLE `oc_return` ADD `refund_iban` VARCHAR(64) NOT NULL DEFAULT '''' AFTER `telephone`',
  'SELECT 1'
);
PREPARE return_refund_iban_statement FROM @return_refund_iban_sql;
EXECUTE return_refund_iban_statement;
DEALLOCATE PREPARE return_refund_iban_statement;

SET @return_items_exists := (
  SELECT COUNT(*) FROM `information_schema`.`columns`
  WHERE `table_schema` = DATABASE() AND `table_name` = 'oc_return' AND `column_name` = 'return_items'
);
SET @return_items_sql := IF(
  @return_items_exists = 0,
  'ALTER TABLE `oc_return` ADD `return_items` TEXT NULL AFTER `quantity`',
  'SELECT 1'
);
PREPARE return_items_statement FROM @return_items_sql;
EXECUTE return_items_statement;
DEALLOCATE PREPARE return_items_statement;

-- Standardni alias osigurava ulaznu rutu; HuntBee zapis osigurava i generiranje URL-a.
UPDATE `oc_url_alias`
SET `keyword` = 'obrazac-za-povrat', `language_id` = 2
WHERE `query` = 'account/return/add'
  AND NOT EXISTS (
    SELECT 1 FROM (SELECT `query`, `keyword` FROM `oc_url_alias`) aliases
    WHERE aliases.`keyword` = 'obrazac-za-povrat'
      AND aliases.`query` <> 'account/return/add'
  );

INSERT INTO `oc_url_alias` (`query`, `keyword`, `language_id`)
SELECT 'account/return/add', 'obrazac-za-povrat', 2
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `oc_url_alias` WHERE `query` = 'account/return/add')
  AND NOT EXISTS (SELECT 1 FROM `oc_url_alias` WHERE `keyword` = 'obrazac-za-povrat');

UPDATE `oc_hb_url`
SET `keyword` = 'obrazac-za-povrat'
WHERE `route` = 'account/return/add' AND `language_id` = 2 AND `store_id` = 0
  AND NOT EXISTS (
    SELECT 1 FROM (SELECT `route`, `keyword`, `language_id`, `store_id` FROM `oc_hb_url`) hb
    WHERE hb.`keyword` = 'obrazac-za-povrat' AND hb.`language_id` = 2 AND hb.`store_id` = 0
      AND hb.`route` <> 'account/return/add'
  );

INSERT INTO `oc_hb_url` (`route`, `keyword`, `language_id`, `store_id`)
SELECT 'account/return/add', 'obrazac-za-povrat', 2, 0
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM `oc_hb_url`
  WHERE `route` = 'account/return/add' AND `language_id` = 2 AND `store_id` = 0
)
AND NOT EXISTS (
  SELECT 1 FROM `oc_hb_url`
  WHERE `keyword` = 'obrazac-za-povrat' AND `language_id` = 2 AND `store_id` = 0
);

-- Ispravci postojeceg hrvatskog teksta Uvjeta kupovine (information_id=5).
UPDATE `oc_information_description`
SET `description` = REPLACE(
  REPLACE(
    REPLACE(`description`, 'image/Obrazac-za-jednostrani-raskid-ugovora.pdf', 'obrazac-za-povrat'),
    'putem Atelier Bebes web shopa',
    'putem Watchline web shopa'
  ),
  'Obrazac za jednostrani raskid ugovora možete preuzeti',
  'Digitalni obrazac za jednostrani raskid ugovora možete ispuniti'
)
WHERE `information_id` = 5 AND `language_id` = 2;

SET SESSION sql_mode = @return_original_sql_mode;

-- Produkcijska provjera: svaki rezultat mora biti 1, a collision_count 0.
SELECT
  (SELECT COUNT(*) FROM `information_schema`.`columns` WHERE `table_schema` = DATABASE() AND `table_name` = 'oc_return' AND `column_name` = 'invoice_number') AS `invoice_number_columns`,
  (SELECT COUNT(*) FROM `information_schema`.`columns` WHERE `table_schema` = DATABASE() AND `table_name` = 'oc_return' AND `column_name` = 'invoice_date') AS `invoice_date_columns`,
  (SELECT COUNT(*) FROM `information_schema`.`columns` WHERE `table_schema` = DATABASE() AND `table_name` = 'oc_return' AND `column_name` = 'refund_iban') AS `refund_iban_columns`,
  (SELECT COUNT(*) FROM `information_schema`.`columns` WHERE `table_schema` = DATABASE() AND `table_name` = 'oc_return' AND `column_name` = 'return_items') AS `return_items_columns`,
  (SELECT COUNT(*) FROM `oc_url_alias` WHERE `query` = 'account/return/add' AND `keyword` = 'obrazac-za-povrat') AS `url_alias_rows`,
  (SELECT COUNT(*) FROM `oc_hb_url` WHERE `route` = 'account/return/add' AND `keyword` = 'obrazac-za-povrat' AND `language_id` = 2 AND `store_id` = 0) AS `hb_url_rows`,
  (SELECT COUNT(*) FROM `oc_url_alias` WHERE `keyword` = 'obrazac-za-povrat' AND `query` <> 'account/return/add')
    + (SELECT COUNT(*) FROM `oc_hb_url` WHERE `keyword` = 'obrazac-za-povrat' AND `route` <> 'account/return/add' AND `language_id` = 2 AND `store_id` = 0) AS `collision_count`;
