DROP TABLE IF EXISTS product_to_download;
CREATE TABLE product_to_download (
  "product_id" int check ("product_id" > 0) NOT NULL,
  "download_id" int check ("download_id" > 0) NOT NULL,
  PRIMARY KEY ("product_id","download_id")
);