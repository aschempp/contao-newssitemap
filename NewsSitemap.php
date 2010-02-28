<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Andreas Schempp 2010
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
 */


class NewsSitemap extends Backend
{
	
	protected function getNewsOptions($arrPages, $domain)
	{
		$arrCompleted = array();
		$objArchives = $this->Database->execute("SELECT a.*, n.tstamp AS lastmod, n.headline FROM tl_news_archive a LEFT OUTER JOIN tl_news n ON a.id=n.pid WHERE sitemap='1' ORDER BY n.tstamp DESC");
		
		while( $objArchives->next() )
		{
			if (in_array($objArchives->id, $arrCompleted))
				continue;
			
			$arrCompleted[] = $objArchives->id;
			
			$arrNewsPages = deserialize($objArchives->sitemapPages);
			
			if (is_array($arrNewsPages) && count($arrNewsPages))
			{
				$objPages = $this->Database->execute("SELECT * FROM tl_page WHERE id IN (" . implode(',', $arrNewsPages) . ") AND published=1");
				
				while( $objPages->next() )
				{
					$strUrl = $domain . $this->generateFrontendUrl($objPages->row());
					
					if ($arrPages[$strUrl]['sitemap_lastmod'] < $objArchives->lastmod)
					{
						$arrPages[$strUrl]['sitemap_lastmod'] = $objArchives->lastmod;
					}
				}
			}
			
			
			if ($objArchives->jumpTo)
			{
				$objJumpTo = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")->limit(1)->execute($objArchives->jumpTo);
				
				if ($objJumpTo->numRows)
				{
					$objNews = $this->Database->prepare("SELECT * FROM tl_news WHERE pid=? AND published='1' AND (start=0 OR start>?) AND (stop=0 OR stop>?)")->execute($objArchives->id, time(), time());
					
					while( $objNews->next() )
					{
						$strUrl = $domain . $this->generateFrontendUrl($objJumpTo->row(), '/items/' . $objNews->alias);
					
						$arrPages[$strUrl]['sitemap_lastmod'] = $objNews->tstamp;
						$arrPages[$strUrl]['sitemap_changefreq'] = $objJumpTo->sitemap_changefreq;
						$arrPages[$strUrl]['sitemap_priority'] = $objJumpTo->sitemap_priority;
					}
				}
			}
			
		}
		
		return $arrPages;
	}
}

