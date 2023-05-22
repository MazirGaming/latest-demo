DROP TABLE IF EXISTS zone_to_zone_group;

DROP SEQUENCE IF EXISTS zone_to_zone_group_seq;
CREATE SEQUENCE zone_to_zone_group_seq;

CREATE TABLE zone_to_zone_group (
  "zone_to_zone_group_id" int check ("zone_to_zone_group_id" > 0) NOT NULL DEFAULT NEXTVAL ('zone_to_zone_group_seq'),
  "country_id" int check ("country_id" > 0) NOT NULL,
  "zone_id" int check ("zone_id" > 0) NOT NULL DEFAULT 0,
  "zone_group_id" int check ("zone_group_id" > 0) NOT NULL,
  "date_added" timestamp(0) NOT NULL,
  PRIMARY KEY ("zone_to_zone_group_id")
);