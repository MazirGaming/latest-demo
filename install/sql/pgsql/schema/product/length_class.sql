DROP TABLE IF EXISTS length_class;

DROP SEQUENCE IF EXISTS length_class_seq;
CREATE SEQUENCE length_class_seq;


CREATE TABLE length_class (
  "length_class_id" int check ("length_class_id" > 0) NOT NULL DEFAULT NEXTVAL ('length_class_seq'),
  "value" decimal(15,8) NOT NULL,
  PRIMARY KEY ("length_class_id")
);