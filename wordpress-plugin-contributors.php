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
if (!defined('WPINC'))
{
	die;
}
define('WPPLUGIN_URL', plugin_dir_url(__FILE__));
define('WPPLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPPLUGIN_UPLOADS_URL', wp_upload_dir(__FILE__));

include(plugin_dir_path(__FILE__).'includes/wpplugin-styles.php');
include(plugin_dir_path(__FILE__).'includes/wpplugin-scripts.php');


function cd_meta_box_add()
{
	$multi_posts=array('post', 'page');
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
add_action('add_meta_boxes', 'cd_meta_box_add');


//display HTML data

function cd_meta_box_cb($post)
{
	$metavalues=get_post_meta($post->ID, 'my_meta_box_check', true);
	$arr=array();
	if($metavalues!=NULL){
	for ($i=0;$i<count($metavalues);$i++) {	
		$arr[$i]=$metavalues[$i];
	//	echo $arr[$i] . "</br>";
	}
	}
	global $post;
	echo'<body>';
 	echo'<b> Select the contributors that have contributed to this post: </b>';
 	echo '<br></br>';
 	wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');
	global $wpdb;

	$authors=$wpdb->get_results("SELECT wp_users.ID, wp_users.user_nicename 
	FROM wp_users INNER JOIN wp_usermeta 
	ON wp_users.ID = wp_usermeta.user_id 
	WHERE (wp_usermeta.meta_key = 'wp_capabilities' AND wp_usermeta.meta_value LIKE '%author%')
	OR (wp_usermeta.meta_key = 'wp_capabilities' AND wp_usermeta.meta_value LIKE '%administrator%')
	OR (wp_usermeta.meta_key = 'wp_capabilities' AND wp_usermeta.meta_value LIKE '%contributor%') 
	OR (wp_usermeta.meta_key = 'wp_capabilities' AND wp_usermeta.meta_value LIKE '%editor%')
	ORDER BY wp_users.user_nicename");
	
	$author_id_now=$post->post_author;
	$original_display_name = get_the_author_meta( 'user_nicename' , $author_id_now ); 

	$current_user = wp_get_current_user();
		

	foreach ($authors as $author) {

	$author_info=get_userdata($author->ID);
	$author_first_name=$author_info->first_name;
	$author_last_name=$author_info->last_name;
		
		if(strcmp($original_display_name,$author->user_nicename)==0)
		{		
			echo"<input type='checkbox' id='hidden_my_meta_box_check' name='my_meta_box_check[]'";
			echo"value=";
			the_author_meta('user_nicename', $author->ID);
			echo" checked disabled>";

			echo"<input type='hidden' id='hidden_my_meta_box_check' name='my_meta_box_check[]'";
			echo"value=";
			the_author_meta('user_nicename', $author->ID);
			echo">";
		}
		else
		{
			echo"<input type='checkbox' id='my_meta_box_check' name='my_meta_box_check[]'";
			echo"value=";
			the_author_meta('user_nicename', $author->ID);
			echo">";	
		}

		echo $author_first_name ." ". $author_last_name ." ";
		echo"(";
		echo"<label id='labelid' for='author'>";
		the_author_meta('user_nicename', $author->ID);
		echo"</label>";
		echo")";
		echo "<br/>";

	}
	
	echo'</body></br>';
?>
<script type="text/javascript">

(function() {

    var boxes = document.querySelectorAll("input[name='my_meta_box_check[]']");
    for (var i = 0; i < boxes.length; i++) {
        var box = boxes[i];
        if (box.hasAttribute("value")) {
            setupBox(box);
        }
    }
    
    function setupBox(box) {
        var storageId = box.getAttribute("value");
        var oldVal    = localStorage.getItem(storageId);
        box.checked = oldVal === "true" ? true : false;

        box.addEventListener("change", function() {
            localStorage.setItem(storageId, this.checked);
        }); 
    }

var metaval= <?php echo json_encode($arr) ?>;
for (var i = 0; i < boxes.length; i++) 
{
	for (var j = 0; j < metaval.length; j++) 
	{
		if(metaval[j]===boxes[i].getAttribute("value"))
		{
			boxes[i].checked=true;
		}
	}
}


})();
document.getElementById("hidden_my_meta_box_check").checked = true;
localStorage.clear();

</script>

<?php
}

//save custom data when our post is saved
function save_custom_data($post_id)
{
	global $post,$wpdb;

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'my_meta_box_nonce')) return;
	if (!current_user_can('edit_post')) return;
	if (isset($_POST['my_meta_box_check'])) 
	{
		update_post_meta($post_id, 'my_meta_box_check', $_POST['my_meta_box_check']);
	}
  	else 
  	{
		delete_post_meta($post_id, 'my_meta_box_check');
	}

}

add_action('save_post', 'save_custom_data');


function displaymeta($content) {
			global $post;
			$m_meta_description=get_post_meta($post->ID, 'my_meta_box_check', true);
			global $wpdb;
			$user_nicenames=$wpdb->get_results("SELECT id,user_nicename,user_email,user_url,display_name FROM 
			{$wpdb->prefix}users", ARRAY_N);	


			if ($m_meta_description!=NULL) 
			{

			$content.="<div id='cont'>";
			$content.="<b>"."Contributors"."</b><br>";
			foreach ($user_nicenames as $nice_name) 
			{

				$user_id=$nice_name[0];
				$name=$nice_name[1];
				$user_email=$nice_name[2];
				$user_website=$nice_name[3];
				$display_name=$nice_name[4];
				$author_page=get_author_posts_url($user_id);

				$user_info=get_userdata($user_id);
				$first_name=$user_info->first_name;
				$last_name=$user_info->last_name;


				foreach ($m_meta_description as $val)
				{
					$flag=strcmp($name, $val);
					if ($flag==0)
					{
						$content.=get_avatar($user_id, 50);
						$content.="<a href=".$author_page.">";
						
						if($first_name!='' AND $last_name!='')
						{
							$content.=$first_name." ".$last_name ."</a><br>";
						}
						else
						{
							$content.=$display_name."</a><br>";
						}
					}
				}
			}

			$content.="</div>";


		} 
		else
		{
			$content="<b> [Message from Contributors plugin] </b> Please mention contributors in post!";
		}
		return $content;

}
add_filter('the_content', 'displaymeta');

add_action( 'pre_get_posts', 'modify_author_query' );
function modify_author_query( $query ) {
    // Check if on frontend and author query is modified
    if ( ! is_admin() && $query->is_author() ) {
        $author_name =  $query->query_vars['author_name'];
        $meta_query = array(  
            array(
                'key' => 'my_meta_box_check',
                'value' => $author_name,
                'compare' => 'LIKE'
            )
        );
        $query->set( 'meta_query', $meta_query );
        // unset the default author
        unset( $query->query_vars['author_name'] );
    }
}
