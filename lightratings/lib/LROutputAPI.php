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
		
		$data = LRUtils::get_aggregated_rating($post_id);
		
		$user_id = get_current_user_id();
		$user_rating = LRUtils::get_rating($post_id, $user_id);
		
		$can_rate = ($user_rating != null ? '' : 'lr-can-rate');
		
		echo "<div class='lr-rating-stars " . $can_rate . "' data-postid='" . $post_id . "' data-average='" . $data->average . "'>";
		
		$full_stars = floor($data->average);
		$empty_stars = 5 - ceil($data->average);
		$stroked = ($user_rating->rating - $full_stars);
		
		for($i = 0; $i < $full_stars; $i++)
			echo "<div class='lr-sprite lr-star lr-star-" . $size . "'></div>";
		
		$half_star = ($data->average - $full_stars > 0);
		
		if($half_star){
			echo "<div class='lr-sprite lr-star lr-star-half lr-star-" . $size . ($stroked > 0 ? ' lr-star-stroke' : '') . "'></div>";
			$stroked--;
		}
		
		for($i = 0; $i < $empty_stars; $i++)
			echo "<div class='lr-sprite lr-star lr-star-empty lr-star-" . $size . ( $stroked - $i > 0 ? ' lr-star-stroke' : '') . "'></div>";
		echo "</div>";
	}
}