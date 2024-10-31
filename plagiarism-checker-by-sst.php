<?php
/*
  Plugin Name: Plagiarism Checker Wordpress Plugin By SmallSeoTools.com
  Plugin URI: https://smallseotools.com/
  Description: Our Wordpress plugin checks your post before it is published. It checks each and every sentence against billions of sentences already indexed on various search engines. <a href="https://smallseotools.com" target="_blank">View full features list Click Here</a>.
  Version: 2.1.4
  Author: Small Seo Tools
  Author URI: https://smallseotools.com/
  License: GPLv3+
*/
/*
Copyright (C) 2012-2018 Small SEO Tools, smallseotools.com (me AT smallseotools.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
// Prefix: sst
if ( ! defined( 'SST_ACTION_SITE' ) )
    define("SST_ACTION_SITE", "https://smallseotools.com/");
define("SST_ACTION_API_SITE", "https://pro.smallseotools.com/api/");
define("SST_ACTION_PRO_SITE", "https://pro.smallseotools.com/");
if(strlen(@get_option('smallseotools_acckey')) > 10)
    define("SST_APIKEY", get_option('smallseotools_acckey'));
class SST_WP_seo{
    function __construct() {
        add_action( 'admin_menu', array( $this, 'sst_add_menus' ) );
    }
    function  sst_add_menus()
    {
        add_menu_page( 'SST Setting Page', 'SST Setting Page', 'manage_options', 'sst-settings', array(__CLASS__, 'sst_files_path'), plugins_url('imgs/fav.png', __FILE__),'14.6');
    }
    static function sst_files_path()
    {
        include('settingPage.php');
    }
    /*
    * Actions perform on activation of plugin
    */
    static function sst_wpa_install() {
        if(strlen(@get_option('smallseotools_acckey')) < 10)
        {
            @add_option('smallseotools_acckey', "");
            @update_option('smallseotools_acckey', "");
        }
    }
    static function sst_wpa_deactivate(){
        @update_option('smallseotools_acckey', "");
    }
    static function sst_wpa_uninstall(){
        delete_option('smallseotools_acckey');
    }

}
new SST_WP_seo();
add_action( 'admin_menu', 'sst_create_metabox_seo' );
register_activation_hook( __FILE__, array( 'SST_WP_seo', 'sst_wpa_install' ) );
// register_deactivation_hook( __FILE__, array( 'SST_WP_seo', 'sst_wpa_deactivate' ));
register_uninstall_hook( __FILE__, array( 'SST_WP_seo', 'sst_wpa_uninstall' ));

function sst_create_metabox_seo()
{
    $post_types = get_post_types();
    foreach($post_types as $type){
        add_meta_box( 'sst-meta-box', 'Plagiarism Checker by <i>" Smallseotools.com "</i>', 'sst_seobox_design', $type, 'normal', 'high' );
    }
}
function sst_main_actions()
{
    include_once("actions.php");
}
add_action( 'admin_init', 'sst_main_actions' );
add_action('admin_head', 'sst_smallseotools_top');
function sst_wp_admin_style() {
    wp_register_style( 'sst_main_css', plugin_dir_url(__FILE__) . 'sst_style.css', false, '1.5.0' );
    wp_enqueue_style( 'sst_main_css' );
    wp_register_style( 'sst_tabs_css', plugin_dir_url(__FILE__) . 'css/tabstyles.css', false, '1.0.0' );
    wp_enqueue_style( 'sst_tabs_css' );
    wp_register_style( 'sst_setting_css', plugin_dir_url(__FILE__) . 'css/settings.css', false, '1.0.0' );
    wp_enqueue_style( 'sst_setting_css' );
}
add_action( 'admin_enqueue_scripts', 'sst_wp_admin_style' );
function sstNotification()
{
    if(strlen(@get_option('smallseotools_acckey')) < 10)
    {
        $msg = 'Plugin installed successfully, To activate this plugin please enter API KEY in the <a href="'.admin_url().'admin.php?page=sst-settings">Setting Page</a> ';
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e( $msg, 'sample-text-domain' ); ?></p>
        </div>
        <?php
    }
}


function wpse_is_gutenberg_editor() {
    if( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
        return true;
    }

    $current_screen = get_current_screen();
    if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
        return true;
    }
    return false;
}

function sst_smallseotools_top() {
    global $wp_version;
    $isguten = true;
    if(!wpse_is_gutenberg_editor()){
        $isguten = false;
    }
    wp_enqueue_script('jquery');
    wp_enqueue_script( 'sst_stopwords', plugin_dir_url(__FILE__) . 'js/stopwords.js', array('jquery'));

    if($wp_version < 5.0 || !$isguten){
        wp_enqueue_script( 'sst_main_fn', plugin_dir_url(__FILE__) . 'js/sst.fn.old.js?v=1.5', array('jquery'));
    }else{
        wp_enqueue_script( 'sst_main_fn', plugin_dir_url(__FILE__) . 'js/sst.fn.new.js?v=1.5', array('jquery'));
        wp_enqueue_script( 'sst_main_fn_block', plugin_dir_url(__FILE__) . 'js/sst.fn.new.block.js?v=1.5', array('jquery'));
    }

    wp_enqueue_script( 'sst_modernizr', plugin_dir_url(__FILE__) . 'js/modernizr.custom.js', array('jquery'));
    wp_enqueue_script( 'sst_cbpFWTabs', plugin_dir_url(__FILE__) . 'js/cbpFWTabs.js', array('jquery'));
    wp_enqueue_script( 'sst_bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'));
    add_action( 'admin_notices', 'sstNotification' );
}


