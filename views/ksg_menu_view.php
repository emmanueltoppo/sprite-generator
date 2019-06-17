<?php
	function ksg_menu_view() {
		require_once __DIR__."/../controller/ksg_main_controller.php";
?>
<div class="ksg_mv_container">
	<div class="ksg_mv_title_container">
		<h1>SPRITE GENERATOR</h1>
	</div>
	<div class="ksg_mv_msg_container">
		<h5></h5>
	</div>
	<div class="ksg_mv_sprite_form_container">
		<form action="" class="ksg_form ksg_mv_sprite_form">
			<button class="ksg_btn ksg_sprite_btn">Test</button>
		</form>
	</div>
</div>
<?php
		fn_ksg_add_asset_view('ksg_menu_view');
	}