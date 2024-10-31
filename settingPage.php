<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__))
{
    die('Direct Access not permitted...');
}
/*function sst_getPage($url){
    $page = wp_remote_retrieve_body(wp_remote_get($url));
    return $page;
}*/

function sst_userInfo($hash){
    $fields = array(
        "token" => sanitize_text_field($hash)
    );
    $target = SST_ACTION_API_SITE.'info';
    $response = wp_remote_post( $target, array(
            'method' => 'POST' ,
            'timeout' => 20,
            'redirection' => '5',
            'body' => $fields
        )
    );
    return wp_remote_retrieve_body($response);
}


$activation = 0;
$accKey = @get_option('smallseotools_acckey');
if(is_user_logged_in() && !empty($_POST['submit']) and !empty($_POST['account_api'])){
    if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'sst_submit_account_api' ) ) {
        exit;
    }
    $account_api = sanitize_text_field($_POST['account_api']);
    if(strlen(trim($account_api)) < 55)
    {
        $accKey = trim($account_api);
        @update_option('smallseotools_acckey', $accKey);
    }
}

$data = sst_userInfo($accKey);
$user = json_decode($data,true);
if(isset($user['total_words']) and $user['total_words'] > 0) {
    $activation = 1;
    $usename = $user['username'];
    $premium = '<strong class="green">Premium User</strong>';
    $total_words = $user['total_words'];
    $usedwords = $user['used_words'];
    $useremail = $user['email'];
} else {
    if(!empty($_POST['account_api']))
    {
        $errorMsg =  "API key you entered is not valid; <br>";
    }
    elseif(!empty($accKey)){
        $errorMsg =  "Error in validating API Key";
    }
}
?>
    <style>
        .pps-setting-table {
            border-collapse:collapse;
            width:800px;
            background:#fff;
            margin-top:40px;
        }
        .pps-setting-table td {
            padding:10px;
            border:1px solid #ccc;
            box-shadow: 0px 0px 10px 2px #EBEBEB inset;
        }
        .pps-setting-table tr td:first-child {
            font-weight:700;
            color: #156780;
        }
    </style>
<?php if($activation == 1): ?>
    <table id="travel" class="pps-setting-table">
        <tr>
            <td colspan="2" style="background:#EBEBEB; color:#000; font-size:16px; text-align:center;">
                <span style="line-height:30px; text-shadow:1px 1px 1px #fff; margin-right:-70px;">-- Account Details --</span>
                <a href="<?php echo SST_ACTION_PRO_SITE; ?>profile#plagiarism-api" target="_blank" style="float:right;" class="button-secondary button-small">view account</a>
            </td>
        </tr>
        <tr>
            <td width="30%">Name</td>
            <td><?php echo @$usename; ?></td>
        </tr>
        <tr>
            <td>Email Address</td>
            <td><?php echo @$useremail; ?></td>
        </tr>
        <tr>
            <td>API Key</td>
            <td>
                <?php echo @$accKey; ?>
            </td>
        </tr>
        <tr>
            <td>Words Limit</td>
            <td><strong><?php echo @$total_words; ?></strong>
                <a href="<?php echo SST_ACTION_SITE.'wordpress-plagiarism-checker/'; ?>" target="_blank" style="float:right;">+ Add more queries</a></td>
        </tr>
        <tr>
            <td>Words Used</td>
            <td><strong><?php echo $usedwords; ?></strong> <span style="margin-left:20px;color:#9C9B9B;">(<strong><?php echo round(($usedwords/$total_words) * 100); ?>%</strong> words used)</span></td>
        </tr>
        <tr>
            <td>Check Plagiarism?</td>
            <td align="center"><a href="<?php echo SST_ACTION_SITE.'plagiarism-checker/'; ?>" target="_blank" class="button button-primary button-large">Free Plagiarism Checker</a></td>
        </tr>
    </table>
<?php else: ?>
    <?php if ( is_user_logged_in() ) : ?>
        <form method="post" action="">
            <table id="travel2" class="pps-setting-table">
                <tr>
                    <td colspan="2" style="background:#EBEBEB; color:#000; font-size:16px; text-align:center;">
                        <span style="line-height:30px; text-shadow:1px 1px 1px #fff; margin-right:-70px;">- Plugin Setting -</span>
                        <a href="<?php echo SST_ACTION_SITE; ?>login" target="_blank" style="float:right;" class="button-secondary button-small">Create account</a>
                    </td>
                </tr>
                <?php if(empty($accKey)): ?>
                    <tr>
                        <td colspan="2" style="color:#000;">
                            To activate this plugin please <a href="<?php echo SST_ACTION_SITE; ?>login" target="_blank">create an account</a> at smallseotools.com<br>
                            Then Get API key from your <a href="<?php echo SST_ACTION_PRO_SITE; ?>profile#plagiarism-api" target="_blank">account page</a>
                            and paste that API key in the input box below and click on "Save Changes" Button
                            <br>
                            <a href="<?php echo SST_ACTION_SITE; ?>login" target="_blank">click here to get API KEY</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="2" style="color:#000;">
                            Invalid API key is used to integrate Plugin, Please check your <a href="<?php echo SST_ACTION_PRO_SITE; ?>profile#plagiarism-api" target="_blank">smallseotools account</a> page and make sure
                            API key that you entered is correct.
                        </td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td width="30%">Account API key</td>
                    <td>
                        <input type="text" name="account_api" value="<?php echo @$accKey; ?>" style="width:90%; border:1px solid #156780; padding:10px;" />
                        <?php wp_nonce_field( 'sst_submit_account_api' ); ?>

                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" name="submit" value="Save Changes" class="button-primary" />
                    </td>
                </tr>
            </table>
        </form>
        <?php
    endif;
endif;
