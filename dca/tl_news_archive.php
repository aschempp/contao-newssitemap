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
$GLOBALS['TL_DCA']['tl_news_archive']['config']['onsubmit_callback'][] = array('tl_news_archive_sitemap', 'generateSitemap');


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_news_archive']['palettes']['__selector__'][] = 'sitemap';
$GLOBALS['TL_DCA']['tl_news_archive']['palettes']['default'] .= ';{sitemap_legend},sitemap';
$GLOBALS['TL_DCA']['tl_news_archive']['subpalettes']['sitemap'] = 'sitemapPages';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_news_archive']['fields']['sitemap'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_news_archive']['sitemap'],
	'inputType'			=> 'checkbox',
	'exclude'			=> true,
	'eval'				=> array('submitOnChange'=>true),
);

$GLOBALS['TL_DCA']['tl_news_archive']['fields']['sitemapPages'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_news_archive']['sitemapPages'],
	'inputType'			=> 'pageTree',
	'exclude'			=> true,
	'eval'				=> array('mandatory'=>true, 'fieldType'=>'checkbox'),
);


class tl_news_archive_sitemap extends Backend
{
	
	public function generateSitemap($dc)
	{
		$objArchive = $this->Database->prepare("SELECT * FROM tl_news_archive WHERE id=?")->limit(1)->execute($dc->id);
		
		if ($objArchive->sitemap)
		{
			$this->import('GoogleSitemap');
			$this->GoogleSitemap->generateSitemap();
		}
	}
}

