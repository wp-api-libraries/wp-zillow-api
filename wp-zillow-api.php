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
	 * @param mixed $screenname The screenname of the user whose reviews you would like to return. Required.
	 * @param mixed $email The email of the user whose reviews you would like to return.
	 * @param mixed $count The count of reviews you would like to return. If omitted, up to 3 are returned. 10 is maximum.
	 * @param mixed $output The type of output desired. Specify 'xml' for XML output and 'json' for JSON output. If omitted, 'xml' is assumed.
	 * @param mixed $return_team_member_reviews If value = 'true', API returns reviews written directly for the account requested, in addition to any reviews attributed to this account from its team members if applicable. If omitted, returned reviews reviews defaults to just those written directly for the account requested.
	 * @return void
	 */
	function get_reviews( $zws_id, $screenname, $email, $count, $output, $return_team_member_reviews ) {

	}


	/**
	 * Get Mortage Rate Summary (https://www.zillow.com/howto/api/GetRateSummary.htm)
	 *
	 * @access public
	 * @param mixed $zws_id The Zillow Web Service Identifier. Required.
	 * @param mixed $state The state for which to return average mortgage rates. Two-letter state abbreviations should be used (AK, AL, etc). If omitted, national average mortgage rates are returned.
	 * @param mixed $output The type of output desired. Specify 'xml' for XML output and 'json' for JSON output. If omitted, 'xml' is assumed.
	 * @param mixed $callback The name of the JavaScript callback function used to process the returned JSON data. If specified, the returned JSON will be wrapped in a function call with the specified function name. This parameter is intended for use with dynamic script tags. The callback function is only used for JSON output.
	 * @return void
	 */
	function get_rate_summary( $zws_id, $state, $output, $callback ) {

	}


	/**
	 * Get Monthly Payments (https://www.zillow.com/howto/api/GetMonthlyPayments.htm)
	 *
	 * @access public
	 * @param mixed $zws_id The Zillow Web Service Identifier. Required.
	 * @param mixed $price The price of the property for which monthly payment data will be calculated. Required.
	 * @param mixed $down The percentage of the total property price that will be placed as a down payment. If omitted, a 20% down payment is assumed. If the down payment is less than 20%, a monthly private mortgage insurance amount is specified for each returned loan type.
	 * @param mixed $dollarsdown The dollar amount that will be placed as a down payment. This amount will be used for the down payment if the 'down' parameter is omitted. If the down payment is less than 20% of the purchase price, a monthly private mortgage insurance amount is specified for each returned loan type.
	 * @param mixed $zip The ZIP code in which the property is located. If omitted, monthly property tax and hazard insurance data will not be returned.
	 * @param mixed $output The type of output desired. Specify 'xml' for XML output and 'json' for JSON output. If omitted, 'xml' is assumed.
	 * @param mixed $callback The name of the JavaScript callback function used to process the returned JSON data. If specified, the returned JSON will be wrapped in a function call with the specified function name. This parameter is intended for use with dynamic script tags. The callback function is only used for JSON output.
	 * @return void
	 */
	function get_monthly_payments( $zws_id, $price, $down, $dollarsdown, $zip, $output, $callback ) {

	}

}