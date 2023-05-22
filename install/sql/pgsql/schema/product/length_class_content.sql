DROP TABLE IF EXISTS length_class_content;
CREATE TABLE length_class_content (
  "length_class_id" int check ("length_class_id" > 0) NOT NULL,
  "language_id" int check ("language_id" > 0) NOT NULL,
  "name" varchar(32) NOT NULL,
  "unit" varchar(4) NOT NULL,
  PRIMARY KEY ("length_class_id","language_id")
);