<?php
/**
 * @brief		gjRadioStatsBanner Widget
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community
 * @subpackage	gjradiostats
 * @since		22 Feb 2025
 */

namespace IPS\gjradiostats\widgets;

use IPS\Helpers\Form;
use IPS\Data\Store;
use IPS\Theme;
use function defined;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * gjRadioStatsBanner Widget
 */
class gjRadioStatsBanner extends \IPS\Widget
{
	/**
	 * @brief	Widget Key
	 */
	public string $key = 'gjRadioStatsBanner';
	
	/**
	 * @brief	App
	 */
	public string $app = 'gjradiostats';
	
	/**
	 * Initialise this widget
	 *
	 * @return void
	 */ 
	public function init() : void
	{
		// Use this to perform any set up and to assign a template that is not in the following format:
		//$this->template( array( Theme::i()->getTemplate( 'widgets', $this->app, 'front' ), $this->key ) );
		// Note: parent::init() will set a default template, so make sure your template is defined after the parent is called.
		parent::init();
    }
	
	/**
	 * Specify widget configuration
	 *
	 * @param	null|Form	$form	Form object
	 * @return	Form
	 */
	public function configuration( Form &$form=null ): Form
	{
 		$form = parent::configuration( $form );

 		// $form->add( new \IPS\Helpers\Form\XXXX( .... ) );
 		return $form;
 	} 
 	
 	 /**
 	 * Ran before saving widget configuration
 	 *
 	 * @param	array	$values	Values from form
 	 * @return	array
 	 */
 	public function preConfig( array $values ): array
 	{
 		return $values;
 	}

	/**
	 * Render a widget
	 *
	 * @return	string
	 */
	public function render(): string
	{
      try {
		// fetch the SHOUTcast data from cache
		$shoutcast = Store::i()->shoutcastStats;
		if (empty($shoutcast)) {
			return "";
		}
		// Map the data to the template

		return $this->output($shoutcast);
		// Use $this->output( $foo, $bar ); to return a string generated by the template set in init() or manually added via $widget->template( $callback );
		// Note you MUST route output through $this->output() rather than calling \IPS\Theme::i()->getTemplate() because of the way widgets are cached
	  } catch (\Exception $e) {
		return $this->output($shoutcast);
	  }
    }
}