function sst_seobox_design()
{
    if($_GET['action'] == 'edit' and !empty($_GET['post']))
    {
        $sstPostMeta = get_metadata('post', sanitize_text_field($_GET['post']), "sst_post_meta", true);
        if(!empty($sstPostMeta))
        {
            $sstParts = explode(":", $sstPostMeta);
            $sstLastUnique = @$sstParts[0];
            $sstLastPlag = 100-@$sstParts[0];
            $sstLastDate = date("d M, Y", @$sstParts[1]);
        }
    }
    $sst_nonce_security = wp_create_nonce('sst-check-plagiarism-security-nonce');
    ?>
    <span id="sst_main_results" style="display:block;">
    	<span id="sstpluginDir" style="display:none;"><?php echo plugin_dir_url(__FILE__); ?></span>
        <span id="sstMainAccKey" style="display:none;"><?php echo @SST_APIKEY; ?></span>
        <span id="sstPluginVersion" style="display:none;"><?php echo 1; ?></span>
        <span id="sstAdminURL" style="display:none;"><?php echo get_admin_url(); ?></span>
        <span id="sstNonceSecurity" style="display:none;"><?php echo $sst_nonce_security; ?></span>
        <?php if(!empty($sstPostMeta)): ?>
            <span id="sstLastPlag" style="display:none;"><?php echo @$sstLastPlag; ?></span>
            <span id="sstLastDate" style="display:none;"><?php echo @$sstLastDate; ?></span>
        <?php endif; ?>
        <span id="contentDetails" style="display:block;">
            <span class="currentStatus">
            	<img src="<?php echo plugin_dir_url(__FILE__); ?>imgs/loading-5.gif" id="statusImg" />
            </span>
            <span id="alerts">
            </span>
            <span id="pluginStatus">
            </span>
            </span>
        </span>
    <style>
        .or-box {
            width:49%;
            float:left;
            border:1px solid #D9D9D9;
            background:#F9F9F9;
            padding-bottom:5px;
            text-transform:uppercase;
        }
        .or-box-2 {
            border-left:0px;
        }
        .or-box .percent {
            font-family:Roboto;
            font-size:52px;
            font-weight:300;
            line-height:60px;
        }
        .btn-switch {
            border:1px solid #ccc;
            padding:5px 20px;
            border-bottom:0px;
            margin-bottom:-1px;
            float:left;
            cursor:pointer;
            margin-right:3px;
        }
        .btn-switch:hover {
            background:#F3F3F3;
        }
        .btn-switch-active {
            background:#00659E !important;
            color:#fff !important;
        }
    </style>
    <span id="sstplagResult" class="tabsContent" style="display:block;">
            <table class="resultstable" id="plagResultsTsst" style="display:none;">
                <tr style="width:100%;">
                    <td style="width:400px;" align="center">
                        <span class="or-box" style="width:100%; padding:22px 0;">
                        <strong id="checkStatus" style="display:inline-block; width:170px; text-align:left; text-indent:5px;">Checking:</strong><span id="percentChecked" style="display:inline-block; width:50px; text-align:right;">0%</span> <br>
                        <img src="<?php echo plugin_dir_url(__FILE__); ?>imgs/loading5.gif" id="loadGif" >
                        <span>
                    </td>
                    <td align="center"  style="width:600px;">
                    	<span class="or-box red">
                        	<span class="percent plagPercent">
                            	0%
                            </span>
                            <br>
                            Plagiarized
                        </span>
                        <span class="or-box or-box-2 green">
                        	<span class="percent uniquePercent">
                            	0%
                            </span>
                            <br>
                            Unique
                        </span>
                        <span class="resultBar" style="display:none;">
                            <span class="showBar" id="uniqueBar" style="width:0%;">
                            </span>
                            <span class="showText" id="howUnique"><span id="uniqueCount">0</span>% Unique</span>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                    	(This tool will ignore your current domain when checking plagiarism)
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div style="width:100%; float:left; border-bottom:1px solid #ccc;">
                        	<span class="btn-switch btn-switch-active queriesBtn">
                            	Sentences
                            </span>
                            <span class="btn-switch resultsBtn">
                            	Sources
                            </span>
                        </div>
                        <div class="resultsBars" style="float:left; display:none;">
                        </div>
                        <div class="queriesBars" style="float:left; width:100%; padding-top:10px;">
                        </div>
                    </td>
                </tr>
            </table>
        </span>
    <span id="linksResult" style="display:none;">
        	<span class="sec_heading">Links Status</span>
        </span>
    </span>
    <?php
}