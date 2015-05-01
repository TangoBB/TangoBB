/*
    Change the tan_ prefix to your prefix!!!!
 */

CREATE TABLE IF NOT EXISTS `tan_user_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_owner` int(11) NOT NULL,
  `writer` int(11) NOT NULL,
  `comment` text NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tan_user_visitors` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`profile_owner` int(11) NOT NULL,
`visitor` int(11) NOT NULL,
`timestamp` int(11) NOT NULL,
PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tan_themes` (
  `id` int(11) NOT NULL,
  `theme_name` varchar(255) NOT NULL,
  `theme_version` varchar(255) NOT NULL DEFAULT '1',
  `theme_json_data` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `tan_forum_posts` ADD FULLTEXT search(post_title, post_content);

ALTER TABLE `tan_generic`
  ADD COLUMN `number_subs` INT(3) DEFAULT 3  NOT NULL AFTER `post_merge`;

ALTER TABLE `tan_themes` CHANGE `theme_json_data` `theme_json_data` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `tan_users` CHANGE `chosen_theme` `chosen_theme` INT NOT NULL DEFAULT '0';

CREATE TABLE `tan_labels`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `node_id` INT(11) NOT NULL,
  `label` VARCHAR(1000) NOT NULL,
  PRIMARY KEY (`id`))ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE `tan_forum_posts`
  ADD COLUMN `label` INT(11) NOT NULL AFTER `watchers`;

CREATE TABLE `tan_poll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `thread_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `tan_poll_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `tan_poll_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `tan_users` ADD `display_group` INT NOT NULL AFTER `user_group`;

INSERT INTO `tan_terminal` (`id`, `command_name`, `command_syntax`, `run_function`) VALUES (NULL, 'dugroup', 'dugroup %s %s', 'dugroup');

ALTER TABLE `tan_generic` ADD `flat_ui_admin` INT NOT NULL DEFAULT '0' AFTER `post_merge`;

