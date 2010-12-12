<?php
/*
Plugin Name: WordPress Multi-site Filer
Plugin URI: https://github.com/ahmednuaman/WordPress-Filer
Description: It's a plugin that handles files that much better
Version: 1
Author: Ahmed Nuaman
Author URI: http://www.ahmednuaman.com
*/

/**
 * @author			Ahmed Nuaman (http://www.ahmednuaman.com)
 * @langversion		5
 * 
 * This work is licenced under the Creative Commons Attribution-Share Alike 2.0 UK: England & Wales License. 
 * To view a copy of this licence, visit http://creativecommons.org/licenses/by-sa/2.0/uk/ or send a letter 
 * to Creative Commons, 171 Second Street, Suite 300, San Francisco, California 94105, USA.
*/

/*
So, how do you use this? Well, in your .htaccess (or whatever rewrite program you use) will be a directive like so:
	RewriteRule ^([_0-9a-zA-Z-]+/)?files/(.+) wp-includes/ms-files.php?file=$2 [L]
	
You need to rewrite it to:
	RewriteRule ^([_0-9a-zA-Z-]+/)?files/(.+) wp-admin/admin-ajax.php?file=$2&origin=$1&action=get_blog_file [L]
	
This simply rewrites it to this bad ass plugin
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