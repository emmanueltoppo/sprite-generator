<?php
/*
Plugin Name: Knapfoco Sprite Generator
Description: A plugin to generate sprite image out of multiple icons and reduce requests to the server.
Version: 1.0.0
Author: Knapfoco Creative Studios
Author URI: http://knapfoco.com
Text Recipe: Sprite Generator
*/
if( !function_exists('add_action')) {
	echo " Not Allowed !!!";
	exit();
}

// Setup
// Includes
include_once "views/ksg_menu_view.php";
// include( 'includes/activate.php');
// Hooks

register_activation_hook( __FILE__, "sg_activate_plugin" );

add_action('admin_menu', 'ksg_plugin_menu'); 

// add_action('wp_enqueue_scripts', '');


// Shortcodes

// Functions

function ksg_plugin_menu () {
       add_menu_page( 'Sprite Generator Admin Panel', 'Knapfoco Sprite Generator', 'manage_options', 'ksg', 'ksg_menu_view' );
}
function test() {
?>
<div class="sprite-home" style="font-family: Roboto Slab;">
	<div class="sprite-title">
		<h1 style="font-size: 30px; text-align: center;">Sprite Generator</h1>
	</div>
	<div class="sprite-msg">
		<?php
			if ( isset($_GET['error_msg']) ){
		?>
			<h5 style="color:red; font-weight: bold; text-align: center; font-size: 18px;">
		<?php
				echo $_GET['error_msg'];
			}
			else if ( isset($_GET['success_msg']) ) {
		?>
			<h5 style="color:green; font-weight: bold; text-align: center; font-size: 18px;">
		<?php
				echo $_GET['success_msg'];
			}
			else {
		?>
			<h5 style="display: none;">
		<?php
			}
		?>
		</h5>
	</div>
	<div class="sprite-form" style="text-align: center; margin-top: 50px;">
		<form action="/wp-content/plugins/knapfoco-sprite-generator/sprite.php" method="post" enctype="multipart/form-data">
			<label style="font-size: 20px;">Select image to upload:</label>
		    <input type="file" name="file" id="fileToUpload">
			<button style="display: block; margin: 0 auto; margin-top: 50px;" name="create_sprite" type="submit">Upload and Create Sprite</button>
		</form>		
	</div>
</div>
<?php
}
