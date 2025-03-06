<?php
/**
 * @brief		fetchRadioStats Task
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community
 * @subpackage	gjradiostats
 * @since		22 Feb 2025
 */

namespace IPS\gjradiostats\tasks;

use IPS\Task;
use IPS\Task\Exception as TaskException;
use IPS\Settings;
use IPS\Log;
use IPS\Data\Store;
use IPS\Http\Url;
use function defined;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * fetchRadioStats Task
 */
class fetchRadioStats extends Task
{
	/**
	 * Execute
	 *
	 * If ran successfully, should return anything worth logging. Only log something
	 * worth mentioning (don't log "task ran successfully"). Return NULL (actual NULL, not '' or 0) to not log (which will be most cases).
	 * If an error occurs which means the task could not finish running, throw an \IPS\Task\Exception - do not log an error as a normal log.
	 * Tasks should execute within the time of a normal HTTP request.
	 *
	 * @return	mixed	Message to log or NULL
	 * @throws	TaskException
	 */
	public function execute() : mixed
	{
		// Get SHOUTcast settings
		$ip = Settings::i()->gjradiostats_ip;
		$port = Settings::i()->gjradiostats_port;
		$debug = Settings::i()->gjradiostats_debug;

		// Ensure settings are configured
		if (!$ip || !$port) {
			if ($debug) {
				Log::log("SHOUTcast fetch failed: IP & Port not configured.", 'fetchRadioStats');
			}
			return "SHOUTcast fetch failed: IP & Port not configured.";
		}
		try {
			if ($debug) {
				Log::log("SHOUTcast fetch DEBUG: Fetching http://{$ip}:{$port}/stats?json=1", 'fetchRadioStats');
			}
			$url = Url::external("http://{$ip}:{$port}/stats?json=1");
			$response = $url->request()->get()->decodeJson();

			if (!empty($response)) {
				// For debugging purposes, we'll generate a GUID
				$response['random'] = uniqid(rand());

				Store::i()->shoutcastStats = $response;
				if ($debug) {
					Log::log("SHOUTcast fetched: " . json_encode($response, JSON_PRETTY_PRINT), 'fetchRadioStats');
				}
				return NULL;
			} else {
				Store::i()->shoutcastStats = array(
					'servertitle' => 'Offline',
					'streamstatus' => 0,
					'random' => uniqid(rand())
				);
				if ($debug) {
					Log::log("SHOUTcast fetch failed: Empty Response, using {$ip}:{$port}", 'fetchRadioStats');
				}
				return "SHOUTcast fetch failed: Empty Response, using {$ip}:{$port}";
			}
		} catch (\Exception $e) {
			Store::i()->shoutcastStats = array(
				'servertitle' => 'Offline',
				'streamstatus' => 0,
				'random' => uniqid(rand())
			);
			Log::log("SHOUTcast fetch failed: " . $e->getMessage(), 'fetchRadioStats');
			return "SHOUTcast fetch failed - caught exception.";;
		}
	}
	
	/**
	 * Cleanup
	 *
	 * If your task takes longer than 15 minutes to run, this method
	 * will be called before execute(). Use it to clean up anything which
	 * may not have been done
	 *
	 * @return	void
	 */
	public function cleanup() : void
	{
		
	}
}