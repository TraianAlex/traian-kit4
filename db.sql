ALTER TABLE `users` ADD `salt` VARCHAR(120) NOT NULL AFTER `password`;
ALTER TABLE `admins` ADD `salt` VARCHAR(120) NOT NULL AFTER `password`;
ALTER TABLE `users` DROP `active`;
ALTER TABLE `admins` DROP `active`;

CREATE TABLE IF NOT EXISTS `users_session` (
`id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `hash` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users_session`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `users_session`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `fld_user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fld_google_id` varchar(100) NOT NULL,
  `fld_user_name` varchar(60) NOT NULL,
  `fld_user_email` varchar(60) DEFAULT NULL,
  `fld_user_doj` int(10) NOT NULL,
  PRIMARY KEY (`fld_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tbl_users_fb` (
	  `fld_user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `fld_facebook_id` varchar(100) NOT NULL,
	  `fld_user_name` varchar(60) NOT NULL,
	  `fld_user_email` varchar(60) DEFAULT NULL,
	  `fld_user_doj` int(10) NOT NULL,
	  PRIMARY KEY (`fld_user_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;