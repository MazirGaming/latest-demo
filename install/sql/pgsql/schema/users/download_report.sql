DROP TABLE IF EXISTS download_report;

DROP SEQUENCE IF EXISTS download_report_seq;
CREATE SEQUENCE download_report_seq;


CREATE TABLE download_report (
  "download_report_id" int check ("download_report_id" > 0) NOT NULL DEFAULT NEXTVAL ('download_report_seq'),
  "download_id" int check ("download_id" > 0) NOT NULL,
  "site_id" smallint NOT NULL,
  "ip" varchar(40) NOT NULL,
  "country" varchar(2) NOT NULL,
  "date_added" timestamp(0) NOT NULL,
  PRIMARY KEY ("download_report_id")
);