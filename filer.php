<?php
/*
Plugin Name: WordPress Multi-site Filer
Plugin URI: https://github.com/ahmednuaman/WordPress-Filer
Description: It's a plugin that handles files that much better
Version: 1
Author: Ahmed Nuaman
Author URI: http://www.ahmednuaman.com
*/

error_reporting( E_ALL ^ E_NOTICE );

add_action( 'wp_ajax_get_blog_file', 				'filer_get_blog_file' );

add_action( 'wp_ajax_nopriv_get_blog_file', 		'filer_get_blog_file' );

$files		= '/wp-content/blogs.dir/';

function filer_get_blog_file()
{
	global $files;
	
	$path	= _to_word( $_REQUEST[ 'origin' ] );

	$b_id	= _filer_get_blog_id( $path );

	$dir	= $files . $b_id . '/files/' . $_REQUEST[ 'file' ];
	
	header( 'Location: ' . $dir );
	
	die();
}

if ( !function_exists( '_filer_get_blog_id' ) )
{
	function _filer_get_blog_id($blog_path)
	{
		global $wpdb;

		$blog_path	= _to_safe_var( $blog_path );

		$blog		= $wpdb->get_row( 'SELECT blog_id FROM wp_blogs WHERE path = "/' . $blog_path . '/"' );

		return $blog->blog_id;
	}
}

if ( !function_exists( '_to_safe_var' ) )
{
	function _to_safe_var($v)
	{
		return str_replace( '"', '', mysql_real_escape_string( $v ) );
	}
}

if ( !function_exists( '_to_word' ) )
{
	function _to_word($v)
	{
		return _to_safe_var( preg_replace( '/[^A-z]/', '', $v ) );
	}
}
?>