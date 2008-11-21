<?php
//
// Magic quotes FIX
//

function fix_magic_quotes() {
    if ( get_magic_quotes_gpc() ) {
        $_GET     = stripslashes_deep($_GET);
        $_POST    = stripslashes_deep($_POST);
        $_COOKIE  = stripslashes_deep($_COOKIE);
        $_REQUEST = stripslashes_deep($_REQUEST);
        $_SESSION = stripslashes_deep($_SESSION);
        $_SERVER  = stripslashes_deep($_SERVER);
        $_FILES   = stripslashes_deep($_FILES);
        $_ENV     = stripslashes_deep($_ENV);
    }
}

function stripslashes_deep($value)  {
    if( is_array($value) ) {
        $value = array_map('stripslashes_deep', $value) ;
    } elseif ( !empty($value) && is_string($value) ) {
        $value = stripslashes($value);
    }
    return $value;
}

fix_magic_quotes();
?>