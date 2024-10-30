<?php
/*
*
* Plugin Name: Mona qTranslate X Oembed Support
* Description: Support for Wordpress Oembed Function.
* Author: Mona Media
* Author URI: https://mona-media.com
* Version: 1.0
* Text Domain: mona-qtranslate-x-oembed-support
*
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( !class_exists( 'Mona_qtx_Oembed' ) ){
	class Mona_qtx_Oembed {		
		/**
		* Class Construct
		*/
		public function __construct() {	
			add_filter( 'oembed_request_post_id', array( $this, 'mona_oembed_request_post_id' ) );	
		}
		
		function mona_oembed_request_post_id($post_id = 0){
			global $wpdb;
			
			// support only qTranslate X
			if(!defined('QTX_VERSION')) return $post_id;
			
			if($post_id == 0){
				$url = parse_url(@$_GET['url'], PHP_URL_PATH);
			
				if($url != ''){
					$url = rtrim($url, '/');
					$url = explode('/', $url);
					
					if(count($url) > 0){
						$slug = $url[(count($url) - 1)];
						
						$post_id_search = 'SELECT post_id FROM '.$wpdb->postmeta.' WHERE (meta_key LIKE "%_qts_slug_%" AND meta_value LIKE "%'.esc_sql($slug).'%"';
						$post_id = (int) @$wpdb->get_var($post_id_search);
					}
				}
			}
		
			return $post_id;
		}
	}
	
	new Mona_qtx_Oembed();
}