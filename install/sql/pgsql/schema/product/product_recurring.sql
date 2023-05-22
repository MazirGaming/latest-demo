DROP TABLE IF EXISTS product_recurring;
CREATE TABLE product_recurring (
  "product_id" int check ("product_id" > 0) NOT NULL,
  "recurring_id" int check ("recurring_id" > 0) NOT NULL,
  "user_group_id" int check ("user_group_id" > 0) NOT NULL,
  PRIMARY KEY ("product_id","recurring_id","user_group_id")
);