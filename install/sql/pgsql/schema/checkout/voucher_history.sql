DROP TABLE IF EXISTS voucher_history;

DROP SEQUENCE IF EXISTS voucher_history_seq;
CREATE SEQUENCE voucher_history_seq;


CREATE TABLE voucher_history (
  "voucher_history_id" int check ("voucher_history_id" > 0) NOT NULL DEFAULT NEXTVAL ('voucher_history_seq'),
  "voucher_id" int check ("voucher_id" > 0) NOT NULL,
  "order_id" int check ("order_id" > 0) NOT NULL,
  "quantity" decimal(15,4) NOT NULL,
  "date_added" timestamp(0) NOT NULL,
  PRIMARY KEY ("voucher_history_id")
);