<?php 
/*plugin name:my_plugin*/

//Enqueue a custom file

add_action('wp_enqueue_scripts','wp_enqueue_custom_script_file');
function wp_enqueue_custom_script_file(){
	wp_enqueue_script('jquery');
	
	wp_enqueue_script( 'my_custom_scripts', plugin_dir_url( __FILE__ ) .'/assets/js/main.js' );
	wp_localize_script( 'my_custom_scripts', 'cpm_object', array('ajax_url' => admin_url( 'admin-ajax.php' ) ));		//ajax url refer 
}

add_filter( 'theme_page_templates', 'add_page_template_to_plugin' );
/*
* Add page templates.
*/
function add_page_template_to_plugin($templates)
{
   $templates[plugin_dir_path( __FILE__ ).'/include/templates/page_form.php' ] = __( 'page_form', 'text-domain' );

   return $templates;
}

function wpb_test_shortcode() { 
	$atts='<form method="post" action="">';
	$atts.='<lable>NAME</lable>';
	$atts.='<input type="text" name="name"  class="name" placeholder="Enter your name" required><br><br>';
	$atts.='<lable>EMAIL</lable>';
	$atts.='<input type="email" name="mail"  class="mail" placeholder="Enter your email" required><br><br>';
	$atts.='<lable>PSW</lable>';
	$atts.='<input type="password" name="psw"  class="psw" placeholder="Enter your password" required><br><br>';
	$atts.='<lable>FILE</lable>';
	$atts.='<input type="file" name="file"  class="file"  required><br><br>';
	$atts.='<input type="submit">';
	$atts.='</form>';
	return $atts;
} 
add_shortcode('my_custom_shortcode', 'wpb_test_shortcode');

//add page when plugin activate 
function create_page(){
	$page_title='form';
	$post_content='[my_custom_shortcode]';
	$form=array(
		'post_title'=>$page_title,
		'post_content'=>$post_content,
		'post_type'=>"page",
		'post_statush'=>'publish'
	);
	$insert_page=wp_insert_post($form);
}
register_activation_hook(__FILE__, 'create_page');

//custom postype 
if ( ! function_exists('custom_post_type_admin_table') ) {
  
	// Register Custom Post Type
	function custom_post_type_admin_table() {
	
		$labels = array(
			'name'                  => _x( 'admin_tables', 'Post Type General Name', 'custom_admin_table' ),
			'singular_name'         => _x( 'admin_table', 'Post Type Singular Name', 'custom_admin_table' ),
			'menu_name'             => __( 'admin_table', 'custom_admin_table' ),
			'name_admin_bar'        => __( 'Post Type', 'custom_admin_table' ),
			'archives'              => __( 'Item Archives', 'custom_admin_table' ),
			'attributes'            => __( 'Item Attributes', 'custom_admin_table' ),
			'parent_item_colon'     => __( 'Parent Item:', 'custom_admin_table' ),
			'all_items'             => __( 'All Items', 'custom_admin_table' ),
			'add_new_item'          => __( 'Add New Item', 'custom_admin_table' ),
			'add_new'               => __( 'Add New', 'custom_admin_table' ),
			'new_item'              => __( 'New Item', 'custom_admin_table' ),
			'edit_item'             => __( 'Edit Item', 'custom_admin_table' ),
			'update_item'           => __( 'Update Item', 'custom_admin_table' ),
			'view_item'             => __( 'View Item', 'custom_admin_table' ),
			'view_items'            => __( 'View Items', 'custom_admin_table' ),
			'search_items'          => __( 'Search Item', 'custom_admin_table' ),
			'not_found'             => __( 'Not found', 'custom_admin_table' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'custom_admin_table' ),
			'featured_image'        => __( 'Featured Image', 'custom_admin_table' ),
			'set_featured_image'    => __( 'Set featured image', 'custom_admin_table' ),
			'remove_featured_image' => __( 'Remove featured image', 'custom_admin_table' ),
			'use_featured_image'    => __( 'Use as featured image', 'custom_admin_table' ),
			'insert_into_item'      => __( 'Insert into item', 'custom_admin_table' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'custom_admin_table' ),
			'items_list'            => __( 'Items list', 'custom_admin_table' ),
			'items_list_navigation' => __( 'Items list navigation', 'custom_admin_table' ),
			'filter_items_list'     => __( 'Filter items list', 'custom_admin_table' ),
		);
		$args = array(
			'label'                 => __( 'admin_table', 'custom_admin_table' ),
			'description'           => __( 'Post Type Description', 'custom_admin_table' ),
			'labels'                => $labels,
			'supports'              => array( 'title','custom-fields'),
			'taxonomies'            => array( 'admin_table', 'custom_post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'admin_table', $args );
	
	}
	add_action( 'init', 'custom_post_type_admin_table', 0 );
	
	}



 



// THE AJAX ADD ACTIONS
		add_action( 'wp_ajax_set_form', 'set_forms' );    //execute when wp logged in
		add_action( 'wp_ajax_nopriv_set_form', 'set_forms'); //execute when logged out

		function set_forms(){
		    if('POST' == $_SERVER['REQUEST_METHOD']){

                
						$name= $_POST['name'];    
						$mail= $_POST['mail'];
						$psw= md5($_POST['psw']);

						//custom post type store form data
						$my_post=array(
							'post_type'=>'admin_table',
							'post_status'=>'publish',
							'post_title'=> $name
						);
						$post_id=wp_insert_post($my_post);
					

						//update_field('name',$name,$post_id);
						update_post_meta($post_id,'email',sanitize_email($mail));
						update_post_meta($post_id,'psw',$psw);
						
						
                        
						die;
                

			}

			exit();  //remove 0 from ajax process data   tip: must inclue exit(); end of function	    
		}

?>
