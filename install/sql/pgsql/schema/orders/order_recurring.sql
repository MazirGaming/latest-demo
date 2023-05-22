DROP TABLE IF EXISTS order_recurring;

DROP SEQUENCE IF EXISTS order_recurring_seq;
CREATE SEQUENCE order_recurring_seq;


CREATE TABLE order_recurring (
  "order_recurring_id" int check ("order_recurring_id" > 0) NOT NULL DEFAULT NEXTVAL ('order_recurring_seq'),
  "order_id" int check ("order_id" > 0) NOT NULL,
  "reference" varchar(191) NOT NULL,
  "product_id" int check ("product_id" > 0) NOT NULL,
  "product_name" varchar(191) NOT NULL,
  "product_quantity" int check ("product_quantity" > 0) NOT NULL,
  "recurring_id" int check ("recurring_id" > 0) NOT NULL,
  "recurring_name" varchar(191) NOT NULL,
  "recurring_content" varchar(191) NOT NULL,
  "recurring_frequency" varchar(25) NOT NULL,
  "recurring_cycle" smallint NOT NULL,
  "recurring_duration" smallint NOT NULL,
  "recurring_price" decimal(10,4) NOT NULL,
  "trial" smallint NOT NULL,
  "trial_frequency" varchar(25) NOT NULL,
  "trial_cycle" smallint NOT NULL,
  "trial_duration" smallint NOT NULL,
  "trial_price" decimal(10,4) NOT NULL,
  "status" smallint NOT NULL,
  "date_added" timestamp(0) NOT NULL,
  PRIMARY KEY ("order_recurring_id")
);