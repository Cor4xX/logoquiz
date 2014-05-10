<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/third_party/hybridauth/Hybrid/Auth.php';

class HybridAuth extends Hybrid_Auth
{
	function __construct($config = array())
	{
		$ci =& get_instance();
		$ci->load->helper('url_helper');

		$config =  
			array(
				"base_url" => base_url()."index.php/user/endpoint", 

				"providers" => array ( 
					// openid providers
					"OpenID" => array (
						"enabled" => true
					),

					"Yahoo" => array ( 
						"enabled" => false,
						"keys"    => array ( "id" => "", "secret" => "" ),
					),

					"AOL"  => array ( 
						"enabled" => false 
					),

					"Google" => array ( 
						"enabled" => false,
						"keys"    => array ( "id" => "", "secret" => "" ), 
					),

					"Facebook" => array ( 
						"enabled" => true,
						"keys"    => array ( "id" => "1488679854684116", "secret" => "9848b6330bd705e42db9d89af1c0af17" ), 
					),

					"Twitter" => array ( 
						"enabled" => false,
						"keys"    => array ( "key" => "", "secret" => "" ) 
					),

					// windows live
					"Live" => array ( 
						"enabled" => false,
						"keys"    => array ( "id" => "", "secret" => "" ) 
					),

					"MySpace" => array ( 
						"enabled" => false,
						"keys"    => array ( "key" => "", "secret" => "" ) 
					),

					"LinkedIn" => array ( 
						"enabled" => false,
						"keys"    => array ( "key" => "", "secret" => "" ) 
					),

					"Foursquare" => array (
						"enabled" => false,
						"keys"    => array ( "id" => "", "secret" => "" ) 
					),
				),

				// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
				"debug_mode" => false,

				"debug_file" => "",
			);
		parent::__construct($config);

		log_message('debug', 'HybridAuthLib Class Initalized');
	}

	/**
	 * @deprecated
	 */
	public static function serviceEnabled($service)
	{
		return self::providerEnabled($service);
	}

	public static function providerEnabled($provider)
	{
		return isset(parent::$config['providers'][$provider]) && parent::$config['providers'][$provider]['enabled'];
	}
}

/* End of file HybridAuthLib.php */
/* Location: ./application/libraries/HybridAuthLib.php */