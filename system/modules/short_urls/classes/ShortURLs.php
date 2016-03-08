<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Helper class for Short URLs
 *
 * @author Fritz Michael Gschwantner <https://github.com/fritzmg>
 */
class ShortURLs
{

	public function __construct()
	{
		// initialize required singletons in the right order
		\FrontendUser::getInstance();
		\Database::getInstance();
	}


	public static function processTarget( $target )
	{
		// replace insert tags for Contao 3.5.7 and up
		if( version_compare( VERSION . '.' . BUILD, '3.5.7', '>=' ) )
			$target = \Controller::replaceInsertTags( $target );

		// check for insert tag
		if( stripos( $target, '{{link_url::' ) === 0 )
		{
			// get the page id
			$pageId = substr( $target, 12, strpos( $target, '}}') - 12 ); 

			// get the page
			if( ( $objPage = \PageModel::findPublishedByIdOrAlias( $pageId ) ) === null )
				return;

			// load details of the page
			$objPage->current()->loadDetails();

			// generate the URL
			$target = \Controller::generateFrontendUrl( $objPage->row(), null, $objPage->rootLanguage, true ) . substr( $target, strpos( $target, '}}' ) + 2 );
		}

		// return processed target
		return $target;	
	}

	public function checkForShortURL()
	{
		// only do something in the frontend
		if( TL_MODE != 'FE' )
			return;

		// check if we have a Short URL
		if( ( $objShortURL = \ShortURLsModel::findActiveByName( \Environment::get('request') ) ) === null )
			return;

		// check if there is a target set
		if( !$objShortURL->target )
			return;

		// check for domain restriction
		if( $objShortURL->domain )
			if( ( $objDomain = \PageModel::findById( $objShortURL->domain ) ) !== null )
				if( strcasecmp( $objDomain->dns, \Environment::get('host') ) != 0 )
					return;

		// build redirect URL
		$url = self::processTarget( $objShortURL->target );

		// prevent infinite redirects
		if( $url == \Environment::get('base') . \Environment::get('request') )
			return;

		// execute redirect
		\Controller::redirect( $url, $objShortURL->redirect == 'permanent' ? 301 : 302 );
	}

}
