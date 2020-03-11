DROP TABLE IF EXISTS players
;

CREATE TABLE players (
  player_id    INT NOT NULL AUTO_INCREMENT,
  player_name  VARCHAR(45) NULL,
  email        VARCHAR(100) NULL,
  PRIMARY KEY (player_id))
;

DROP TABLE IF EXISTS assignments
;

CREATE TABLE assignments (
  assignment_id  INT NOT NULL AUTO_INCREMENT,
  attacker_id    INT NOT NULL,
  target_id      INT NOT NULL,
  status         INT DEFAULT 0,
  round          INT DEFAULT 1,
  PRIMARY KEY (assignment_id))
;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `is_admin` int(0),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
