DROP TABLE IF EXISTS custom_field;

DROP SEQUENCE IF EXISTS custom_field_seq;
CREATE SEQUENCE custom_field_seq;


CREATE TABLE custom_field (
  "custom_field_id" int NOT NULL DEFAULT NEXTVAL ('custom_field_seq'),
  "custom_field_group_id" int NOT NULL,
  "type" varchar(32) NOT NULL,
  "value" text NOT NULL,
  "status" smallint NOT NULL,
  "sort_order" int NOT NULL,
  PRIMARY KEY ("custom_field_id")
);