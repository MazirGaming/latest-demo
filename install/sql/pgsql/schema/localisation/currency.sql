DROP TABLE IF EXISTS currency;

DROP SEQUENCE IF EXISTS currency_seq;
CREATE SEQUENCE currency_seq;


CREATE TABLE currency (
  "currency_id" int check ("currency_id" > 0) NOT NULL DEFAULT NEXTVAL ('currency_seq'),
  "name" varchar(32) NOT NULL,
  "code" varchar(3) NOT NULL,
  "symbol_left" varchar(12) NOT NULL,
  "symbol_right" varchar(12) NOT NULL,
  "decimal_place" char(1) NOT NULL,
  "value" double precision NOT NULL,
  "status" smallint NOT NULL,
  "date_modified" timestamp(0) NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY ("currency_id")
);