<?php


namespace IPS\gjradiostats\modules\front\gjradiostats;

use IPS\Dispatcher\Controller;
use IPS\Data\Store;
use IPS\Output;
use function defined;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * fetchRadioStats
 */
class fetchRadioStats extends Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute() : void
	{
		
		parent::execute();

        // Get cached SHOUTcast data
        $shoutcast = Store::i()->shoutcastStats ?? [];

        // Return JSON response
        if (!empty($shoutcast)) {
            Output::i()->json($shoutcast);
        } else {
            Output::i()->json(['error' => 'No data available'], 404);
        }
	}

	/**
	 * ...
	 *
	 * @return	void
	 */
	protected function manage() : void
	{
		// This is the default method if no 'do' parameter is specified
	}
	
	// Create new methods with the same name as the 'do' parameter which should execute it
}