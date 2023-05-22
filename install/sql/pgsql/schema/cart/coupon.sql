DROP TABLE IF EXISTS coupon;

DROP SEQUENCE IF EXISTS coupon_seq;
CREATE SEQUENCE coupon_seq;


CREATE TABLE coupon (
  "coupon_id" int check ("coupon_id" > 0) NOT NULL DEFAULT NEXTVAL ('coupon_seq'),
  "name" varchar(128) NOT NULL,
  "code" varchar(20) NOT NULL,
  "type" char(1) NOT NULL,
  "discount" decimal(15,4) NOT NULL,
  "logged" smallint NOT NULL,
  "shipping" smallint NOT NULL,
  "total" decimal(15,4) NOT NULL,
  "date_start" date NOT NULL DEFAULT '1000-01-01',
  "date_end" date NOT NULL DEFAULT '1000-01-01',
  "uses_total" int check ("uses_total" > 0) NOT NULL,
  "uses_user" varchar(11) NOT NULL,
  "status" smallint NOT NULL,
  "date_added" timestamp(0) NOT NULL,
  PRIMARY KEY ("coupon_id")
);