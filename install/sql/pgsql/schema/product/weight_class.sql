DROP TABLE IF EXISTS weight_class;

DROP SEQUENCE IF EXISTS weight_class_seq;
CREATE SEQUENCE weight_class_seq;


CREATE TABLE weight_class (
  "weight_class_id" int check ("weight_class_id" > 0) NOT NULL DEFAULT NEXTVAL ('weight_class_seq'),
  "value" decimal(15,8) NOT NULL DEFAULT 0.00000000,
  PRIMARY KEY ("weight_class_id")
);