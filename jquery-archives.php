<?php
/*
Plugin Name: jQuery Archives
Version:     1.0.0
Plugin URI:  http://itx-technologies.com/blog/jquery-archives-plugin
Description: Displays all published posts per month with an accordion jQuery effect.  Simply create a new template in which you call the fonction jquery_archives().  Create a new page with that template and you're all set ! Visit our <a href="http://itx-technologies.com/blog/jquery-archives-plugin" target="_blank">support page</a> for more questions.  Created by <a href="http://itx-technologies.com">iTx Technologies</a>.
Author:      iTx Technologies
Author URI:  http://itx-technologies.com/
*/

if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

//Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
  define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );


function wp_js_head()
{
    if ( !wp_script_is( 'jquery' ) ) {
     
      echo "\n".' <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" charset="utf-8"></script> '."\n";
      echo "\n".' <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js" charset="utf-8"></script>'."\n";
    }
    
    echo '<script>
    $(function() {
      $( "#accordion" ).accordion({
	collapsible: true,
				  autoHeight: false,
				  header: "h3"
      });
    });
    </script>';
    
}

function jquery_archives() {
  
  echo "<div id='accordion'>";
  global $wpdb;
  $table_name = $wpdb->prefix . "posts";
  $now_y = date('Y');
  $now_m = date('m');
  
  for ($i=$now_y; $i>1990; $i--) {
    $query_cy = "SELECT COUNT(ID) AS count_y FROM $table_name WHERE post_type='post' AND year(post_date) = '$i' AND post_status = 'publish'";
    $count_y = $wpdb->get_var($wpdb->prepare($query_cy));
    
    if ($count_y)
    {     setlocale (LC_TIME, get_locale());
    echo "<h2>".$i."</h2>";
    
    for ($j=12; $j>0; $j--) 
    {
      
      $query_cm = "SELECT COUNT(ID) AS count_m FROM $table_name WHERE post_type='post' AND month(post_date) = '$j' AND year(post_date) = '$i' AND post_status = 'publish'";
      $count_m = $wpdb->get_var($wpdb->prepare($query_cm));	
      
      if ($count_m) 
      {
	
	$query_y = "SELECT post_title, post_name FROM $table_name WHERE post_type='post' AND year(post_date) = '$i' AND month(post_date) = '$j' AND post_status = 'publish' ORDER BY post_date DESC";
	$myrows = $wpdb->get_results($query_y);
	echo "<h3><a href='#'>".utf8_encode(strftime('%B',mktime(0, 0, 0, $j)))."</a></h3>";
	echo "<ul>";
	foreach($myrows as $myrow)
	{
	  $title = $myrow->post_title;
	  $permalink = $myrow->post_name;
	  echo "<a href='".$permalink."' title='".$title."'>".$title."</a><br />";
	}
	echo "</ul>";
      }
    }
    }
  }
  echo "</div>";  
  
}

add_action('wp_head', 'wp_js_head');

