<?php
	
	#ÊTesting falsifying a cron job.
	$_GET["/crons/energyrefill/refill"] = null;

	//$_SERVER["REQUEST_URI"] ="/crons/energyrefill/refill";
	
	require_once("/Applications/MAMP/htdocs/boardwalk/index.php");