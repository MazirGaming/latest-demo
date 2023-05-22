DROP TABLE IF EXISTS user_group;

DROP SEQUENCE IF EXISTS user_group_seq;
CREATE SEQUENCE user_group_seq;


CREATE TABLE user_group (
  "user_group_id" int check ("user_group_id" > 0) NOT NULL DEFAULT NEXTVAL ('user_group_seq'),
  "approval" int NOT NULL,
  "sort_order" int NOT NULL,
  PRIMARY KEY ("user_group_id")
);
