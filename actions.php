<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__))
{
die('Direct Access not permitted...');
}
if(!current_user_can('edit_posts'))
{
	if(!check_ajax_referer( 'sst-check-plagiarism-security-nonce', 'sst_nonce_security'))
	{
		wp_die();
	}
	die('Only Admins and Editors are allowed to access these files...');
}
if(!empty($_POST['sst_check_status']))
{
	if(!check_ajax_referer( 'sst-check-plagiarism-security-nonce', 'sst_nonce_security'))
	{
		wp_die();
	}
	include_once('lib/checkstatus.php');
	sst_checkStatus();
}
if(!empty($_POST['sst_check_plag']))
{
	if(!check_ajax_referer( 'sst-check-plagiarism-security-nonce', 'sst_nonce_security'))
	{
		wp_die();
	}
	include_once('lib/checkPlag.php');
	sst_checkPlag();
}
if (!empty($_POST['sst_b_s'])) {
	if(!check_ajax_referer( 'sst-check-plagiarism-security-nonce', 'sst_nonce_security'))
	{
		wp_die();
	}
	include_once('lib/sst_b_s.php');
	sst_b_s();
}
if(!empty($_POST['sst_post_meta']))
{
	if(!check_ajax_referer( 'sst-check-plagiarism-security-nonce', 'sst_nonce_security'))
	{
		wp_die();
	}

	if(!empty($_POST['id']) and !empty($_POST['data']))
	{
		$metaData = sanitize_text_field($_POST['data']).':'.time();
		if ( ! add_post_meta( sanitize_text_field($_POST['id']), 'sst_post_meta', $metaData, true ) ) {
			update_post_meta( sanitize_text_field($_POST['id']), 'sst_post_meta', $metaData );
		}
	}
	exit;
}
