<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Table tl_short_urls
 */
$GLOBALS['TL_DCA']['tl_short_urls'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'name,disable' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('name'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name'),
			'format'                  => '%s',
			'label_callback'          => array('tl_short_urls', 'labelCallback'),
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_short_urls']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_short_urls']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_short_urls', 'toggleIcon')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_short_urls']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_short_urls']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{shorturl_legend},name,domain,target,redirect,disable'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_short_urls']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'mandatory'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'load_callback'           => array(array('tl_short_urls', 'loadName')),
			'save_callback'           => array( array('tl_short_urls', 'validateName') ),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'domain' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_short_urls']['domain'],
			'default'                 => '',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_short_urls', 'getDomains'),
			'eval'                    => array('tl_class'=>'w50','submitOnChange'=>true),
			'save_callback'           => array( array('tl_short_urls', 'validateDomain') ),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'redirect' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_short_urls']['redirect'],
			'default'                 => 'permanent',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('permanent', 'temporary'/*, 'alias'*/),
			'reference'               => &$GLOBALS['TL_LANG']['tl_short_urls'],
			'eval'                    => array('tl_class' => 'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'target' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_short_urls']['target'],
			'exclude'                 => true,
            'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'fieldType'=>'radio', 'filesOnly'=>true, 'tl_class'=>'w50 wizard', 'mandatory'=>true),
            'wizard'                  => array( array('tl_short_urls', 'pagePicker') ),
            'inputType'               => 'text',
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'disable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_short_urls']['disable'],
			'exclude'                 => true,
			'filter'                  => true,
			'default'                 => false,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'clr'),
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);

if (interface_exists('Contao\CoreBundle\Picker\DcaPickerProviderInterface'))
{
	$GLOBALS['TL_DCA']['tl_short_urls']['fields']['target']['eval']['dcaPicker'] = true;
	unset($GLOBALS['TL_DCA']['tl_short_urls']['fields']['target']['eval']['fieldType']);
	unset($GLOBALS['TL_DCA']['tl_short_urls']['fields']['target']['eval']['filesOnly']);
	unset($GLOBALS['TL_DCA']['tl_short_urls']['fields']['target']['wizard']);
}


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Fritz Michael Gschwantner <https://github.com/fritzmg>
 */
class tl_short_urls extends Backend
{

	/**
	 * Decodes the name
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 * @return string
	 */
	public function loadName($varValue, DataContainer $dc)
	{
		// check for query parameters
		if (strpos($varValue, '?') !== false)
		{
			$pos = strpos($varValue, '?');
			$url = substr($varValue, 0, $pos);
			$par = substr($varValue, $pos + 1);

			if ($par)
			{
				parse_str($par, $arrParameters);
				return rawurldecode($url) . '?' . http_build_query($arrParameters);
			}
		}

		return rawurldecode($varValue);
	}


	/**
	 * Process and validate the name of a short url
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 * @return string
	 * @throws Exception
	 */
	public function validateName($varValue, DataContainer $dc)
	{
		// check for query parameters
		if (strpos($varValue, '?') !== false)
		{
			$pos = strpos($varValue, '?');
			$url = substr($varValue, 0, $pos);
			$par = substr($varValue, $pos + 1);

			if ($par)
			{
				parse_str($par, $arrParameters);
				return implode('/', array_map('rawurlencode', explode('/', rawurldecode($url)))) . '?' . http_build_query($arrParameters);
			}
		}

		$varValue = rawurldecode($varValue);
		$this->validate($varValue, $dc->activeRecord->domain);
		return implode('/', array_map('rawurlencode', explode('/', $varValue)));
	}


	/**
	 * Process and validate the name of a short url
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 * @return string
	 * @throws Exception
	 */
	public function validateDomain($varValue, DataContainer $dc)
	{
		$this->validate($dc->activeRecord->name, $varValue);
		return $varValue;
	}


