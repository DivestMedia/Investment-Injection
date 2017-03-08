<?php

function add_scripts(){
  wp_enqueue_style( 'jquery-ui-autocomplete' );
  wp_enqueue_script( 'jquery-ui-autocomplete' );
}
add_action('wp_enqueue_scripts', 'add_scripts');

add_action( 'rest_api_init', 'location_api_route' );

function location_api_route() {
	register_rest_route( 'api', 'location', array(
		'methods'  => WP_REST_Server::READABLE,
		'callback' => 'get_locations',
	) );
}

/**
 * Generate results for the /wp-json/api/location route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function get_locations( WP_REST_Request $request ) {
	// Do something with the $request
	global $wpdb;

	$_params = $request->get_params();
	$_limit = !empty($_params['limit'])?$_params['limit']:10;
	$_main_where = [];
	$_where = [];
	$loc = [];
	if(!empty($_params['s'])){
		$_keys = explode(' ', $_params['s']);
		if(!empty($_keys)){
			$_t = array_splice($_keys, 0, 1);
			$_main_where = !empty($_t[0])?'+'.$_t[0].'*':'';
			foreach ($_keys as $_key) {
				if(!empty($_key)){
					array_push($_where,'<'.$_key.'*');
				}
			}
		}
	}

	if(!empty($_where)){
		$_where = $_main_where.' +( '.implode(' ', $_where).' )';
		$loc = $wpdb->get_results(" SELECT `country_name`, `city_name`, MATCH (`country_name`,`city_name`) AGAINST (' ".$_where." ' IN BOOLEAN MODE) AS relevance FROM `location` WHERE MATCH (`country_name`,`city_name`) AGAINST (' ".$_where." ' IN BOOLEAN MODE) GROUP BY `city_name` ORDER BY relevance DESC LIMIT 20");
	}elseif(!empty($_main_where)){
		$loc = $wpdb->get_results(" SELECT `country_name`, `city_name`, MATCH (`country_name`,`city_name`) AGAINST (' ".$_main_where." ' IN BOOLEAN MODE) AS relevance FROM `location` WHERE MATCH (`country_name`,`city_name`) AGAINST (' ".$_main_where." ' IN BOOLEAN MODE) GROUP BY `city_name` ORDER BY relevance DESC LIMIT 20");
	}


	return json_encode($loc);
}