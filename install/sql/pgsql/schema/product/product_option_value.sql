DROP TABLE IF EXISTS product_option_value;

DROP SEQUENCE IF EXISTS product_option_value_seq;
CREATE SEQUENCE product_option_value_seq;


CREATE TABLE product_option_value (
  "product_option_value_id" int check ("product_option_value_id" > 0) NOT NULL DEFAULT NEXTVAL ('product_option_value_seq'),
  "product_option_id" int check ("product_option_id" > 0) NOT NULL,
  "product_id" int check ("product_id" > 0) NOT NULL,
  "option_id" int check ("option_id" > 0) NOT NULL,
  "option_value_id" int check ("option_value_id" > 0) NOT NULL,
  "quantity" int NOT NULL,
  "subtract" smallint NOT NULL,
  "price" decimal(15,4) NOT NULL,
  "price_prefix" varchar(1) NOT NULL,
  "points" int NOT NULL,
  "points_prefix" varchar(1) NOT NULL,
  "weight" decimal(15,8) NOT NULL,
  "weight_prefix" varchar(1) NOT NULL,
  PRIMARY KEY ("product_option_value_id")
);