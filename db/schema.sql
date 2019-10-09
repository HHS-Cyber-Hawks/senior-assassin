DROP TABLE IF EXISTS players
;

CREATE TABLE players (
  player_id  INT NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(45) NULL,
  last_name  VARCHAR(45) NULL,
  email      VARCHAR(100) NULL,
  PRIMARY KEY (player_id))
;

DROP TABLE IF EXISTS assignments
;

CREATE TABLE assignments (
  assignment_id INT NOT NULL AUTO_INCREMENT,
  attacker_id    INT NOT NULL,
  victim_id      INT NOT NULL,
  status         INT 0,
  PRIMARY KEY (assignments_id))
;
