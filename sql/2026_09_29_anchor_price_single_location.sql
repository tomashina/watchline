-- Watchline sidrene cijene: automatska potvrda aktivnih artikala i jedna
-- prodajna jedinica. Skripta je idempotentna.

START TRANSACTION;

INSERT INTO `oc_anchor_price_audit` (
  `anchor_price_id`,
  `product_id`,
  `store_id`,
  `user_id`,
  `action`,
  `old_data`,
  `new_data`,
  `reason`,
  `date_added`
)
SELECT
  ap.`anchor_price_id`,
  ap.`product_id`,
  ap.`store_id`,
  0,
  'auto_confirm_active',
  JSON_OBJECT(
    'price', ap.`price`,
    'gross_price', ap.`gross_price`,
    'currency_code', ap.`currency_code`,
    'tax_class_id', ap.`tax_class_id`,
    'tax_context', ap.`tax_context`,
    'reference_date', ap.`reference_date`,
    'rule_code', ap.`rule_code`,
    'source', ap.`source`,
    'verification_status', ap.`verification_status`
  ),
  JSON_OBJECT(
    'price', ap.`price`,
    'gross_price', ap.`gross_price`,
    'currency_code', ap.`currency_code`,
    'tax_class_id', ap.`tax_class_id`,
    'tax_context', ap.`tax_context`,
    'reference_date', ap.`reference_date`,
    'rule_code', ap.`rule_code`,
    'source', ap.`source`,
    'verification_status', 'confirmed'
  ),
  'Automatic confirmation for an active product',
  NOW()
FROM `oc_anchor_price` ap
INNER JOIN `oc_product` p
  ON p.`product_id` = ap.`product_id`
INNER JOIN `oc_product_to_store` p2s
  ON p2s.`product_id` = p.`product_id`
 AND p2s.`store_id` = ap.`store_id`
WHERE ap.`verification_status` = 'pending'
  AND p.`status` = 1
  AND p.`date_available` <= CURDATE();

UPDATE `oc_anchor_price` ap
INNER JOIN `oc_product` p
  ON p.`product_id` = ap.`product_id`
INNER JOIN `oc_product_to_store` p2s
  ON p2s.`product_id` = p.`product_id`
 AND p2s.`store_id` = ap.`store_id`
SET ap.`verification_status` = 'confirmed',
    ap.`date_modified` = NOW()
WHERE ap.`verification_status` = 'pending'
  AND p.`status` = 1
  AND p.`date_available` <= CURDATE();

COMMIT;

SELECT COUNT(*) AS `active_products_without_confirmed_anchor`
FROM `oc_product` p
INNER JOIN `oc_product_to_store` p2s
  ON p2s.`product_id` = p.`product_id`
 AND p2s.`store_id` = 0
LEFT JOIN `oc_anchor_price` ap
  ON ap.`product_id` = p.`product_id`
 AND ap.`store_id` = p2s.`store_id`
 AND ap.`verification_status` = 'confirmed'
WHERE p.`status` = 1
  AND p.`date_available` <= CURDATE()
  AND ap.`anchor_price_id` IS NULL;
