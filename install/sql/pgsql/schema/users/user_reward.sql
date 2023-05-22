DROP TABLE IF EXISTS user_reward;

DROP SEQUENCE IF EXISTS user_reward_seq;
CREATE SEQUENCE user_reward_seq;


CREATE TABLE user_reward (
  "user_reward_id" int check ("user_reward_id" > 0) NOT NULL DEFAULT NEXTVAL ('user_reward_seq'),
  "user_id" int check ("user_id" > 0) NOT NULL DEFAULT 0,
  "order_id" int check ("order_id" > 0) NOT NULL DEFAULT 0,
  "description" text NOT NULL,
  "points" int NOT NULL DEFAULT 0,
  "date_added" timestamp(0) NOT NULL,
  PRIMARY KEY ("user_reward_id")
);