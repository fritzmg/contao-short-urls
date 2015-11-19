<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['content']['shorturls'] = array
(
	'tables'      => array('tl_short_urls'),
	'icon'        => 'system/modules/short_urls/assets/icon.png',
);


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['initializeSystem'][] = array('ShortURLs','checkForShortURL');
