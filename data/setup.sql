delimiter $$

CREATE TABLE `invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invite_by` varchar(45) NOT NULL,
  `invite_code` varchar(45) DEFAULT NULL,
  `invite_name` varchar(512) DEFAULT NULL,
  `invite_email` varchar(512) DEFAULT NULL,
  `invite_created` datetime DEFAULT NULL,
  `invite_status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`invite_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `profile_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(36) NOT NULL,
  `file_name` varchar(256) DEFAULT NULL,
  `description` text,
  `user_image` int(11) DEFAULT '0',
  `sort` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`user_id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$

delimiter $$

CREATE TABLE `profile_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(36) NOT NULL,
  `public_profile` int(11) DEFAULT NULL,
  `show_descriptions` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `profile_kvs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(45) DEFAULT NULL,
  `k` varchar(512) DEFAULT NULL,
  `v` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(45) NOT NULL,
  `fullname` varchar(256) DEFAULT NULL,
  `summary` text,
  `type` int(11) DEFAULT NULL,
  `birthdate` datetime DEFAULT NULL,
  `public_profile` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(36) NOT NULL,
  `username` varchar(70) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `password` varchar(70) DEFAULT NULL,
  `contributor` int(11) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `optin` int(11) DEFAULT '1',
  PRIMARY KEY (`id`,`uuid`),
  UNIQUE KEY `uuid_UNIQUE` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `activity_feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` varchar(45) NOT NULL,
  `actor` varchar(45) NOT NULL,
  `target` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `title` varchar(512) DEFAULT NULL,
  `story` text,
  `parent_id` varchar(45) DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`feed_id`,`actor`,`target`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8$$



/////////////////////////////////////////////////////////////////////////////////////

delimiter $$

DROP TABLE codes$$


delimiter $$

CREATE TABLE `proofs_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_by` varchar(45) NOT NULL,
  `code_code` varchar(45) DEFAULT NULL,
  `code_name` varchar(512) DEFAULT NULL,
  `code_email` varchar(512) DEFAULT NULL,
  `code_created` datetime DEFAULT NULL,
  `code_status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`code_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$


/////////////////////////////////////////////////////////////////////////////////////

ALTER TABLE 
`flugoodence`.`profile_images` ADD COLUMN `type` INT NULL  AFTER `user_image` ;

ALTER TABLE 
`flugoodence`.`profile_images` CHANGE COLUMN `type` `type` INT(11) NULL DEFAULT 1  ;

update portfolio_images set type = 1;

ALTER TABLE 
`flugoodence`.`profile_images` CHANGE COLUMN `type` `category_id` INT(11) NULL DEFAULT '1'  ;

ALTER TABLE 
`flugoodence`.`profile_images` ADD COLUMN `published` DATETIME NULL  AFTER `sort` ;

/////////////////////////////////////////////////////////////////////////////////////

ALTER TABLE 
`flugoodence`.`profile_settings` ADD COLUMN `featured` INT NULL DEFAULT 0  AFTER `show_descriptions` ;

/////////////////////////////////////////////////////////////////////////////////////

delimiter $$

CREATE TABLE `relationships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actor` varchar(45) NOT NULL,
  `target` varchar(45) NOT NULL,
  `note` text,
  `status` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`actor`,`target`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$

ALTER TABLE 
`flugoodence`.`profiles` ADD COLUMN `location` VARCHAR(45) NULL  AFTER `fullname` ;


ALTER TABLE 
`flugoodence`.`activity_feed` ADD COLUMN `feeds` INT NULL DEFAULT 3  AFTER `published` ;

/////////////////////////////////////////////////////////////////////////////////////

delimiter $$

CREATE TABLE `profile_views` (
  `user_id` varchar(45) NOT NULL,
  `profile_id` varchar(45) NOT NULL,
  `views` int(11) DEFAULT '1',
  `new` int(11) DEFAULT '1',
  `last_viewed` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`portfolio_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$


/////////////////////////////////////////////////////////////////////////////////////


delimiter $$

CREATE TABLE `abuse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(45) NOT NULL,
  `type_id` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `comments` text,
  `reported_on` datetime DEFAULT NULL,
  `reported_by` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`,`user_id`,`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$


ALTER TABLE 
`flugoodence`.`users` ADD COLUMN `status` INT NULL DEFAULT 1  AFTER `optin`;

/////////////////////////////////////////////////////////////////////////////////////

delimiter $$

CREATE PROCEDURE `LoginsByDay`()
BEGIN
        
        SET @total:= 0;

        select 
            (UNIX_TIMESTAMP(DATE(users.date)) * 1000) as date, 
            users.logins
        from 
            (
                SELECT 
                    DATE(lastlogin) as date, 
                    COUNT(*) as logins 
                FROM 
                    users 
                GROUP BY 
                    DATE(lastlogin)
            ) as users;

    END$$

DELIMITER ;


delimiter $$

CREATE PROCEDURE `UserByDay`()
BEGIN
        
        SET @total:= 0;

        select 
            (UNIX_TIMESTAMP(DATE(users.date)) * 1000) as date, 
            users.new, 
            (@total := @total + users.new) AS total 
        from 
            (
                SELECT 
                    DATE(created) as date, 
                    COUNT(*) as new 
                FROM 
                    users 
                GROUP BY 
                    DATE(created)
            ) as users;

    END$$
	
DELIMITER ;


/////////////////////////////////////////////////////////////////////////////////////
//
// CREATED: 8/15/2013  
// MOVED TO PRODUCTION: 8/15/2013
//
/////////////////////////////////////////////////////////////////////////////////////

ALTER TABLE 
`flugoodence`.`activity_feed` ADD COLUMN `likes` INT NULL DEFAULT 0  AFTER `status` ;
ALTER TABLE 
`flugoodence`.`activity_feed` CHANGE COLUMN `likes` `likes` INT(11) NULL DEFAULT '0'  ;

CREATE  TABLE 
`flugoodence`.`activity_feed_likes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `feed_id` VARCHAR(45) NULL ,
  `user_id` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) );


/////////////////////////////////////////////////////////////////////////////////////
//
// CREATED: 8/16/2013  
// MOVED TO PRODUCTION: 8/16/2013  
//
/////////////////////////////////////////////////////////////////////////////////////

ALTER TABLE 
`flugoodence`.`activity_feed` 
	ADD COLUMN `notification` VARCHAR(512) NULL  AFTER `likes` , 
	ADD COLUMN `notification_url` VARCHAR(512) NULL  AFTER `notification` , 
	ADD COLUMN `viewed` INT NULL DEFAULT 0  AFTER `notification_url` ;


/////////////////////////////////////////////////////////////////////////////////////
//
// CREATED: 8/18/2013  
// MOVED TO PRODUCTION: 
//
/////////////////////////////////////////////////////////////////////////////////////

CREATE TABLE `message_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` varchar(45) DEFAULT NULL,
  `user_id` varchar(45) DEFAULT NULL,
  `sender` int(11) DEFAULT '0',
  `viewed` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8


CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` varchar(45) DEFAULT NULL,
  `parent_id` varchar(45) DEFAULT NULL,
  `message` text,
  `message_date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8



