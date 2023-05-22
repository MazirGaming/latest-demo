DROP TABLE IF EXISTS order_recurring_transaction;

DROP SEQUENCE IF EXISTS order_recurring_transaction_seq;
CREATE SEQUENCE order_recurring_transaction_seq;


CREATE TABLE order_recurring_transaction (
  "order_recurring_transaction_id" int check ("order_recurring_transaction_id" > 0) NOT NULL DEFAULT NEXTVAL ('order_recurring_transaction_seq'),
  "order_recurring_id" int check ("order_recurring_id" > 0) NOT NULL,
  "reference" varchar(191) NOT NULL,
  "type" int check ("type" > 0) NOT NULL,
  "quantity" decimal(10,4) NOT NULL,
  "date_added" timestamp(0) NOT NULL,
  PRIMARY KEY ("order_recurring_transaction_id")
);