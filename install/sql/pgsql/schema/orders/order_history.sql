DROP TABLE IF EXISTS order_history;

DROP SEQUENCE IF EXISTS order_history_seq;
CREATE SEQUENCE order_history_seq;


CREATE TABLE order_history (
  "order_history_id" int check ("order_history_id" > 0) NOT NULL DEFAULT NEXTVAL ('order_history_seq'),
  "order_id" int check ("order_id" > 0) NOT NULL,
  "order_status_id" int check ("order_status_id" > 0) NOT NULL,
  "notify" smallint NOT NULL DEFAULT 0,
  "comment" text NOT NULL,
  "date_added" timestamp(0) NOT NULL,
  PRIMARY KEY ("order_history_id")
);