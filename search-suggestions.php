<?php 

/**
 * Must use plugin_dir_path() and plugins_url() for absolute paths and URLs.
 * 
 * RESOURCES:
 * - https://code.tutsplus.com/tutorials/custom-database-tables-creating-the-table--wp-28124
 * - https://codex.wordpress.org/Writing_a_Plugin
 * - https://codex.wordpress.org/Creating_Tables_with_Plugins
 * 
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if( !class_exists('SearchSuggestions') ) :
/**
* 
*/
class SearchSuggestions
{
	
	function __construct(argument)
	{	
		global $sesu_db_version;
		$sesu_db_version = '1.0';
		// Create tables on plugin activation
		register_activation_hook( __FILE__, 'create_suggestions_table' );
	}

	function register_suggestions_table() {
	    global $wpdb;
	    $wpdb->search_suggestions = $wpdb->prefix . "search_suggestions";
	}
	function create_suggestions_table() {
	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    global $wpdb;
	    global $charset_collate;

	    // Call this manually as we may have missed the init hook
	   register_suggestions_table();

	   $sql_create_table = "CREATE TABLE {$wpdb->search_suggestions} (
	             sug_id bigint(20) unsigned NOT NULL auto_increment,
	             post_id bigint(20) unsigned NOT NULL default '0',
	             suggestion varchar(20) NOT NULL default '',
	             suggestion_type varchar(20) NOT NULL default '',
	             PRIMARY KEY  (sug_id),
	             KEY post_id (post_id)
	        ) $charset_collate; ";
	    
	   dbDelta( $sql_create_table );

	   add_option( 'sesu_db_version', $sesu_db_version );

	   // addToDatabase();
	}



	function addToDatabase() {

		/**
		
			TODO:
			- Get all Title names
			- Get all publishers from ACF

			TODO [UPGRADE]:
			- Choose from the wp interface
		
		 */

		$title = 'The Kinfolk Table';
		$type = 'title';
		$id = '321';

		$table_name = $wpdb->prefix . 'search_suggestions';

		
		

		$wpdb->insert( 
			$table_name, 
			array( 
				'suggestion' => $title, 
				'suggestion_type' => $type, 
				'post_id' => $id,
			) 
		);

	}
}


// initialize
new SearchSuggestions();


// class_exists check
endif;

?>
