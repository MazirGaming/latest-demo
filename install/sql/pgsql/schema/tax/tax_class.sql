DROP TABLE IF EXISTS tax_class;


DROP SEQUENCE IF EXISTS tax_class_seq;
CREATE SEQUENCE tax_class_seq;

CREATE TABLE tax_class (
  "tax_class_id" int check ("tax_class_id" > 0) NOT NULL DEFAULT NEXTVAL ('tax_class_seq'),
  "name" varchar(32) NOT NULL,
  "description" varchar(191) NOT NULL,
  "date_added" timestamp(0) NOT NULL DEFAULT current_timestamp,
  "date_modified" timestamp(0) NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY ("tax_class_id")
);