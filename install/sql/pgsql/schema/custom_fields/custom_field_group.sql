DROP TABLE IF EXISTS custom_field_group;

DROP SEQUENCE IF EXISTS custom_field_group_seq;
CREATE SEQUENCE custom_field_group_seq;


CREATE TABLE custom_field_group (
  "custom_field_group_id" int NOT NULL DEFAULT NEXTVAL ('custom_field_group_seq'),
  "name" text NOT NULL,
  "status" smallint NOT NULL,
  "sort_order" int NOT NULL,
  PRIMARY KEY ("custom_field_group_id")
);