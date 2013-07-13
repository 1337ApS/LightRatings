<?php
/*
Plugin Name: LightRatings
Description: An ultra-lightweifght Rating system for Wordpress. It actually doesn't do a thing, unless you specifically tell it to.
Author: 1337 ApS
Version: 1.0
Author URI: http://1337.dk/
*/

define('LR_VERSION', '1.0');
define('LR_PLUGIN_FILE', __FILE__);
define('LR_PLUGIN_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('LR_PLUGIN_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('LR_DEBUG', true);
define('LR_LANG', 'lightratings');


add_action( 'activated_plugin', 'save_activation_error' );
function save_activation_error() {
	$error = ob_get_contents();

	if(!empty($error))
		update_option( 'lr_plugin_error',  $error );
}

class LightRatings {
	public function __construct() {
		spl_autoload_register(array(&$this, 'autoloader'));
		add_action( 'plugins_loaded', array(&$this, 'initialize_wpdb_tables'));
		register_activation_hook( __FILE__, array(&$this, 'install') );
		
		add_action('wp_enqueue_scripts', array(&$this, 'enqueue'));
		
		new LROutputAPI();
		new LRAjaxAPI();
	}
	
	public function enqueue(){
		wp_enqueue_style( 'lightratings', LR_PLUGIN_URL . 'assets/css/main.css', array(), LR_VERSION );
		wp_enqueue_script( 'lightratings', LR_PLUGIN_URL . 'assets/js/rating.js', array( 'jquery' ), LR_VERSION );
	}
	
	public function install(){		
		global $wpdb;
		
		$this->initialize_wpdb_tables();
		
		$sql = array();
		
		$sql[] = "CREATE TABLE " . $wpdb->lr_log . " (
					id INT(11) AUTO_INCREMENT NOT NULL,
					event VARCHAR(100) NOT NULL,
					level VARCHAR(100) NOT NULL DEFAULT 'notice',
					description TEXT,
					details LONGTEXT,
					logtime INT(11) NOT NULL,
					PRIMARY KEY  (id)
				);";
		
		$sql[] = "CREATE TABLE " . $wpdb->lr_ratings . " (
					id INT(11) AUTO_INCREMENT NOT NULL,
					rating INT(11) NOT NULL,
					post_id INT(11) NOT NULL,
					user_id INT(11) NOT NULL,
					user_ip VARCHAR(55) NOT NULL,
					ratingtime INT(11) NOT NULL,
					PRIMARY KEY  (id)
				);";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		foreach($sql as $s)
			dbDelta($s);
	}
	
	public function initialize_wpdb_tables(){
		global $wpdb;
		
		$wpdb->lr_log = $wpdb->prefix."lightratings_log";
		$wpdb->lr_ratings = $wpdb->prefix."lightratings";
	}
	
	private function autoloader($class){
		$path = dirname(__FILE__).'/';
		$paths = array();
		$exts = array('.php', '.class.php');
		
		$paths[] = $path;
		$paths[] = $path.'lib/';
				
		foreach($paths as $p)
			foreach($exts as $ext){
				if(file_exists($p.$class.$ext)){
					require_once($p.$class.$ext);
					return true;
				}
			}
		
		return false;
	}
}
new LightRatings();