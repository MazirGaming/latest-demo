DROP TABLE IF EXISTS coupon_category;
CREATE TABLE coupon_category (
  "coupon_id" int check ("coupon_id" > 0) NOT NULL,
  "taxonomy_item_id" int check ("taxonomy_item_id" > 0) NOT NULL,
  PRIMARY KEY ("coupon_id","taxonomy_item_id")
);