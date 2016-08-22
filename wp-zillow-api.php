<?php
/**
* WP-Zillow-API
*
* @package WP-Zillow-API
*/


/**
 * WP_Zillow_API class.
 */
class WP_Zillow_API{


	/**
	 * Get Zillow Reviews (https://www.zillow.com/howto/api/ReviewsAPI.htm)
	 *
	 * @access public
	 * @param mixed $zws_id The Zillow Web Service Identifier. Required.
	 * @param mixed $screenname The screenname of the user whose reviews you would like to return. Required
	 * @param mixed $email The email of the user whose reviews you would like to return.
	 * @param mixed $count The count of reviews you would like to return. If omitted, up to 3 are returned. 10 is maximum.
	 * @param mixed $output The type of output desired. Specify 'xml' for XML output and 'json' for JSON output. If omitted, 'xml' is assumed.
	 * @param mixed $return_team_member_reviews If value = 'true', API returns reviews written directly for the account requested, in addition to any reviews attributed to this account from its team members if applicable. If omitted, returned reviews reviews defaults to just those written directly for the account requested.
	 * @return void
	 */
	function get_reviews( $zws_id, $screenname, $email, $count, $output, $return_team_member_reviews ) {

	}

}