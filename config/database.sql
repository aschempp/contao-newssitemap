-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_news_archive`
-- 

CREATE TABLE `tl_news_archive` (
  `sitemap` char(1) NOT NULL default '',
  `sitemapPages` blob NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

