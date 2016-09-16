<?php
/**
 * WP-Zillow-API (https://www.zillow.com/howto/api/APIOverview.htm)
 *
 * @package WP-Zillow-API
 */

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Check if class exists. */
if ( ! class_exists( 'ZillowAPI' ) ) {

	/**
	 * Zillow API Class.
	 */
	class ZillowAPI {

		/**
		 * Zillow API Key (aka ZWSID).
		 *
		 * @var string
		 */
		static private $zws_id;

		/**
		* Return format. XML or JSON.
		*
		* @var [string
	 	*/
	 	static private $output;

		/**
		 * Zillow BaseAPI Endpoint
		 *
		 * @var string
		 * @access protected
		 */
		protected $base_uri = 'https://www.zillow.com/webservice/';


		/**
		 * __construct function.
		 *
		 * @access public
		 * @param mixed $zws_id ZWSID.
		 * @return void
		 */
		public function __construct( $zws_id, $output = 'json' ) {

			static::$zws_id = $zws_id;
			static::$output = $output;

		}


		/**
		 * Fetch the request from the API.
		 *
		 * @access private
		 * @param mixed $request Request URL.
		 * @return $body Body.
		 */
		private function fetch( $request ) {

			$response = wp_remote_get( $request );
			$code = wp_remote_retrieve_response_code( $response );

			if ( 200 !== $code ) {
				return new WP_Error( 'response-error', sprintf( __( 'Server response code: %d', 'text-domain' ), $code ) );
			}

			$body = wp_remote_retrieve_body( $response );

			return json_decode( $body );

		}

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
		 * @return $response API Response.
		 */
		function get_reviews( $screenname, $email = null, $count = '3', $returnTeamMemberReviews = null ) {


			if ( empty( $screenname ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}


			$request = $this->base_uri . '/ProReviews.htm?zws-id=' . static::$zws_id . '&screenname=' . $screenname . '&output=json';

			return $this->fetch( $request );


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
		function get_rate_summary( $state = null, $callback = '') {

			$request = $this->base_uri . '/GetRateSummary.htm?zws-id=' . static::$zws_id . '&output=json';

			return $this->fetch( $request );

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
		function get_monthly_payments( $price, $down = null, $dollarsdown = null, $zip = null, $output = null, $callback = null ) {

			if ( empty( $price ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}

			$request = $this->base_uri . '/GetMonthlyPayments.htm?zws-id=' . static::$zws_id . '&output=json' . '&price=' . $price;

			return $this->fetch( $request );

		}


		/**
		 * get_deep_search_results function.
		 *
		 * @access public
		 * @param mixed $address
		 * @param mixed $citystatezip
		 * @param mixed $rentzestimate (default: null)
		 * @return void
		 */
		function get_deep_search_results( $address, $citystatezip, $rentzestimate = null ) {

			if ( empty( $address ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}

			$request = $this->base_uri . '/GetMonthlyPayments.htm?zws-id=' . static::$zws_id . '&output=json' . '&address=' . $address . '&citystatezip=' . $citystatezip;

			return $this->fetch( $request );

		}


		/**
		 * get_deep_comps function.
		 *
		 * @access public
		 * @param mixed $zpid
		 * @param string $count (default: '5')
		 * @param bool $rentzestimate (default: false)
		 * @return void
		 */
		function get_deep_comps( $zpid, $count = '5', $rentzestimate = false ) {

			if ( empty( $zpid ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}


			$request = $this->base_uri . '/GetDeepComps.htm?zws-id=' . static::$zws_id . '&zpid=' . $zpid . '&count=' . $count;

			return $this->fetch( $request );

		}


		/**
		 * get_updated_property_details function.
		 *
		 * @access public
		 * @param mixed $zpid
		 * @return void
		 */
		function get_updated_property_details( $zpid ) {

			if ( empty( $zpid ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}


			$request = $this->base_uri . '/GetUpdatedPropertyDetails.htm?zws-id=' . static::$zws_id . '&zpid=' . $zpid;

			return $this->fetch( $request );

		}


		/**
		 * get_search_results function.
		 *
		 * @access public
		 * @param mixed $address
		 * @param mixed $citystatezip
		 * @param bool $rentzestimate (default: false)
		 * @return void
		 */
		function get_search_results( $address, $citystatezip, $rentzestimate = false ) {

			if ( empty( $address ) && empty( $citystatezip ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}

			$request = $this->base_uri . '/GetUpdatedPropertyDetails.htm?zws-id=' . static::$zws_id . '&address=' . $address . '&citystatezip=' . $citystatezip;

			// return $this->fetch( $request );

			$xml = simplexml_load_file(trim($request));

			echo json_encode($xml);

		}


		/**
		 * get_zestimate function.
		 * http://wern-ancheta.com/blog/2014/03/20/getting-started-with-zillow-api/
		 * https://github.com/letsgetrandy/wp-zestimate
		 *
		 * @access public
		 * @param mixed $zpid
		 * @return void
		 */
		function get_zestimate( $zpid ) {

			if ( empty( $zpid ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}


			$request = $this->base_uri . '/GetZestimate.htm?zws-id=' . static::$zws_id . '&zpid=' . $zpid;

			$xml = simplexml_load_file(trim($request));

			echo json_encode($xml);

		}

		function get_chart( $zpid, $unit_type, $width, $height, $chart_duration ) {

		}

		function get_comps( $zpid, $count, $rentzestimate ) {

		}


		/**
		 * Response code message for GetSearchResults.
		 *
		 * @param  [String] $code : Response code to get message from.
		 * @return [String]       : Message corresponding to response code sent in.
		 */
		public function response_code_msg( $code = '' ) {
			switch ( $code ) {
			case 0:
				$msg = __( 'Request successfully processed.', 'text-domain' );
				break;
			case 1:
				$msg = __( 'Service error-there was a server-side error while processing the request.', 'text-domain' );
				break;
			case 2:
				$msg = __( 'The specified ZWSID parameter was invalid or not specified in the request.', 'text-domain' );
				break;
			case 3:
				$msg = __( 'Web services are currently unavailable.', 'text-domain' );
				break;
			case 4:
				$msg = __( 'The API call is currently unavailable.', 'text-domain' );
				break;
			case 500:
				$msg = __( 'Invalid or missing address parameter.', 'text-domain' );
				break;
			case 501:
				$msg = __( 'Invalid or missing city, state, zip parameter.', 'text-domain' );
				break;
			case 502:
				$msg = __( 'No results found.', 'text-domain' );
				break;
			case 503:
				$msg = __( 'Failed to resolve city, state or ZIP code.', 'text-domain' );
				break;
			case 504:
				$msg = __( 'No coverage for specified area.', 'text-domain' );
				break;
			case 505:
				$msg = __( 'Timeout.', 'text-domain' );
				break;
			case 506:
				$msg = __( 'Address string too long.', 'text-domain' );
				break;
			case 507:
				$msg = __( 'No exact match found.', 'text-domain' );
				break;
			default:
				$msg = __( 'Sorry, response code is unknown.' );
				break;
			}
			return $msg;
		}
	}
}
