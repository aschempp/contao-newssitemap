<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2009
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Configuration
 */
$GLOBALS['TL_DCA']['tl_news']['config']['onload_callback'][] = array('tl_news_sitemap', 'injectFields');
$GLOBALS['TL_DCA']['tl_news']['config']['onsubmit_callback'][] = array('tl_news_sitemap', 'generateSitemap');


/**
 * Listing
 */
$GLOBALS['TL_DCA']['tl_news']['list']['sorting']['headerFields'][] = 'sitemap';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_news']['fields']['sitemap_lastmod'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_news']['sitemap_lastmod'],
	'exclude'				=> true,
	'inputType'				=> 'text',
	'default'				=> time(),
	'eval'					=> array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString(), 'tl_class'=>'clr wizard')
);

$GLOBALS['TL_DCA']['tl_news']['fields']['sitemap_changefreq'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_news']['sitemap_changefreq'],
	'exclude'				=> true,
	'inputType'				=> 'select',
	'options'				=> array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'),
	'reference'				=> &$GLOBALS['TL_LANG']['MSC']['sitemap_changefreq'],
	'eval'					=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_news']['fields']['sitemap_priority'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_news']['sitemap_priority'],
	'exclude'				=> true,
	'inputType'				=> 'select',
	'options'				=> array('0.0', '0.1', '0.2', '0.3', '0.4', '0.5', '0.6', '0.7', '0.8', '0.9', '1.0'),
	'eval'					=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
);



class tl_news_sitemap extends Backend
{
	
	/**
	 * Update the XML sitemap when news are saved.
	 * 
	 * @access public
	 * @return void
	 */
	public function generateSitemap($dc)
	{
		$objArchive = $this->Database->prepare("SELECT a.* FROM tl_news_archive a LEFT OUTER JOIN tl_news n ON n.pid=a.id WHERE n.id=?")->limit(1)->execute($dc->id);
		
		if ($objArchive->sitemap)
		{
			$this->import('GoogleSitemap');
			$this->GoogleSitemap->generateSitemap();
		}
	}
	
	
	public function injectFields($dc)
	{
		$objArchive = $this->Database->prepare("SELECT tl_news_archive.* FROM tl_news LEFT JOIN tl_news_archive ON tl_news.pid=tl_news_archive.id WHERE tl_news.id=?")->execute($dc->id);
		
		if ($objArchive->sitemap)
		{
			foreach( $GLOBALS['TL_DCA']['tl_news']['palettes'] as $name => $palette )
			{
				if ($name == '__selector__')
					continue;
					
				$GLOBALS['TL_DCA']['tl_news']['palettes'][$name] .= ';{sitemap_legend:hide},sitemap_lastmod,sitemap_changefreq,sitemap_priority';
			}
		}
	}
}

