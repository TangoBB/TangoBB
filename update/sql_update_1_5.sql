/*
    Change the iko_ prefix to your prefix!!!!
 */

CREATE TABLE `iko_user_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_owner` int(11) NOT NULL,
  `writer` int(11) NOT NULL,
  `comment` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE `iko_user_visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_owner` int(11) NOT NULL,
  `visitor` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);