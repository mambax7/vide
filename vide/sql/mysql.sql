CREATE TABLE `videlog` (
  `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
  `titre`         varchar(25)   NOT NULL DEFAULT '',
  `created`       datetime      NOT NULL,
  `description`   varchar (100) NOT NULL DEFAULT '',
  `userid`        int(11) unsigned  NOT NULL DEFAULT '0',
  `ip`            varchar(50)   NOT NULL DEFAULT '',
  `url`           varchar(150)  NOT NULL DEFAULT '',
  `provenance`    varchar(150)  NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `videdivers` (
  `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nom`           varchar (25)  NOT NULL DEFAULT '',
  `valeur`        varchar (150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `videcategorie` (
  `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid`           int(11) unsigned NOT NULL DEFAULT '0',
  `nom`           varchar(100) NOT NULL DEFAULT'',
  `created`       datetime,
  `descriptif`    text NOT NULL DEFAULT '',
  `keywords`      text NOT NULL DEFAULT '',
  `image`         varchar(200) NOT NULL DEFAULT '',
  `actif`         tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `videitem` (
  `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid`           int(11) unsigned NOT NULL DEFAULT '0',
  `nom`           varchar(100) NOT NULL DEFAULT'',
  `created`       datetime,
  `descriptif`    text NOT NULL DEFAULT '',
  `keywords`      text NOT NULL DEFAULT '',
  `fichier`         varchar(200) NOT NULL DEFAULT '',
  `actif`         tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;