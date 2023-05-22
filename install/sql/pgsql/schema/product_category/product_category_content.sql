DROP TABLE IF EXISTS product_category_content;
CREATE TABLE product_category_content (
  "product_taxonomy_item_id" int check ("product_taxonomy_item_id" > 0) NOT NULL,
  "language_id" int check ("language_id" > 0) NOT NULL,
  "name" varchar(191) NOT NULL,
  "slug" varchar(191) NOT NULL DEFAULT '',
  "description" text NOT NULL,
  "meta_title" varchar(191) NOT NULL DEFAULT '',
  "meta_description" varchar(191) NOT NULL DEFAULT '',
  "meta_keyword" varchar(191) NOT NULL DEFAULT '',
  PRIMARY KEY ("product_taxonomy_item_id","language_id")
);

CREATE INDEX "product_category_content_name" ON product_category_content ("name");