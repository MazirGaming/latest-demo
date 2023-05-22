DROP TABLE IF EXISTS product_special;

DROP SEQUENCE IF EXISTS product_special_seq;
CREATE SEQUENCE product_special_seq;


CREATE TABLE product_special (
  "product_special_id" int check ("product_special_id" > 0) NOT NULL DEFAULT NEXTVAL ('product_special_seq'),
  "product_id" int check ("product_id" > 0) NOT NULL,
  "user_group_id" int check ("user_group_id" > 0) NOT NULL,
  "priority" int NOT NULL DEFAULT 1,
  "price" decimal(15,4) NOT NULL DEFAULT 0.0000,
  "date_start" date NOT NULL DEFAULT '1000-01-01',
  "date_end" date NOT NULL DEFAULT '1000-01-01',
  PRIMARY KEY ("product_special_id")
);

CREATE INDEX "product_special_product_id" ON product_special ("product_id");