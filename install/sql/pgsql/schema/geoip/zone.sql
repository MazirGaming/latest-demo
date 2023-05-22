DROP TABLE IF EXISTS zone;

DROP SEQUENCE IF EXISTS zone_seq;
CREATE SEQUENCE zone_seq;


CREATE TABLE zone (
  "zone_id" int check ("zone_id" > 0) NOT NULL DEFAULT NEXTVAL ('zone_seq'),
  "country_id" int check ("country_id" > 0) NOT NULL,
  "name" varchar(128) NOT NULL,
  "code" varchar(32) NOT NULL,
  "status" smallint NOT NULL DEFAULT 1,
  PRIMARY KEY ("zone_id")
);