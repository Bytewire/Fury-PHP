<?php

/*
 * CometChat 
 * Copyright (c) 2011 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

include_once dirname(dirname(__FILE__))."/cometchat_init.php";
include_once dirname(dirname(__FILE__))."/license.php";

if (!empty($_GET['userid'])) {
	
	$_SESSION['userid'] = 0;
}

if (!empty($_GET['return'])) {
	header('Location: '.urldecode($_GET['return']));
} else {
	header('Location: '.$_SERVER['HTTP_REFERER']);
}