<?php
class LRUtils {
	public static function get_ratings( $post_id ){
		global $wpdb;
		
		$ratings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `" . $wpdb->lr_ratings . "` WHERE `post_id` = %d", $post_id ) );
		
		if($ratings == null)
			$ratings = array();
		
		return $ratings;
	}
	
	public static function get_rating( $post_id, $user_id ){
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `" . $wpdb->lr_ratings . "` WHERE `post_id` = %d AND `user_id` = %d", $post_id, $user_id ) );
	}
	
	public static function get_aggregated_rating( $post_id ){
		global $wpdb;
		
		$data = $wpdb->get_row( $wpdb->prepare("SELECT ROUND( AVG(`rating`) * 2 ) / 2 `average` , COUNT(*) `count` FROM  `" . $wpdb->lr_ratings . "` WHERE `post_id` = %d GROUP BY `post_id`", $post_id));		
		return $data;
	}
	
	public static function store_rating( $post_id, $user_id, $rating ){
		// Validate the submitted rating
		if(!is_numeric($rating) || $rating < 1 || $rating > 5 )
			return false;
		
		// Validate user ID
		if(empty($user_id) || !is_numeric($user_id) || $user_id < 1)
			return false;
		
		// Check that the post we're trying to rate is published
		$post_status = get_post_status( $post_id );
		if(!$post_status == 'publish')
			return false;
		
		// Check if the current user already rated the post
		$exists = (LRUtils::get_rating($post_id, $user_id) != null);
		if($exists)
			return false;
		
		// Finally save the post
		global $wpdb;
		$data = array(
			'rating' => $rating,
			'post_id' => $post_id,
			'user_id' => $user_id,
			'user_ip' => $_SERVER['REMOTE_ADDR'],
			'ratingtime' => time()
		);
		$format = array('%d', '%d', '%d', '%s', '%d');
		
		$wpdb->insert( $wpdb->lr_ratings, $data, $format );
		
		return true;
	}
}
