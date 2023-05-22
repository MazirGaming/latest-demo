DROP TABLE IF EXISTS product_content;
CREATE TABLE product_content (
  "product_id" int check ("product_id" > 0) NOT NULL,
  "language_id" int check ("language_id" > 0) NOT NULL,
  "name" varchar(191) NOT NULL DEFAULT '',
  "slug" varchar(191) NOT NULL DEFAULT '',
  "content" text DEFAULT NULL,
  "tag" text DEFAULT NULL,
  "meta_title" varchar(191) NOT NULL DEFAULT '',
  "meta_description" varchar(191) NOT NULL DEFAULT '',
  "meta_keyword" varchar(191) NOT NULL DEFAULT '',
  PRIMARY KEY ("product_id","language_id")
);

CREATE INDEX "product_content_slug" ON product_content ("slug");
-- CREATE INDEX FULLTEXT KEY "search" ("name","content")