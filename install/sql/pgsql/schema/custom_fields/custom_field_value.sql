DROP TABLE IF EXISTS custom_field_value;

DROP SEQUENCE IF EXISTS custom_field_value_seq;
CREATE SEQUENCE custom_field_value_seq;


CREATE TABLE custom_field_value (
  "custom_field_value_id" int NOT NULL DEFAULT NEXTVAL ('custom_field_value_seq'),
  "custom_field_id" int NOT NULL,
  "sort_order" int NOT NULL,
  PRIMARY KEY ("custom_field_value_id")
);