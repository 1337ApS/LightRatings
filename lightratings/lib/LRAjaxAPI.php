<?php
class LRAjaxAPI {
	public function __construct() {
		add_action('wp_ajax_lr_submit_rating', array(&$this, 'submit_rating'));
	}
	
	public function submit_rating(){
		$rating = intval($_POST['rating']);
		$post_id = intval($_POST['post_id']);
		
		if(!is_user_logged_in())
			$this->ajax_return();
		
		$user_id = get_current_user_id();
		
		$result = LRUtils::store_rating($post_id, $user_id, $rating);
		
		$avg = LRUtils::get_aggregated_rating($post_id);
		
		$return = array(
			'error' => !$result,
			'average' => $avg->average,
			'count' => $avg->count,
			'rating' => $rating
		);
		
		$this->ajax_return($return);
	}
	
	private function ajax_return( $data = false ){
		echo json_encode( $data );
		exit();
	}
}
