DROP TABLE IF EXISTS download_content;
CREATE TABLE download_content (
  "download_id" int check ("download_id" > 0) NOT NULL,
  "language_id" int check ("language_id" > 0) NOT NULL,
  "name" varchar(64) NOT NULL,
  PRIMARY KEY ("download_id","language_id")
);