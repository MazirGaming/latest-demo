DROP TABLE IF EXISTS download;

DROP SEQUENCE IF EXISTS download_seq;
CREATE SEQUENCE download_seq;


CREATE TABLE download (
  "download_id" int check ("download_id" > 0) NOT NULL DEFAULT NEXTVAL ('download_seq'),
  "filename" varchar(160) NOT NULL,
  "mask" varchar(128) NOT NULL,
  "date_added" timestamp(0) NOT NULL,
  PRIMARY KEY ("download_id")
);