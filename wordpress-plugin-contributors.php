<?php
/*
* Plugin Name: Contributors plugin
* Plugin URI: https://github.com/KomalRangoonwala/wordpress-plugin-contributors
* Description: Set and display a list of contributors on the posts.
* Version: 1.0
* Author: Komal Rangoonwala
* Author URI: https://kkrtify.wordpress.com
* License: GPLv2 or later
* Text Domain: wpplugin
*/
//If this file is called direk ctly, abort.
if(!defined('WPINC'))
{
	die;
}
define('WPPLUGIN_URL',plugin_dir_url(__FILE__));
define( 'WPPLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define('WPPLUGIN_UPLOADS_URL',wp_upload_dir(__FILE__));

function cd_meta_box_add()
{
	$multi_posts=array('post','page');
	foreach ($multi_posts as $multi_post) {
	    add_meta_box(
    			'my-meta-box-id', //id
     			'Contributors', //title
     			'cd_meta_box_cb', //callback
     			 $multi_post, //post type
     			'normal', //position
     			'high' //priority
     			);

	}
}
add_action( 'add_meta_boxes', 'cd_meta_box_add' );
//display HTML data

function cd_meta_box_cb($post)
{
	global $post;
 	echo'<b> Select the contributors that have contributed to this post: </b>';
 	echo '<br><br>';
 	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	global $wpdb;
	$authors = $wpdb->get_results("SELECT ID, user_nicename from $wpdb->users ORDER BY user_nicename");
	foreach($authors as $author) {
		echo"<input type='checkbox' id='my_meta_box_check' name='my_meta_box_check[]'";
		echo"value=";
		the_author_meta('user_nicename', $author->ID);
		echo">";
		echo"<label for='author'>";
		the_author_meta('user_nicename', $author->ID);
		echo"</label>";
		echo "<br />";
	}
}
//save custom data when our post is saved
function save_custom_data($post_id)
{
	$values=[];
	global $post;
	$contributor=get_post_meta($post->ID,'my_meta_box_check',true);
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_n
    	once( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
    if( !current_user_can( 'edit_post' ) ) return;
    if ( isset($_POST['my_meta_box_check']) ) 
    {
		update_post_meta($post_id, 'my_meta_box_check',$_POST['my_meta_box_check']);
	}
  	else 
  	{
    	delete_post_meta($post_id, 'my_meta_box_check');
	}
}
add_action( 'save_post', 'save_custom_data' );

function displaymeta( $content ){
			global $post;
			$m_meta_description = get_post_meta($post->ID, 'my_meta_box_check', 
												true);
			global $wpdb;
			$user_nicenames = $wpdb->get_results("SELECT id,user_nicename,user_email,user_url FROM 
			{$wpdb->prefix}users", ARRAY_N);	
			
			if($m_meta_description!=NULL){
			
			$content .= "<div id='cont'>";
			$content .= "<b>". "Contributors". "</b><br>";
			foreach($user_nicenames as $nice_name) 
			{
				$user_id = $nice_name[0];
				$name = $nice_name[1];
				$user_email=$nice_name[2];
				$user_website=$nice_name[3];
				$author_page=get_author_posts_url($user_id);

				foreach($m_meta_description as $val)
				{
					$flag=strcmp($name,$val);
					if($flag==0)
					{
						$content .= get_avatar($user_id,50);
						$content .= "<a href=".$author_page.">";
						$content .= $name."</a><br>";
					}
				}
			}
			$content .= "</div>";
		}
		else
		{
			$content = "<b> [Message from Contributors plugin] </b> Please mention contributors in post!";
		}
		return $content;
}
add_filter( 'the_content', 'displaymeta' );
?>

