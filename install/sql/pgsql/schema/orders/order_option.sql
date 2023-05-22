DROP TABLE IF EXISTS order_option;

DROP SEQUENCE IF EXISTS order_option_seq;
CREATE SEQUENCE order_option_seq;


CREATE TABLE order_option (
  "order_option_id" int check ("order_option_id" > 0) NOT NULL DEFAULT NEXTVAL ('order_option_seq'),
  "order_id" int check ("order_id" > 0) NOT NULL,
  "order_product_id" int check ("order_product_id" > 0) NOT NULL,
  "product_option_id" int check ("product_option_id" > 0) NOT NULL,
  "product_option_value_id" int check ("product_option_value_id" > 0) NOT NULL DEFAULT 0,
  "name" varchar(191) NOT NULL,
  "value" text NOT NULL,
  "type" varchar(32) NOT NULL,
  PRIMARY KEY ("order_option_id")
);