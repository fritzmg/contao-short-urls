<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Model class for ShortURLs
 *
 * @author Fritz Michael Gschwantner <https://github.com/fritzmg>
 */
class ShortURLsModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_short_urls';


	/**
	 * Find active Short URLs by their name
	 *
	 * @param mixed $name       The name of the short URL
	 * @param array $arrOptions An optional options array
	 *
	 * @return static Model|Collection|null
	 */
	public static function findActiveByName($name, array $arrOptions=array())
	{
		$t = static::$strTable;
		return static::findBy(array("$t.name = ? AND $t.disable = ''"), array( $name ), $arrOptions);
	}
}
