DROP TABLE IF EXISTS product_category_to_site;
CREATE TABLE product_category_to_site (
  "product_taxonomy_item_id" int check ("product_taxonomy_item_id" > 0) NOT NULL,
  "site_id" smallint NOT NULL,
  PRIMARY KEY ("product_taxonomy_item_id","site_id")
);