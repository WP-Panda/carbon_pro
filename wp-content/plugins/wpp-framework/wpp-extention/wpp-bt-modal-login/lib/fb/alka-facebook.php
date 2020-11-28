<?php
	/**
	 * Plugin Name:       AWPP_Fl Facebook
	 * Description:       Login and Register your users using Facebook's API
	 * Version:           1.0.0
	 * Author:            AWPP_Flweb
	 * Author URI:        http://wpp_alf-web.com
	 * Text Domain:       wpp_alfweb
	 * License:           GPL-2.0+
	 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
	 * GitHub Plugin URI: https://github.com/2Fwebd/wpp_alf-facebook
	 */
	
	/*
	 * Import the Facebook SDK and load all the classes
	 */
	require_once 'facebook-sdk/autoload.php';
	
	/*
	 * Classes required to call the Facebook API
	 * They will be used by our class
	 */
	
	use Facebook\Facebook;
	use Facebook\Exceptions\FacebookSDKException;
	use Facebook\Exceptions\FacebookResponseException;
	
	/**
	 * Class AWPP_FlFacebook
	 */
	class AWPP_FlFacebook {
		
		/**
		 * Facebook APP ID
		 *
		 * @var string
		 */
		private $app_id = '629132077460168';
		
		/**
		 * Facebook APP Secret
		 *
		 * @var string
		 */
		private $app_secret = '813e08e712a366feeface33ad63a4aee';
		
		/**
		 * Callback URL used by the API
		 *
		 * @var string
		 */
		private $callback_url = 'https://beckertime.wpadvanceddevelopment.com?ac=wpp-fb-l';
		
		/**
		 * Access token from Facebook
		 *
		 * @var string
		 */
		private $access_token;
		
		/**
		 * Where we redirect our user after the process
		 *
		 * @var string
		 */
		private $redirect_url;
		
		/**
		 * User details from the API
		 */
		private $facebook_details;
		
		/**
		 * AWPP_FlFacebook constructor.
		 */
		public function __construct() {
			
			// We register our shortcode
			add_shortcode( 'wpp_alf_facebook', array ( $this, 'renderShortcode' ) );
			
			// Callback URL
			add_action( 'wp_ajax_wpp_alf_facebook', array ( $this, 'apiCallback' ) );
			add_action( 'wp_ajax_nopriv_wpp_alf_facebook', array ( $this, 'apiCallback' ) );
			
		}
		
		/**
		 * Render the shortcode [wpp_alf_facebook/]
		 *
		 * It displays our Login / Register button
		 */
		public function renderShortcode() {
			
			// Start the session
			if ( ! session_id() ) {
				session_start();
			}
			
			// No need for the button is the user is already logged
			if ( is_user_logged_in() ) {
				return;
			}
			
			// We save the URL for the redirection:
			if ( ! isset( $_SESSION[ 'wpp_alf_facebook_url' ] ) ) {
				$_SESSION[ 'wpp_alf_facebook_url' ] = 'https://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
			}
			
			// Different labels according to whether the user is allowed to register or not
			if ( get_option( 'users_can_register' ) ) {
				$button_label = __( 'Login or Register with Facebook', 'wpp_alfweb' );
			} else {
				$button_label = __( 'Login with Facebook', 'wpp_alfweb' );
			}
			
			// HTML markup
			$html = '<div id="wpp_alf-facebook-wrapper">';
			
			// Messages
			if ( isset( $_SESSION[ 'wpp_alf_facebook_message' ] ) ) {
				$message = $_SESSION[ 'wpp_alf_facebook_message' ];
				$html .= '<div id="wpp_alf-facebook-message" class="alert alert-danger">' . $message . '</div>';
				// We remove them from the session
				unset( $_SESSION[ 'wpp_alf_facebook_message' ] );
			}
			
			// Button
			$html .= '<a href="' . $this->getLoginUrl(
				) . '" class="btn btn-fcb btn-block text-uppercase" id="wpp_alf-facebook-button">' . $button_label . '<i class="fa fa-facebook"></i></a>';
			
			$html .= '</div>';
			
			// Write it down
			return $html;
			
		}
		
		/**
		 * @return Facebook
		 * @throws FacebookSDKException
		 */
		private function initApi() {
			
			$facebook = new Facebook(
				[
					'app_id'                  => $this->app_id,
					'app_secret'              => $this->app_secret,
					'default_graph_version'   => 'v2.2',
					#'persistent_data_handler' => 'session'
				]
			);
			
			return $facebook;
			
		}
		
		/**
		 * @return mixed|string
		 * @throws FacebookSDKException
		 */
		private function getLoginUrl() {
			
			if ( ! session_id() ) {
				session_start();
			}
			
			$fb = $this->initApi();
			
			$helper = $fb->getRedirectLoginHelper();
			
			// Optional permissions
			$permissions = [ 'email' ];
			
			$url = $helper->getLoginUrl( $this->callback_url, $permissions );
			
			return esc_url( $url );
			
		}
		
		/**
		 * @throws FacebookSDKException
		 */
		public function apiCallback() {
			
			if ( ! session_id() ) {
				session_start();
			}
			
			
			
			$fb = new Facebook(
				[
					'app_id'                  => $this->app_id,
					'app_secret'              => $this->app_secret,
					'default_graph_version'   => 'v2.2',
					#'persistent_data_handler' => 'session'
				]
			);
			
			$helper = $fb->getRedirectLoginHelper();
			
			try {
				$accessToken = $helper->getAccessToken();
			} catch(FacebookResponseException $e) {
				// When Graph returns an error
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
			} catch(FacebookSDKException $e) {
				// When validation fails or other local issues
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}
			
			if (! isset($accessToken)) {
				if ($helper->getError()) {
					header('HTTP/1.0 401 Unauthorized');
					echo "Error: " . $helper->getError() . "\n";
					echo "Error Code: " . $helper->getErrorCode() . "\n";
					echo "Error Reason: " . $helper->getErrorReason() . "\n";
					echo "Error Description: " . $helper->getErrorDescription() . "\n";
				} else {
					header('HTTP/1.0 400 Bad Request');
					echo 'Bad request';
				}
				exit;
			}
			
			// Logged in
			echo '<h3>Access Token</h3>';
			var_dump($accessToken->getValue());
			
			// The OAuth 2.0 client handler helps us manage access tokens
			$oAuth2Client = $fb->getOAuth2Client();
			
			// Get the access token metadata from /debug_token
			$tokenMetadata = $oAuth2Client->debugToken($accessToken);
			echo '<h3>Metadata</h3>';
			var_dump($tokenMetadata);
			
			// Validation (these will throw FacebookSDKException's when they fail)
			$tokenMetadata->validateAppId($this->app_id); // Replace {app-id} with your app id
			// If you know the user ID this access token belongs to, you can validate it here
			//$tokenMetadata->validateUserId('123');
			$tokenMetadata->validateExpiration();
			
			if (! $accessToken->isLongLived()) {
				// Exchanges a short-lived access token for a long-lived one
				try {
					$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
				} catch (FacebookSDKException $e) {
					echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
					exit;
				}
				
				echo '<h3>Long-lived</h3>';
				var_dump($accessToken->getValue());
			}
			
			$_SESSION['fb_access_token'] = (string) $accessToken;
			
			// Set the Redirect URL:
			#$this->redirect_url = ( isset( $_SESSION[ 'wpp_alf_facebook_url' ] ) ) ? $_SESSION[ 'wpp_alf_facebook_url' ] : home_url();
			#$helper = $this->getLoginUrl();
			// We start the connection
			#$fb = $this->initApi();
			/*
			// We save the token in our instance
			$this->access_token = $this->getToken( $fb );
			wpp_d_log('token');
			wpp_d_log($this->getToken( $fb ));
			wpp_d_log('----------------------------');
			wpp_d_log('----------------------------');
			wpp_d_log('----------------------------');
			wpp_d_log('----------------------------');
			wpp_d_log('----------------------------');
			
			// We get the user details
			$this->facebook_details = $this->getUserDetails( $fb );
			
			wpp_d_log('detals');
			wpp_d_log($this->getUserDetails( $fb ));
			wpp_d_log('----------------------------');
			wpp_d_log('----------------------------');
			wpp_d_log('----------------------------');
			wpp_d_log('----------------------------');
			wpp_d_log('----------------------------');
			
			// We first try to login the user
			#$this->loginUser();
			
			// Otherwise, we create a new account
			#$this->createUser();
			
			// Redirect the user
			header( "Location: " . $this->redirect_url, true );
			die();*/
			
		}
		
		/**
		 * Get a TOKEN from the Facebook API
		 * Or redirect back if there is an error
		 *
		 * @param $fb Facebook
		 *
		 * @return string - The Token
		 */
		private function getToken( $fb ) {
			
			// Assign the Session variable for Facebook
			$_SESSION[ 'FBRLH_state' ] = $_GET[ 'state' ];
			
			// Load the Facebook SDK helper
			$helper = $fb->getRedirectLoginHelper();
			
			// Try to get an access token
			try {
				$accessToken = $helper->getAccessToken();
			} // When Graph returns an error
			catch ( FacebookResponseException $e ) {
				$error = __( 'Graph returned an error: ', 'wpp_alfweb' ) . $e->getMessage();
				$message = array (
					'type'    => 'error',
					'content' => $error
				);
			} // When validation fails or other local issues
			catch ( FacebookSDKException $e ) {
				$error = __( 'Facebook SDK returned an error: ', 'wpp_alfweb' ) . $e->getMessage();
				$message = array (
					'type'    => 'error',
					'content' => $error
				);
			}
			
			// If we don't got a token, it means we had an error
			if ( ! isset( $accessToken ) ) {
				// Report our errors
				$_SESSION[ 'wpp_alf_facebook_message' ] = $message;
				// Redirect
				header( "Location: " . $this->redirect_url, true );
				die();
			}
			/*wpp_d_log( '$accessToken->getValue()' );
			wpp_d_log( '$accessToken->getValue()' );
			wpp_d_log( '$accessToken->getValue()' );
			wpp_d_log( $accessToken->getValue() );
			wpp_d_log( '$accessToken->getValue()' );
			wpp_d_log( '$accessToken->getValue()' );
			wpp_d_log( '$accessToken->getValue()' );*/
			
			return $accessToken->getValue();
			
		}
		
		/**
		 * Get user details through the Facebook API
		 *
		 * @link https://developers.facebook.com/docs/facebook-login/permissions#reference-public_profile
		 *
		 * @param $fb Facebook
		 *
		 * @return \Facebook\GraphNodes\GraphUser
		 */
		private function getUserDetails( $fb ) {
			
			try {
				$response = $fb->get( '/me?fields=id,name,first_name,last_name,email,link', $this->access_token );
			} catch ( FacebookResponseException $e ) {
				$error = __( 'Graph returned an error: ', 'wpp_alfweb' ) . $e->getMessage();
				$message = array (
					'type'    => 'error',
					'content' => $error
				);
			} catch ( FacebookSDKException $e ) {
				$error = __( 'Facebook SDK returned an error: ', 'wpp_alfweb' ) . $e->getMessage();
				$message = array (
					'type'    => 'error',
					'content' => $error
				);
			}
			
			// If we caught an error
			if ( isset( $message ) ) {
				// Report our errors
				$_SESSION[ 'wpp_alf_facebook_message' ] = $message;
				// Redirect
				header( "Location: " . $this->redirect_url, true );
				die();
			}
			
			return $response->getGraphUser();
			
		}
		
		/**
		 * Login an user to WordPress
		 *
		 * @link https://codex.wordpress.org/Function_Reference/get_users
		 * @return bool|void
		 */
		private function loginUser() {
			
			// We look for the `eo_facebook_id` to see if there is any match
			$wp_users = get_users(
				array (
					'meta_key'    => 'wpp_alf_facebook_id',
					'meta_value'  => $this->facebook_details[ 'id' ],
					'number'      => 1,
					'count_total' => false,
					'fields'      => 'id',
				)
			);
			
			if ( empty( $wp_users[ 0 ] ) ) {
				return false;
			}
			
			// Log the user ?
			wp_set_auth_cookie( $wp_users[ 0 ] );
			
		}
		
		/**
		 * Create a new WordPress account using Facebook Details
		 */
		private function createUser() {
			
			
			$fb_user = $this->facebook_details;
			
			// Create an username
			$username = sanitize_user( str_replace( ' ', '_', strtolower( $this->facebook_details[ 'name' ] ) ) );
			
			// Creating our user
			$new_user = wp_create_user( $username, wp_generate_password(), $fb_user[ 'email' ] );
			
			if ( is_wp_error( $new_user ) ) {
				// Report our errors
				$_SESSION[ 'wpp_alf_facebook_message' ] = $new_user->get_error_message();
				// Redirect
				header( "Location: " . $this->redirect_url, true );
				die();
			}
			
			// Setting the meta
			update_user_meta( $new_user, 'first_name', $fb_user[ 'first_name' ] );
			update_user_meta( $new_user, 'last_name', $fb_user[ 'last_name' ] );
			update_user_meta( $new_user, 'user_url', $fb_user[ 'link' ] );
			update_user_meta( $new_user, 'wpp_alf_facebook_id', $fb_user[ 'id' ] );
			
			// Log the user ?
			wp_set_auth_cookie( $new_user );
			
		}
		
		
	}
	
	/*
	 * Starts our plugins, easy!
	 */
	new AWPP_FlFacebook();