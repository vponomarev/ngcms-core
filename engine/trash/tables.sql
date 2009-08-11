-- -------------------------------------------------------- --
-- DEFAULT mySQL tables structure for NGCMS
-- -------------------------------------------------------- --

-- 
-- Table `PREFIX_config`
-- 

CREATE TABLE `XPREFIX_config` (
  `name` char(60),
  `value` char(100),
  PRIMARY KEY  (`key`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table `PREFIX_category`
-- 

CREATE TABLE `XPREFIX_category` (
  `id` int(10) NOT NULL auto_increment,
  `position` int(10) default NULL,
  `name` varchar(50) NOT NULL default '',
  `alt` varchar(50) NOT NULL default '',
  `flags` char(10) default '',
  `tpl` char(20) default '',
  `number` int default 0,
  `parent` int(10) default '0',
  `description` text,
  `keywords` text,
  `icon` varchar(255) NOT NULL,
  `alt_url` text,
  `orderby` varchar(30) default 'id desc',
  `posts` int default 0,
  `posorder` int default 0,
  `poslevel` int default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- -- 
-- -- Table 'PREFIX_comments'
-- -- 
-- 
-- CREATE TABLE `XPREFIX_comments` (
--   `id` int(11) NOT NULL auto_increment,
--   `postdate` int(10) NOT NULL default '0',
--   `post` int(10) NOT NULL default '0',
--   `author` varchar(100) NOT NULL default '',
--   `author_id` int(11) NOT NULL default '0',
--   `mail` varchar(100) NOT NULL default '',
--   `text` text NOT NULL,
--   `ip` varchar(50) NOT NULL default '',
--   `reg` tinyint(1) default '0',
--   `answer` text,
--   `name` varchar(100) NOT NULL default '',
--   PRIMARY KEY  (`id`),
--   KEY `c_post` (`post`)
-- ) ENGINE=MyISAM;
-- 
-- -- --------------------------------------------------------


-- 
-- Tabel 'PREFIX_FILES'
-- 

CREATE TABLE `XPREFIX_files` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `orig_name` varchar(100) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  `folder` varchar(100) NOT NULL default '',
  `date` int(10) NOT NULL default '0',
  `user` varchar(100) NOT NULL default '',
  `owner_id` int(10) default '0',
  `category` int(10) default '0',
  `linked_ds` int(10) default 0,
  `linked_id` int(10) default 0,
  `storage` int(1) default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table 'PREFIX_FLOOD'
-- 

CREATE TABLE `XPREFIX_flood` (
  `ip` varchar(15) NOT NULL default '',
  `id` int(10) default NULL,
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table 'PREFIX_IMAGES'
-- 

CREATE TABLE `XPREFIX_images` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `orig_name` varchar(100) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  `folder` varchar(100) NOT NULL default '',
  `date` int(10) NOT NULL default '0',
  `user` varchar(100) NOT NULL default '',
  `width` int(10) default 0,
  `height` int(10) default 0,
  `preview` tinyint(1) default '0',
  `p_width` int(10) default 0,
  `p_height` int(10) default 0,
  `owner_id` int(10) default '0',
  `stamp` int(10) default '0',
  `category` int(10) default '0',
  `linked_ds` int(10) default 0,
  `linked_id` int(10) default 0,
  `storage` int(1) default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table 'PREFIX_ipban`
-- 

CREATE TABLE `XPREFIX_ipban` (
  `id` int not null auto_increment,
  `addr` char(20),
  `atype` int default 0,
  `addr_start` int default 0,
  `addr_stop` int default 0,
  `netlen` int default 0,
  `flags` char(10) default '',
  `createdate` datetime,
  `reason` char(255),
  `hitcount` int default 0,
  PRIMARY KEY  (`id`),
  KEY `ban_start` (`addr_start`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table `PREFIX_news`
-- 

CREATE TABLE `XPREFIX_news` (
  `id` int(11) NOT NULL auto_increment,
  `postdate` int(10) NOT NULL default '0',
  `author` varchar(100) NOT NULL default '',
  `author_id` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `alt_name` varchar(255) default NULL,
  `mainpage` tinyint(1) default '1',
  `approve` tinyint(1) default '0',
  `views` int(10) default '0',
  `favorite` tinyint(1) default '0',
  `pinned` tinyint(1) default '0',
  `flags` tinyint(1) default '0',
  `attach_count` int(10) default `0`,
  `editdate` int(10) NOT NULL default '0',
  `catid` varchar(255) NOT NULL default '0',
  `xfields` text NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `rating` int(10) NOT NULL default '0',
  `votes` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `alt_name` (`alt_name`),
  KEY `news_title` (`title`),
  KEY `news_postdate` (`postdate`),
  KEY `news_editdate` (`editdate`),
  KEY `news_catid` (`catid`),
  KEY `news_view` (`views`),
  KEY `news_archive` (`favorite`, `approve`),
  KEY `news_main` (`pinned`,`postdate`,`approve`,`mainpage`),
  KEY `news_mainid` (`pinned`,`id`,`approve`,`mainpage`),
  KEY `news_altname` (`alt_name`),
  KEY `news_mainpage` (`approve`,`pinned`,`id`),
  KEY `news_mcount` (`mainpage`,`approve`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table `PREFIX_news_map`
-- 

CREATE TABLE `XPREFIX_news_map` (
  `newsID` int(11) default NULL,
  `categoryID` int(11) default NULL,
  KEY `newsID` (`newsID`),
  KEY `categoryID` (`categoryID`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table `PREFIX_static`
-- 

CREATE TABLE `XPREFIX_static` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `content` text,
  `alt_name` varchar(255) default '',
  `template` varchar(100) default '',
  `description` text,
  `keywords` text,
  `approve` tinyint(1) default 0,
  `flags` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `static_title` (`title`),
  KEY `static_altname` (`alt_name`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table `PREFIX_users`
-- 

CREATE TABLE `XPREFIX_users` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `mail` varchar(80) default NULL,
  `pass` varchar(32) default NULL,
  `news` int(10) default '0',
--  `com` int(10) default '0',
  `status` tinyint(1) default '4',
  `last` int(10) NOT NULL default '0',
  `reg` int(10) NOT NULL default '0',
  `site` varchar(100) default NULL,
  `icq` varchar(10) NOT NULL default '',
  `where_from` varchar(255) default NULL,
  `info` text,
  `avatar` varchar(100) NOT NULL default '',
  `photo` varchar(100) NOT NULL default '',
  `activation` varchar(25) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '0',
  `newpw` varchar(32) default NULL,
  `authcookie` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  KEY `users_name` (`name`),
  KEY `users_auth` (`authcookie`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table `PREFIX_users_pm`
-- 

CREATE TABLE `XPREFIX_users_pm` (
  `pmid` int(10) NOT NULL auto_increment,
  `from_id` int(10) default '0',
  `to_id` int(10) default '0',
  `pmdate` int(10) NOT NULL,
  `title` varchar(255) default NULL,
  `content` text NOT NULL,
  `viewed` tinyint(1) default '0',
  PRIMARY KEY  (`pmid`),
  KEY `from_id` (`from_id`,`to_id`,`viewed`)
) ENGINE=MyISAM;
