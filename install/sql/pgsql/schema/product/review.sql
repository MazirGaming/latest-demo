DROP TABLE IF EXISTS review;

DROP SEQUENCE IF EXISTS review_seq;
CREATE SEQUENCE review_seq;


CREATE TABLE review (
  "review_id" int check ("review_id" > 0) NOT NULL DEFAULT NEXTVAL ('review_seq'),
  "product_id" int check ("product_id" > 0) NOT NULL,
  "user_id" int check ("user_id" > 0) NOT NULL,
  "author" varchar(64) NOT NULL,
  "content" text NOT NULL,
  "rating" int NOT NULL,
  "status" smallint NOT NULL DEFAULT 0,
  "date_added" timestamp(0) NOT NULL,
  "date_modified" timestamp(0) NOT NULL,
  PRIMARY KEY ("review_id")
);

CREATE INDEX "review_product_id" ON review ("product_id");