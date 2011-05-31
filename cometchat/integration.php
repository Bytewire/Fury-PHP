<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* ADVANCED */

define('SET_SESSION_NAME','');			// Session name
define('DO_NOT_START_SESSION','0');		// Set to 1 if you have already started the session
define('DO_NOT_DESTROY_SESSION','0');	// Set to 1 if you do not want to destroy session on logout
define('SWITCH_ENABLED','0');		
define('INCLUDE_JQUERY','1');	
define('FORCE_MAGIC_QUOTES','0');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* DATABASE */

define('DB_SERVER',					'localhost'								);
define('DB_PORT',					'3306'									);
define('DB_USERNAME',				'root'									);
define('DB_PASSWORD',				'root'								);
define('DB_NAME',					'boardwalk'								);
define('TABLE_PREFIX',				''										);
define('DB_USERTABLE',				'characters'									);
define('DB_USERTABLE_NAME',			'username'								);
define('DB_USERTABLE_USERID',		'id'								);
define('DB_USERTABLE_LASTACTIVITY',	'lastonline'							);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* FUNCTIONS */

function getUserID() {
	$userid = 0;
	
	if (!empty($_SESSION['id'])) {
		$userid = $_SESSION['id'];
	}

	return $userid;
}


function getFriendsList($userid,$time) {	

	$sql = ("select c.username,c.id as userid, lastonline as lastactivity, cometchat_status.message, cometchat_status.status, p.avatar,p.profile_link as link from friends_list f INNER JOIN characters c ON f.friendid=c.id LEFT JOIN cometchat_status ON c.id=cometchat_status.userid LEFT JOIN profile p ON c.id=p.playerid where f.playerid='".mysql_real_escape_string($userid)."' order by c.username asc");
	
	/* $sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_LASTACTIVITY." lastactivity, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." avatar, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." link, cometchat_status.message, cometchat_status.status from ".TABLE_PREFIX."friends_list join ".TABLE_PREFIX.DB_USERTABLE." on  ".TABLE_PREFIX."friends_list.toid = ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid where ".TABLE_PREFIX."friends_list.fromid = '".mysql_real_escape_string($userid)."' order by username asc"); */
	return $sql;
}

function getUserDetails($userid) {

	// Fetch the user details
	
	$sql = ("SELECT id AS userid,username,lastonline AS lastactivity, cometchat_status.message, cometchat_status.status FROM characters LEFT JOIN cometchat_status ON characters.id=cometchat_status.userid WHERE characters.id='".mysql_real_escape_string($userid)."'");

/*
	$sql = ("select ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, 
	".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_LASTACTIVITY." lastactivity,  ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." link,
	 ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." avatar,
	 cometchat_status.message, 
	 cometchat_status.status 
	 FROM ".TABLE_PREFIX.DB_USERTABLE." LEFT JOIN cometchat_status ON ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid WHERE ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = '".mysql_real_escape_string($userid)."'");
*/
	return $sql;
}

function updateLastActivity($userid) {
	$sql = ("update `".TABLE_PREFIX.DB_USERTABLE."` set ".DB_USERTABLE_LASTACTIVITY." = '".getTimeStamp()."' where ".DB_USERTABLE_USERID." = '".mysql_real_escape_string($userid)."'");
	return $sql;
}

function getUserStatus($userid) {
	 $sql = ("select cometchat_status.message, cometchat_status.status from cometchat_status where userid = '".mysql_real_escape_string($userid)."'");
	 return $sql;
}

function getLink($link) {
    return 'users.php?id='.$link;
}

function getAvatar($image) {
    if (is_file(dirname(dirname(__FILE__)).'/images/'.$image.'.gif')) {
        return 'images/'.$image.'.gif';
    } else {
        return 'images/noavatar.gif';
    }
}


function getTimeStamp() {
	return time();
}

function processTime($time) {
	return $time;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* HOOKS */

function hooks_statusupdate($userid,$statusmessage) {
	
}

function hooks_forcefriends() {
	
}

function hooks_activityupdate($userid,$status) {

}

function hooks_message($userid,$unsanitizedmessage) {
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* LICENSE */

include_once(dirname(__FILE__).'/license.php');
$x="\x62a\x73\x656\x34\x5fd\x65c\157\144\x65";
eval($x('JHI9ZXhwbG9kZSgnLScsJGxpY2Vuc2VrZXkpOyRwXz0wO2lmKCFlbXB0eSgkclsyXSkpJHBfPWludHZhbChwcmVnX3JlcGxhY2UoIi9bXjAtOV0vIiwnJywkclsyXSkpOw'));

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 