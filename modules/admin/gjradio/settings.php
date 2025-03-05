<?php


namespace IPS\gjradiostats\modules\admin\gjradio;

use IPS\Dispatcher;
use IPS\Dispatcher\Controller;
use IPS\Settings as SettingsClass;
use IPS\Helpers\Form;
use IPS\Helpers\Form\Number;
use IPS\Helpers\Form\Text;
use IPS\Helpers\Form\Select;
use IPS\Http\Url;
use IPS\Member;
use IPS\Output;
use IPS\Member\Group;
use function defined;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * settings
 */
class _settings extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'settings_manage' );
		parent::execute();
	}

	/**
	 * ...
	 *
	 * @return	void
	 */
	protected function manage()
	{
		$form = new Form;
		$groups = Group::groups();

		$form->addHeader('__app_gjradiostats');
		$form->AddSidebar('
			<h2 class="ipsFieldRow_section">Support</h2>
			<p>If you have any issues, please open an issue on GitHub, I will try my best to respond.</p>
			<ul class="ipsList_reset ipsPad_half ">
				<li class="ipsType_center"><a href="https://garethjohnstone.co.uk" target="_blank" rel="noreferrer" class="ipsButton  ipsButton_small ipsButton_light">Website</a> <a href="https://github.com/gaza1994/IPS4-SHOUTcast-Plugin" target="_blank" rel="noreferrer" class="ipsButton ipsButton_small ipsButton_light">GitHub</a></li>
			</ul>');

		// Add a text input field for Title
		$form->add( new Text( 'gjradiostats_title', SettingsClass::i()->gjradiostats_title ?: '' ) );

		// Add a text input field for IP
		$form->add( new Text( 'gjradiostats_ip', SettingsClass::i()->gjradiostats_ip ?: '' ) );

		// Add a text input field for Port
		$form->add( new Number( 'gjradiostats_port', SettingsClass::i()->gjradiostats_port ?: '' ) );

		// Add a select input field for Visibility
		$form->add( new Select( 'gjradiostats_visibility',SettingsClass::i()->gjradiostats_visibility=='*' ? '*' : explode( ',', SettingsClass::i()->gjradiostats_visibility ), TRUE, array( 'options' => $groups, 'parse' => 'normal', 'multiple' => true, 'unlimited' => '*', 'unlimitedLang' => 'everyone' ), NULL, NULL, NULL, 'gjradiostats_visibility' ) );

		// Add a number input field for Update Polling
		$form->add( new Number( 'gjradiostats_updatepolling', SettingsClass::i()->gjradiostats_updatepolling ?: 60 ) );

		// Setting for autoplay
		$form->add( new Select( 'gjradiostats_autoplay', SettingsClass::i()->gjradiostats_autoplay ?: false, TRUE, array( 'options' => array( false => 'Off', true => 'On' ) ) ) );

		// Setting for player style
		$form->add( new Select( 'gjradiostats_playerstyle', SettingsClass::i()->gjradiostats_playerstyle ?: false, TRUE, array( 'options' => array( 1 => 'Fixed', 2 => 'Inline' ) ) ) );

		// Add a text input field for player width
		$form->add( new Text( 'gjradiostats_playerwidth', SettingsClass::i()->gjradiostats_playerwidth ?: '' ) );

		// Add debug selectbox On or Off field
		$form->add( new Select( 'gjradiostats_debug', SettingsClass::i()->gjradiostats_debug ?: 0, TRUE, array( 'options' => array( 0 => 'Off', 1 => 'On' ) ) ) );


		/* Save */
		if ($values = $form->values(TRUE)) {
			$form->saveAsSettings($values);
			Output::i()->redirect(Url::internal('app=gjradiostats&module=gjradio&controller=settings'), 'saved');
		}

		/* Output */
		Output::i()->breadcrumb[] = array(Url::internal("app=gjradiostats&module=gjradio&controller=settings"), '__app_gjradiostats');
		Output::i()->title = Member::loggedIn()->language()->addToStack('__app_gjradiostats');
		Output::i()->output .= $form;
	}
	
	// Create new methods with the same name as the 'do' parameter which should execute it
}