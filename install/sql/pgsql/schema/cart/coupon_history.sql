DROP TABLE IF EXISTS coupon_history;

DROP SEQUENCE IF EXISTS coupon_history_seq;
CREATE SEQUENCE coupon_history_seq;

CREATE TABLE coupon_history (
  "coupon_history_id" int check ("coupon_history_id" > 0) NOT NULL DEFAULT NEXTVAL ('coupon_history_seq'),
  "coupon_id" int check ("coupon_id" > 0) NOT NULL,
  "order_id" int check ("order_id" > 0) NOT NULL,
  "user_id" int check ("user_id" > 0) NOT NULL,
  "quantity" decimal(15,4) NOT NULL,
  "date_added" timestamp(0) NOT NULL,
  PRIMARY KEY ("coupon_history_id")
);