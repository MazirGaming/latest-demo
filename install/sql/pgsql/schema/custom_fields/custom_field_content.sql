DROP TABLE IF EXISTS custom_field_content;
CREATE TABLE custom_field_content (
  "custom_field_id" int NOT NULL,
  "language_id" int NOT NULL,
  "name" varchar(128) NOT NULL,
  PRIMARY KEY ("custom_field_id","language_id")
);