<?php
class LROutputAPI {
	public function __construct() {
		/* LightRatings API */
		add_action( 'lr_averages', array(&$this, 'output_average'));
	}
	
	public function output_average( $size = 'large', $post_id = null ) {
		if( empty( $post_id ) )
			$post_id = get_the_ID();
		
		// Validate the star size. Should be either large, medium or small.
		if($size != 'large' && $size != 'medium' && $size != 'small')
			$size = 'large';
		
		global $wpdb;
		
		$data = $wpdb->get_row( $wpdb->prepare("SELECT AVG(`rating`) `average` , COUNT(*) `count` FROM  `" . $wpdb->ls_ratings . "` WHERE `post_id` = %d GROUP BY `post_id`", $post_id));
		
		echo "<div class='lr-rating-stars'>";
		
		$full_stars = floor($data->average);
		$empty_stars = 5 - ceil($data->average);
		
		for($i = 0; $i < $full_stars; $i++)
			echo "<div class='lr-sprite lr-star lr-star-" . $size . "'></div>";
		
		$half_star = ($data->average - $full_stars > 0);
		
		if($half_star)
			echo "<div class='lr-sprite lr-star lr-star-half lr-star-" . $size . "'></div>";
		
		for($i = 0; $i < $empty_stars; $i++)
			echo "<div class='lr-sprite lr-star lr-star-empty lr-star-" . $size . "'></div>";
		
		echo "</div>";
	}
}