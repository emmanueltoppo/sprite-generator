<?php
	function fn_ksg_add_asset_view( $fileName ) {
		echo "<link href='https://fonts.googleapis.com/css?family=Roboto&display=swap' rel='stylesheet'>";
		echo "<style>". file_get_contents( __DIR__.'/../assets/css/' . $fileName .'.css' ) . "</style>";
		echo "<script>". file_get_contents( __DIR__.'/../assets/js/' . $fileName .'.js' ) . "</script>";
	}