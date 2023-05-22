DROP TABLE IF EXISTS location;

DROP SEQUENCE IF EXISTS location_seq;
CREATE SEQUENCE location_seq;


CREATE TABLE location (
  "location_id" int check ("location_id" > 0) NOT NULL DEFAULT NEXTVAL ('location_seq'),
  "name" varchar(32) NOT NULL,
  "address" text NOT NULL,
  "phone_number" varchar(32) NOT NULL,
  "fax" varchar(32) NOT NULL,
  "geocode" varchar(32) NOT NULL,
  "image" varchar(191) NOT NULL,
  "open" text NOT NULL,
  "comment" text NOT NULL,
  PRIMARY KEY ("location_id")
);

CREATE INDEX "location_name" ON location ("name");