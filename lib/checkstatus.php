<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) 
{ 
die('Direct Access not permitted...'); 
}
function sst_checkStatus()
{
    $fields = array(
        "token" => sanitize_text_field(@$_POST['key'])
    );
    $target = SST_ACTION_API_SITE.'info';
	$response = wp_remote_post( $target, array(
			'method' => 'POST' ,
			'timeout' => 20,
    		'redirection' => '5',
			'body' => $fields
		)
	);
	$result = json_decode(wp_remote_retrieve_body($response),true);
	$respdata = array();
	if(isset($result['account_status']) and strtolower($result['account_status']) != "active"){
        $respdata['msg'] = "Look's like you have not activated your account, please get api key from ".SST_ACTION_API_SITE."profile and Submit it in the plugin settings page.".'<a href="'.SST_ACTION_PRO_SITE.'profile#plagiarism-api"  target="_blank">Click here to get API KEY</a> <br>'.'<a href="'.SST_ACTION_SITE.'contact-us" target="_blank">contact us</a> for help';
        $respdata['status'] = "error";
    }else{
        $respdata['status'] = "ok";
        //$respdata['msg'] = 'Your account is not correct. Not sure where is the problem? <a href="'.SST_ACTION_SITE.'contact-us" target="_blank">contact us</a> for help';;
    }
    echo json_encode($respdata);
	exit();
}