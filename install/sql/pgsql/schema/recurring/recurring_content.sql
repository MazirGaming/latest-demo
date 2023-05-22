DROP TABLE IF EXISTS recurring_content;
CREATE TABLE recurring_content (
  "recurring_id" int check ("recurring_id" > 0) NOT NULL,
  "language_id" int check ("language_id" > 0) NOT NULL,
  "name" varchar(191) NOT NULL,
  PRIMARY KEY ("recurring_id","language_id")
);