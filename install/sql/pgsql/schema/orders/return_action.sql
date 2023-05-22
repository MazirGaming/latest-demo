DROP TABLE IF EXISTS return_action;

DROP SEQUENCE IF EXISTS return_action_seq;
CREATE SEQUENCE return_action_seq;

CREATE TABLE return_action (
  "return_action_id" int check ("return_action_id" > 0) NOT NULL DEFAULT NEXTVAL ('return_action_seq'),
  "language_id" int check ("language_id" > 0) NOT NULL DEFAULT 0,
  "name" varchar(64) NOT NULL,
  PRIMARY KEY ("return_action_id","language_id")
);