SET NAMES utf8;

create database wowtest CHARACTER SET utf8 COLLATE utf8_general_ci;

use wowtest;

CREATE TABLE `wowtest`.`document` (
  `id`          VARCHAR(20)  NOT NULL,
  `name`        VARCHAR(255) NOT NULL,
  `pages_count` INT          NOT NULL,
  `created`     INT          NOT NULL,
  PRIMARY KEY (`ID`)
)
  ENGINE = InnoDB;

