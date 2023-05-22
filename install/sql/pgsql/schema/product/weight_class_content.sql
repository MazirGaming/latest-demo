DROP TABLE IF EXISTS weight_class_content;
CREATE TABLE weight_class_content (
  "weight_class_id" int check ("weight_class_id" > 0) NOT NULL,
  "language_id" int check ("language_id" > 0) NOT NULL,
  "title" varchar(32) NOT NULL,
  "unit" varchar(4) NOT NULL,
  PRIMARY KEY ("weight_class_id","language_id")
);