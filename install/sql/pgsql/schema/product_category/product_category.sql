DROP TABLE IF EXISTS product_category;

DROP SEQUENCE IF EXISTS product_category_seq;
CREATE SEQUENCE product_category_seq;


CREATE TABLE product_category (
  "product_taxonomy_item_id" int check ("product_taxonomy_item_id" > 0) NOT NULL DEFAULT NEXTVAL ('product_category_seq'),
  "image" varchar(191) NOT NULL DEFAULT '',
  "parent_id" int check ("parent_id" >= 0) NOT NULL DEFAULT 0,
  "top" smallint NOT NULL DEFAULT 0,
  "column" int NOT NULL DEFAULT 0,
  "sort_order" int NOT NULL DEFAULT 0,
  "status" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("product_taxonomy_item_id")
);

CREATE INDEX "product_category_parent_id" ON product_category ("parent_id");