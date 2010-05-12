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


-- --------------------------------------------------------

-- 
-- Table `tl_news`
-- 

CREATE TABLE `tl_news` (
  `sitemap_lastmod` varchar(11) NOT NULL default '',
  `sitemap_changefreq` varchar(10) NOT NULL default '',
  `sitemap_priority` varchar(3) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

