<?php
/**
 * WP-Zillow-API (https://www.zillow.com/howto/api/APIOverview.htm)
 *
 * @package WP-Zillow-API
 */

/*
* Plugin Name: WP Zillow API
* Plugin URI: https://github.com/wp-api-libraries/wp-zillow-api
* Description: Perform API requests to Zillow in WordPress.
* Author: WP API Libraries
* Version: 1.0.2
* Author URI: https://wp-api-libraries.com
* GitHub Plugin URI: https://github.com/wp-api-libraries/wp-zillow-api
* GitHub Branch: master
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
		 * @param mixed  $state (default: null) State.
		 * @param string $callback (default: '') Callback.
		 * @return void
		 */
		function get_rate_summary( $state = null, $callback = '' ) {

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
		 * Get Deep Search Results.
		 *
		 * @access public
		 * @param mixed $address Address.
		 * @param mixed $citystatezip City/State/Zip.
		 * @param mixed $rentzestimate (default: null) Rent Zestimate.
		 * @return void
		 */
		function get_deep_search_results( $address, $citystatezip, $rentzestimate = null ) {

			if ( empty( $address ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}

			$request = $this->base_uri . '/GetMonthlyPayments.htm?zws-id=' . static::$zws_id . '&address=' . $address . '&citystatezip=' . $citystatezip;

			$xml = simplexml_load_file( $request );

			$json = wp_json_encode( $xml );

			$deep_results = json_decode( $json, true );

			return $deep_results;

		}


		/**
		 * Get Deep Comps.
		 *
		 * @access public
		 * @param mixed  $zpid ZPID.
		 * @param string $count (default: '5') Count.
		 * @param bool   $rentzestimate (default: false) Rent Zestimate.
		 * @return void
		 */
		function get_deep_comps( $zpid, $count = '5', $rentzestimate = false ) {

			if ( empty( $zpid ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}

			$request = $this->base_uri . '/GetDeepComps.htm?zws-id=' . static::$zws_id . '&zpid=' . $zpid . '&count=' . $count;

			$xml = simplexml_load_file( $request );

			$json = wp_json_encode( $xml );

			$deep_comps = json_decode( $json, true );

			return $deep_comps;

		}


		/**
		 * Get Updated Property Details.
		 *
		 * @access public
		 * @param mixed $zpid ZPID.
		 * @return void
		 */
		function get_updated_property_details( $zpid ) {

			if ( empty( $zpid ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}

			$request = $this->base_uri . '/GetUpdatedPropertyDetails.htm?zws-id=' . static::$zws_id . '&zpid=' . $zpid;

			$xml = simplexml_load_file( $request );

			$json = wp_json_encode( $xml );

			$prop_details = json_decode( $json, true );

			return $prop_details;

		}

		/**
		 * Get Search Results.
		 *
		 * @access public
		 * @param mixed $address Address.
		 * @param mixed $citystatezip City/State/Zip.
		 * @param bool  $rentzestimate (default: false) Rent Zestimate.
		 * @return void
		 */
		function get_search_results( $address, $citystatezip, $rentzestimate = false ) {

			if ( empty( $address ) && empty( $citystatezip ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}

			$request = $this->base_uri . '/GetUpdatedPropertyDetails.htm?zws-id=' . static::$zws_id . '&address=' . $address . '&citystatezip=' . $citystatezip;

			$xml = simplexml_load_file( $request );

			$json = wp_json_encode( $xml );

			$search_results = json_decode( $json, true );

			return $search_results;

		}

		/**
		 * Get Zillow Zestimate.
		 * http://wern-ancheta.com/blog/2014/03/20/getting-started-with-zillow-api/
		 * https://github.com/letsgetrandy/wp-zestimate
		 *
		 * @access public
		 * @param mixed $zpid ZPID.
		 * @return void
		 */
		function get_zestimate( $zpid ) {

			if ( empty( $zpid ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}

			$request = $this->base_uri . '/GetZestimate.htm?zws-id=' . static::$zws_id . '&zpid=' . $zpid;

			$xml = simplexml_load_file( $request );

			$json = wp_json_encode( $xml );

			$zestimate = json_decode( $json, true );

			return $zestimate;

		}

		/**
		 * Get Chart.
		 *
		 * @access public
		 * @param mixed  $zpid ZPID.
		 * @param mixed  $unit_type Unit Type.
		 * @param string $width (default: '600') Width.
		 * @param string $height (default: '300') Height.
		 * @param string $chart_duration (default: '1year') Chart Duration.
		 * @return void
		 */
		function get_chart( $zpid, $unit_type, $width = '600', $height = '300', $chart_duration = '1year' ) {

			if ( empty( $zpid ) && empty( $unit_type ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}

			$request = $this->base_uri . '/GetChart.htm?zws-id=' . static::$zws_id . '&unit-type=' . $unit_type . '&zpid=' . $zpid;

			$xml = simplexml_load_file( $request );

			$json = wp_json_encode( $xml );

			$chart = json_decode( $json, true );

			return $chart;

		}

		/**
		 * Get Comps.
		 *
		 * @access public
		 * @param mixed $zpid ZPID.
		 * @param mixed $count Count.
		 * @param bool  $rentzestimate (default: false) Rent Zestimate.
		 * @return void
		 */
		function get_comps( $zpid, $count, $rentzestimate = false ) {

			if ( empty( $zpid ) && empty( $count ) ) {
				return new WP_Error( 'required-fields', __( 'Required fields are empty.', 'text-domain' ) );
			}

			$request = $this->base_uri . '/GetComps.htm?zws-id=' . static::$zws_id . '&zpid=' . $zpid . '&count=' . $count;

			$xml = simplexml_load_file( $request );

			$json = wp_json_encode( $xml );

			$comps = json_decode( $json, true );

			return $comps;

		}

		/**
		 * Get the ZPID from a Zillow Property Url.
		 *
		 * @access public
		 * @param mixed $url URL.
		 * @return void
		 */
		function get_zpid_from_url( $url ) {

			if ( empty( $url ) ) {
				return new WP_Error( 'required-fields', __( 'Please provide a URL.', 'text-domain' ) );
			}

			preg_match( '!\d+_zpid!', $url, $matches );

			$final_match = preg_replace( '/_zpid/', '', $matches );

			return $final_match['0'];
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