	/**
	 * Validate the name of a short url
	 * @param  string $name  
	 * @param  string $domain
	 * @return void
	 */
	private function validate($name, $domain)
	{
		// check if a page exists
		if( !\Config::get('urlSuffix') && ( $objPages = \PageModel::findByAlias( $name ) ) !== null )
		{
			// check if short url has a domain
			if( $domain > 0 )
			{
				$valid = true;
				while( $objPages->next() )
				{
					// load page details
					$objPages->current()->loadDetails();

					// if one of the found pages is within the same domain, do not allow it
					if( $objPages->rootId == $domain )
					{
						$valid = false;
						break;
					}
				}

				if( $valid )
					return;			
			}

			// otherwise throw exception
			throw new Exception(sprintf($GLOBALS['TL_LANG']['tl_short_urls']['pageExists'], $name));
		}
	}


	/**
	 * Generate the page picker link
	 *
	 * @param DataContainer $dc
	 *
	 * @return string
	 */
	public function pagePicker(DataContainer $dc)
	{
		return ' <a href="contao/page.php?do=' . Input::get('do') . '&amp;table=' . $dc->table . '&amp;field=' . $dc->field . '&amp;value=' . str_replace(array('{{link_url::', '}}'), '', $dc->value) . '&amp;switch=1' . '" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']) . '" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':768,\'title\':\'' . specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])) . '\',\'url\':this.href,\'id\':\'' . $dc->field . '\',\'tag\':\'ctrl_'. $dc->field . ((Input::get('act') == 'editAll') ? '_' . $dc->id : '') . '\',\'self\':this});return false">' . Image::getHtml('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
	}


	/**
	 * Return the "toggle visibility" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.$row['disable'];

		if ($row['disable'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['disable'] ? 0 : 1) . '"').'</a> ';
	}


	/**
	 * Disable/enable a short url
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_short_urls']['fields']['disable']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_short_urls']['fields']['disable']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_short_urls SET tstamp=". time() .", disable='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);
	}


	/**
	 * Display list info in the backend
	 *
	 * @param array $arrRow
	 *
	 * @return string
	 */
	public function labelCallback($arrRow)
	{
		// get the target URL
		$targetURL = \ShortURLs::processTarget( $arrRow['target'] );

		// remove current host
		$targetURL = str_replace( \Environment::get('base'), '', $targetURL );

		// check for domain restriction
		$domain = '';
		if( $arrRow['domain'] && ( $objPage = \PageModel::findById( $arrRow['domain'] ) ) !== null )
			$domain = $objPage->dns . '/';

		// generate list record
		return '<div class="tl_content_right"><span style="color:rgb(200,200,200)">[' . ( $arrRow['redirect'] == 'permanent' ? 301 : 302 ) . ']</span></div><div class="tl_content_left">' . $domain . rawurldecode($arrRow['name']) . ' &raquo; ' . rawurldecode($targetURL) . ' </div>';
	}


	/**
	 * Retreives all available domains in this installation (plus empty selection)
	 *
	 * @return array
	 */
	public function getDomains()
	{
		// options array
		$options = array( '' => $GLOBALS['TL_LANG']['tl_short_urls']['noDomain'] );

		// get the root pages and their dns settings
		if( version_compare( VERSION, '3.5', '>=' ) )
		{
			if( ( $objPages = \PageModel::findPublishedRootPages() ) !== null )
				while( $objPages->next() )
					if( $objPages->dns )
						$options[$objPages->id] = $objPages->dns;			
		}
		else
		{
			$t = \PageModel::getTable();
			$arrColumns = array("$t.type=?");

			if (!BE_USER_LOGGED_IN)
			{
				$time = time();
				$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
			}

			$objPages = \PageModel::findBy($arrColumns, 'root');

			if( $objPages !== null )
				while( $objPages->next() )
					if( $objPages->dns )
						$options[$objPages->id] = $objPages->dns;
		}

		// return the options
		return $options;
	}
}
