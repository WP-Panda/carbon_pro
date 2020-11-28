<?php


class WPP_Fr_Mailcimp {

	public static $prefix = 'wpp_mc_';

	private static $dc = null;
	private static $redirect = null;
	private static $id = null;
	private static $secret = null;
	private static $key = null;

	public static $table_users = 'wpp_mc_users';
	public static $table_segment = 'wpp_mc_segments';
	private static $table_intesrest = 'wpp_mc_intesrest';
	private static $table_category = 'wpp_mc_category';


	function __construct() {

		$options        = get_option( 'wpp_mc' );
		self::$dc       = $options[ self::$prefix . 'server' ];
		self::$redirect = $options[ self::$prefix . 'redirect_url' ];
		self::$id       = $options[ self::$prefix . 'id' ];
		self::$secret   = $options[ self::$prefix . 'secret' ];
		self::$key      = $options[ self::$prefix . 'key' ];

		add_action( 'init', [ $this, 'create_table' ] );
	}


	/**
	 * Создание таблицы для вишлтиста
	 */
	public static function create_table() {

		global $wpdb;

		$wpdb->hide_errors();

		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var( sprintf( "show tables like '%s'", $wpdb->prefix . self::$table_users ) ) !== $wpdb->prefix . self::$table_users ) {
			$sql = sprintf( "CREATE TABLE `%s` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `mc_id` varchar(255) NOT NULL,
                `email` varchar(255) NOT NULL,
                `email_id` varchar(255) NOT NULL,
                `email_type` varchar(255) NOT NULL,
                `status` varchar(255) NOT NULL,
                `interests` longtext NOT NULL,
                UNIQUE KEY id (id)
        ) %s;", $wpdb->prefix . self::$table_users, $charset_collate );

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		if ( $wpdb->get_var( sprintf( "show tables like '%s'", $wpdb->prefix . self::$table_intesrest ) ) !== $wpdb->prefix . self::$table_intesrest ) {
			$sql = sprintf( "CREATE TABLE `%s` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `list_id` varchar(255) NOT NULL,
                `interest_id` varchar(255) NOT NULL,
                `title` varchar(255) NOT NULL,
                `type` varchar(255) NOT NULL,
                UNIQUE KEY id (id)
        ) %s;", $wpdb->prefix . self::$table_intesrest, $charset_collate );

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		if ( $wpdb->get_var( sprintf( "show tables like '%s'", $wpdb->prefix . self::$table_category ) ) !== $wpdb->prefix . self::$table_category ) {
			$sql = sprintf( "CREATE TABLE `%s` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `list_id` varchar(255) NOT NULL,
                `interest_id` varchar(255) NOT NULL,
                `category_id` varchar(255) NOT NULL,
                `name` varchar(255) NOT NULL,
                UNIQUE KEY id (id)
        ) %s;", $wpdb->prefix . self::$table_category, $charset_collate );

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		if ( $wpdb->get_var( sprintf( "show tables like '%s'", $wpdb->prefix . self::$table_segment ) ) !== $wpdb->prefix . self::$table_segment ) {
			$sql = sprintf( "CREATE TABLE `%s` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `list_id` varchar(255) NOT NULL,
                `name` varchar(255) NOT NULL,
                `options` longtext NOT NULL,
                `member_count` int(11) NOT NULL,
                `term_id` int(11) NOT NULL,
                `segment_id` varchar(255) NOT NULL,
                `intersest_id` varchar(255) NOT NULL,
                `intersest_cat_id` varchar(255) NOT NULL,
                UNIQUE KEY id (id)
        ) %s;", $wpdb->prefix . self::$table_segment, $charset_collate );

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

	}

	/**
	 * Урл для запроса к API
	 *
	 * @param $type
	 * @param null $subtype
	 *
	 * @return string
	 */
	function api_urls( $part ) {

		return sprintf( 'https://%sapi.mailchimp.com/3.0/%s/', self::$dc, $part );
	}


	/**
	 * Отправка курл
	 *
	 * @param $type
	 * @param $data
	 *
	 * @return array
	 *
	 */
	function curl( $part, $data = [], $post = 'POST' ) {
		$url = $this->api_urls( $part );
		$ch  = curl_init();

		$string = null;

		if ( $post === 'POST' || $post === 'PATCH' ) {
			if ( ! empty( $data ) ) {
				$data = json_encode( $data );
			} else {
				return [ 'Local error' => 'Data is empty' ];
			}
		}

		if ( $post === 'GET' ) {
			if ( ! empty( $data ) ) {
				$string = '?' . http_build_query( $data ) . "\n";
			}
		}


		curl_setopt( $ch, CURLOPT_URL, $url . $string );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_USERPWD, "anystring:" . self::$key );

		if ( $post === 'POST' ) {
			curl_setopt( $ch, CURLOPT_POST, true );
		}
		if ( $post === 'POST' || $post === 'PATCH' ) {
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		}

		if ( $post === 'DELETE' || $post === 'PATCH' ) {
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $post );
		}

		$result = curl_exec( $ch );
		curl_close( $ch );

		return json_decode( $result, true );

	}

	/**
	 * Список листов
	 * @return array
	 */
	function get_lists() {
		return $this->curl( 'lists', [], 'GET' );
	}

	/**
	 * ID нулевого листа
	 */
	function list_null_id() {

		$lists = $this->get_lists();

		if ( ! empty( $lists ) ) {
			return $lists['lists'][0]['id'];
		} else {
			return false;
		}

	}


	/**
	 * Добавление подписчиков
	 *
	 * @param array $members
	 *
	 * @return bool
	 */
	function add_members_to_list( $members = [], $list = null ) {

		/**
		 * Образец массива $members
		 */
		/*
		  ['members' => [[
		  'email_address' => 'panda@wp-panda.com',
		  'status'        => 'subscribed',
		  'interests'     =>  (object)['403b8ef804'=>true]
		  ]]
		 */
		global $wpdb;

		if ( empty( $list ) ) {
			$list = $this->list_null_id();
		}

		if ( empty( $list ) ) {
			return false;
		}


		if ( ! empty( $members ) ) {
			$request = $this->curl( 'lists/' . $list, $members );
		}


		if ( empty( $request['errors'] ) ) {
			$return = $request['new_members'];

			foreach ( $return as $one_user ) {

				$array               = [];
				$format              = [];
				$array['mc_id']      = $one_user['id'];
				$format[]            = '%s';
				$array['email']      = $one_user['email_address'];
				$format[]            = '%s';
				$array['email_id']   = $one_user['unique_email_id'];
				$format[]            = '%s';
				$array['email_type'] = $one_user['email_type'];
				$format[]            = '%s';
				$array['status']     = $one_user['status'];
				$format[]            = '%s';

				$interests = [];

				foreach ( $one_user['interests'] as $key => $val ) {
					if ( $val === true ) {
						$interests[] = $key;
					}

				}

				$array['interests'] = serialize( $interests );
				$format[]           = '%s';

				$out = $wpdb->insert(
					$wpdb->prefix . self::$table_users,
					$array,
					$format
				);
			}

			return $out;

		} else {
			return $request['errors'];
		}
	}

	function add_interest_to_user( $members_email, $target, $usar_base, $list = null ) {

		/**
		 * Образец массива $members
		 */
		/*
		  ['members' => [[
		  'email_address' => 'panda@wp-panda.com',
		  'status'        => 'subscribed',
		  'interests'     =>  (object)['403b8ef804'=>true]
		  ]]
		 */

		global $wpdb;

		if ( empty( $list ) ) {
			$list = $this->list_null_id();
		}

		if ( empty( $list ) ) {
			return false;
		}

		if ( empty( $members_email ) ) {
			return false;
		}

		$members_id = md5( $members_email );

		$url = sprintf( '/lists/%s/members/%s', $list, $members_id );

		$interest = unserialize( $usar_base->interests );

		if ( in_array( $target, $interest ) ) {
			return [ 'error' => __( 'You already signed up for this', 'wpp-fr' ) ];
		} else {

			$request = $this->curl( $url, [ 'interests' => (object) [ $target => true ] ], 'PATCH' );

			if ( empty( $request['status'] ) ) {
				return $request;
			} else {
				return $request['status'];
			}
		}


	}

	/**
	 * Новый шаблон письма
	 *
	 * @param $template
	 *
	 * @return array
	 */
	function new_template( $template ) {
		return $this->curl( 'templates', $template );
	}

	/**
	 * Категория интересов
	 *
	 * @param $template
	 *
	 * @return array
	 */
	function new_interest_category( $data, $list_id = null ) {
		global $wpdb;
		/**
		 * Образец массива - $data
		 */
		/*
		[ 'title' => 'Cars', 'type' => 'dropdown' ];
		*/
		if ( empty( $list_id ) ) {
			$list_id = $this->list_null_id();
		}

		$request = $this->curl( 'lists/' . $list_id . '/interest-categories', $data );
		$array   = $format = [];
		if ( empty( $request['status'] ) ) {
			$array['list_id']     = $request['list_id'];
			$format[]             = '%s';
			$array['interest_id'] = $request['id'];
			$format[]             = '%s';
			$array['title']       = $request['title'];
			$format[]             = '%s';
			$array['type']        = $request['type'];
			$format[]             = '%s';

			$out = $wpdb->insert(
				$wpdb->prefix . self::$table_intesrest,
				$array,
				$format
			);

			return $out;

		} else {
			return [ 'error' => $request['status'] . "\n" . $request['detail'] ];
		}

	}

	/**
	 * Интерес в категорию
	 *
	 * @param $cat_id
	 * @param $data
	 * @param null $list_id
	 *
	 * @return array
	 */
	function new_interest( $cat_id, $data, $list_id = null ) {

		global $wpdb;
		if ( empty( $list_id ) ) {
			$list_id = $this->list_null_id();
		}

		if ( ! empty( $data ) ) {
			$request = $this->curl( 'lists/' . $list_id . '/interest-categories/' . $cat_id . '/interests', $data );
		}

		$array = $format = [];
		if ( empty( $request['status'] ) ) {
			$array['list_id']     = $request['list_id'];
			$format[]             = '%s';
			$array['interest_id'] = $request['category_id'];
			$format[]             = '%s';
			$array['category_id'] = $request['id'];
			$format[]             = '%s';
			$array['name']        = $request['name'];
			$format[]             = '%s';

			$out = $wpdb->insert(
				$wpdb->prefix . self::$table_category,
				$array,
				$format
			);

			if ( ! empty( $out ) ) {
				return [ 'id' => $request['id'] ];
			}

		} else {
			return [ 'error' => $request['status'] . "\n" . $request['detail'] ];
		}

	}


	/**
	 * Новый сегмент
	 *
	 * @param null $list_id
	 *
	 * @return array
	 */

	function new_segment( $data, $term_id, $segment, $interest_cat_id, $list_id = null ) {

		global $wpdb;

		if ( empty( $list_id ) ) {
			$list_id = $this->list_null_id();
		}

		/*
				$data = [
					'name'    => 'NEW 2',
					'options' => (object) [
						'match'      => 'any',
						'conditions' => (array) [
							(object) [
								'condition_type' => 'Interests',
								"field"          => "interests-ebef886730",
								"op"             => "interestcontains",
								"value"          => [ '1e85c37d9b' ]
							]
						]
					]
				];
		*/

		if ( ! empty( $data ) ) {
			$request = $this->curl( 'lists/' . $list_id . '/segments', $data );

			if ( empty( $request['status'] ) ) {
				$array['list_id']          = $request['list_id'];
				$format[]                  = '%s';
				$array['name']             = $request['name'];
				$format[]                  = '%s';
				$array['options']          = serialize( $request['options'] );
				$format[]                  = '%s';
				$array['member_count']     = $request['member_count'];
				$format[]                  = '%d';
				$array['term_id']          = $term_id;
				$format[]                  = '%d';
				$array['intersest_id']     = $segment;
				$format[]                  = '%s';
				$array['segment_id']       = $request['id'];
				$format[]                  = '%s';
				$array['intersest_cat_id'] = $interest_cat_id;
				$format[]                  = '%s';

				$out = $wpdb->insert(
					$wpdb->prefix . self::$table_segment,
					$array,
					$format
				);

				#wpp_d_log( $request );

				$out = [ 'message' => 'Segment Added' ];

			} else {
				$out = [ 'error' => $request['status'] . "\n" . $request['detail'] ];
			}
		} else {
			$out = [ 'error' => 'data is Empty' ];
		}

		return $out;
	}

	/**
	 * Удаление сегмента
	 *
	 * @param $segment_id
	 * @param null $list_id
	 */
	function segment_delete( $segment_id, $list_id = null ) {

		if ( empty( $list_id ) ) {
			$list_id = $this->list_null_id();
		}


		$request = $this->curl( 'lists/' . $list_id . '/segments/' . $segment_id, [], 'DELETE' );


	}

	function interest_delete( $interest_category_id, $interest_id, $list_id = null ) {

		if ( empty( $list_id ) ) {
			$list_id = $this->list_null_id();
		}

		$request = $this->curl( 'lists/' . $list_id . '/interest-categories/' . $interest_category_id . '/interests/' . $interest_id, [], 'DELETE' );


	}

}

$GLOBALS['wpp_fr_mc'] = new WPP_Fr_Mailcimp();