<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) 
{ 
die('Direct Access not permitted...'); 
}
function sst_checkPlag()
{
	$key = $_POST['query'];
	$hash = $_POST['hash'];
	$target = SST_ACTION_API_SITE.'query-footprint/'.$hash.'/'.$key.'/true';
	echo wp_remote_retrieve_body(wp_remote_get($target,array('timeout' => 30)));
	exit();
}