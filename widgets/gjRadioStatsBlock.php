<?php
/**
 * @brief		gjRadioStatsBlock Widget
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community
 * @subpackage	gjradiostats
 * @since		05 Mar 2025
 */

namespace IPS\gjradiostats\widgets;

use IPS\Data\Store;
use function defined;


/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * gjRadioStatsBlock Widget
 */
class _gjRadioStatsBlock extends \IPS\Widget
{
	/**
	 * @brief	Widget Key
	 */
	public $key = 'gjRadioStatsBlock';
	
	/**
	 * @brief	App
	 */
	public $app = 'gjradiostats';
		
	/**
	 * @brief	Plugin
	 */
	public $plugin = '';
	
	/**
	 * Initialise this widget
	 *
	 * @return void
	 */ 
	public function init()
	{
		// Use this to perform any set up and to assign a template that is not in the following format:
		// $this->template( array( \IPS\Theme::i()->getTemplate( 'widgets', $this->app, 'front' ), $this->key ) );
		// If you are creating a plugin, uncomment this line:
		// $this->template( array( \IPS\Theme::i()->getTemplate( 'plugins', 'core', 'global' ), $this->key ) );
		// And then create your template at located at plugins/<your plugin>/dev/html/gjRadioStatsBlock.phtml
		
		
		parent::init();
	}
	
	/**
	 * Specify widget configuration
	 *
	 * @param	null|\IPS\Helpers\Form	$form	Form object
	 * @return	null|\IPS\Helpers\Form
	 */
	public function configuration( &$form=null )
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
 	public function preConfig( $values )
 	{
 		return $values;
 	}

	/**
	 * Render a widget
	 *
	 * @return	string
	 */
	public function render()
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