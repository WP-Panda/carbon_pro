<?php
header( "Access-Control-Allow-Origin: *" );
$font_file = preg_replace( '/\\.[^.\\s]{3,4}$/', '', __FILE__ );
$font_ext  = pathinfo( $font_file, PATHINFO_EXTENSION );
header( "Content-Type: application/font-" . $font_ext );
echo file_get_contents( $font_file );