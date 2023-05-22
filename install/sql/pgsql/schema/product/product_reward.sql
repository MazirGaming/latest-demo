DROP TABLE IF EXISTS product_reward;

DROP SEQUENCE IF EXISTS product_reward_seq;
CREATE SEQUENCE product_reward_seq;


CREATE TABLE product_reward (
  "product_reward_id" int check ("product_reward_id" > 0) NOT NULL DEFAULT NEXTVAL ('product_reward_seq'),
  "product_id" int check ("product_id" > 0) NOT NULL DEFAULT 0,
  "user_group_id" int check ("user_group_id" > 0) NOT NULL DEFAULT 0,
  "points" int NOT NULL DEFAULT 0,
  PRIMARY KEY ("product_reward_id")
);