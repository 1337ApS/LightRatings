<?php
class LRUtils {
	public static function get_ratings( $post_id ){
		global $wpdb;
		
		$ratings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `" . $wpdb->lr_ratings . "` WHERE `post_id` = %d", $post_id ) );
		
		if($ratings == null)
			$ratings = array();
		
		return $ratings;
	}
	
	public static function get_aggregated_rating( $post_id ){
		global $wpdb;
		
		
	}
}
