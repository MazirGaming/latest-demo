DROP TABLE IF EXISTS zone_group;

DROP SEQUENCE IF EXISTS zone_group_seq;
CREATE SEQUENCE zone_group_seq;


CREATE TABLE zone_group (
  "zone_group_id" int check ("zone_group_id" > 0) NOT NULL DEFAULT NEXTVAL ('zone_group_seq'),
  "name" varchar(32) NOT NULL,
  "description" varchar(191) NOT NULL,
  "date_added" timestamp(0) NOT NULL,
  PRIMARY KEY ("zone_group_id")
);