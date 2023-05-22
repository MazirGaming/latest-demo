DROP TABLE IF EXISTS tax_rate;


DROP SEQUENCE IF EXISTS tax_rate_seq;
CREATE SEQUENCE tax_rate_seq;

CREATE TABLE tax_rate (
  "tax_rate_id" int check ("tax_rate_id" > 0) NOT NULL DEFAULT NEXTVAL ('tax_rate_seq'),
  "zone_group_id" int check ("zone_group_id" > 0) NOT NULL DEFAULT 0,
  "name" varchar(32) NOT NULL,
  "rate" decimal(15,4) NOT NULL DEFAULT 0.0000,
  "type" char(1) NOT NULL,
  "date_added" timestamp(0) NOT NULL,
  "date_modified" timestamp(0) NOT NULL,
  PRIMARY KEY ("tax_rate_id")
);