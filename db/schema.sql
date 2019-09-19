DROP TABLE IF EXISTS players
;

CREATE TABLE players (
  id         INT NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(45) NULL,
  last_name  VARCHAR(45) NULL,
  email      VARCHAR(100) NULL,
  PRIMARY KEY (id))
;
