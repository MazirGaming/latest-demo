DROP TABLE IF EXISTS return_history;

DROP SEQUENCE IF EXISTS return_history_seq;
CREATE SEQUENCE return_history_seq;


CREATE TABLE return_history (
  "return_history_id" int check ("return_history_id" > 0) NOT NULL DEFAULT NEXTVAL ('return_history_seq'),
  "return_id" int check ("return_id" > 0) NOT NULL,
  "return_status_id" int check ("return_status_id" > 0) NOT NULL,
  "notify" smallint NOT NULL,
  "comment" text NOT NULL,
  "date_added" timestamp(0) NOT NULL,
  PRIMARY KEY ("return_history_id")
);