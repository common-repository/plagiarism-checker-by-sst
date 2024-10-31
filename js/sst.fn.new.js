jQuery(function($){
// Configrations
    var remoteUrl = 'https://smallseotools.com/';
// SEO Scores
    var titlePoints = 5;
    var slugPoints = 5;
    var desPoints = 10;
    var keywordsPoints = 5;
    var wordsPointsLow = 10;
    var wordsPointsMed = 15;
    var wordsPointsHigh = 20;
    var ratioPoints = 5;
    var h1Points = 10;
    var h2Points = 5;
    var h3Points = 3;
    var h4Points = 2;
    var intLinksPoints = 5;
    var extLinksPointsLow = 5;
    var extLinksPointsHigh = 10;
    var imgsPoints = 5;
    var brokenLinksPoints = 10;
// Animation Bar Pointing
    var totalbarval = 0;
    var totalYellowbarval = 0;
    var totalRedbarval = 0;
    var animationRunning = 0;
    var activeActions = 0;
    var actionsComplete = 0;
    var improvements = new Array();
// Configations & Data
    var postText = '';
    var postContent = '';
    var previewHTML = '';
    var accKey = '';
    var accountOK = 1;
    function sst_start_checking_main() {
        addAnalyzeBtn();
        accKey = $("#sstMainAccKey").html();
        $("#ddImpBtn").click(function(){
            $(".improvements").slideToggle(700);
            $(this).toggleClass("up");
        });

        $("#tabs a").click(function(){

            if(typeof $(this).attr("name") != 'undefined'){
                $(".tabsContent").hide();
                $("#" + $(this).attr("name")).show();
                $("#tabs li").removeClass("tab-current");
                $(this).parent().addClass("tab-current");
            }
        });
    }

    sst_start_checking_main();

    $(document).on('click','.sba_btnCheck',function(e){
        Fclick(e);
    });


    function Fclick(e){
        var plagBtn = $(e);
        if(plagBtn.hasClass("disable")){
            return false;
        }
        $("#sst_main_results").show();
        $("#statusImg").show();
        changeCstatus("Getting data from text editor...");
        $("#contentDetails").show();
        sstcheckSEO(accKey);
    }

    function addAnalyzeBtn(){
        setTimeout(function() {
            var cclas = $(".sba_btnCheck").css('display');
            var CClas = $(".edit-post-sidebar").css('display');
            if(typeof CClas == 'undefined'){
                var CClas = $(".editor-sidebar__panel").css('display');
            }
            if(typeof cclas == 'undefined' && typeof CClas != 'undefined'){

                'use strict';
                const { registerPlugin } = wp.plugins;
                const { __ } = wp.i18n;
                const {  createSlotFill, PanelRow } = wp.components;
                var createHigherOrderComponent = wp.compose.createHigherOrderComponent;

                var exports = {};

                Object.defineProperty(exports, "__esModule", {
                    value: true
                });

                var _createSlotFill = createSlotFill('PluginPostStatusInfo');

                var Fill = _createSlotFill.Fill,
                    Slot = _createSlotFill.Slot;

                var PluginPostStatusInfo = wp.editPost.PluginPostStatusInfo;


                var MyPluginPostStatusInfo = function MyPluginPostStatusInfo() {
                    return React.createElement(
                        PluginPostStatusInfo,
                        {
                            className: "button button-primary button-large sba_btnCheck",
                            ID: 'AnalyzePost'
                        },
                        __("Check Plagiarism")
                    );
                };
                if(typeof wp.plugins.getPlugin('plagiarism-checker-by-sst') == 'undefined'){
                    registerPlugin('plagiarism-checker-by-sst', {
                        render: MyPluginPostStatusInfo
                    });
                }

                //sst_start_checking_main();
            }
        }, 5000);
    }

    function sstcheckSEO(accountKey){

        postContent = get_tinymce_content();
        postText = postContent.replace(/(<([^>]+)>)/ig," ").replace(/\s+/g, " ");
        accKey = accountKey;

        windowScrolling();
        doAction(checkStatus);
        activeTab("contentStatus");
        doAction(checkSEO_step5);
    }
    function checkStatus() {
        activeActions  = 1;
        $("#pluginStatus").html("");
        var key = $("#sstMainAccKey").html();
        var version = $("#sstPluginVersion").html();
        var plugDir = $("#sstpluginDir").html();
        var adminURL = $("#sstAdminURL").html();
        var sstNonceSecurity = $("#sstNonceSecurity").html();
        $.ajax({
            url : adminURL + "post.php",
            type: "post",
            data: {"key": key, "v":version, "sst_check_status": 1 , "sst_nonce_security" : sstNonceSecurity},
            dataType:"JSON",
            success: function(res){
                if(res.status != "ok"){
                    var alertHTML = '<span class="alert alert_' + res.status + '">'+res.msg+'</span>';
                    $("#pluginStatus").html(alertHTML);
                    $(".currentStatus").hide();
                    accountOK = 0;
                } else {
                    accountOK = 1;
                }
                activeActions = 0;
            }
        });

    }
    function checkSEO_step5(){
        if(accountOK == 0){
            return false;
        }
        hideCstatus();
        if(accKey.length < 10)
        {
            return false;
        }
        activeTab("sstplagResult");
        changeCstatus("Checking Post Plagiarism...");

        if(postText.length <= 0){
            var errorHtml = '<span id="contentDetails" style="display:block;"><span id="alerts"><span class="alert alert_error"><b>Alert: </b><strong>To much Shorter sentence length.</strong></span></span><span id="pluginStatus"></span></span>';
            $("#sst_main_results").append(errorHtml);
            $("#contentDetails").hide();
            return false;
        }else{
            break_sentence(postText, accKey);
        }
    }
    function break_sentence(str, accKey) {
        activeActions  = 1;
        $("#pluginStatus").html("");
        var key = $("#sstMainAccKey").html();
        var version = $("#sstPluginVersion").html();
        var adminURL = $("#sstAdminURL").html();
        var sstNonceSecurity = $("#sstNonceSecurity").html();
        $.ajax({
            url : adminURL + "post.php",
            type: "post",
            data: { "s": str , "key": key, "v":version, "sst_b_s": 1 , "sst_nonce_security" : sstNonceSecurity},
            dataType:"JSON",
            success: function(data){
                if(data == 'null'){
                    var errorHtml = '<span id="contentDetails" style="display:block;"><span id="alerts"><span class="alert alert_error"><b>Alert: </b><strong>To much Shorter sentence length.</strong></span></span><span id="pluginStatus"></span></span>';
                    $("#sst_main_results").append(errorHtml);
                    $("#contentDetails").hide();
                }else{
                    var parts1 = (data);
                    sendRequests(parts1, accKey);
                }
                activeActions = 0;
            }
        });
    }
    function activeTab(name){
        $("#tabs li").removeClass("tab-current");
        $(".tabsContent").hide();
        $("a[name='"+name+"']").parent().addClass("tab-current");
        $("#"+name).show();
    }
    function addStatus(type, title, content)
    {
        var html = '<span class="notice ' + type + '"><p>'
            + '<b class="labelN">' + title + ' : </b><br>'
            +  content
            +  '</p></span>';
        $("#contentStatus").append(html);
    }
    function get_tinymce_content(){
        //var oldcontent = wp.data.select( "core/editor" ).getCurrentPost().content;
        //var updatedcontent = wp.data.select( "core/editor" ).getEditedPostContent();
        return wp.data.select( "core/editor" ).getEditedPostContent();
    }
    function getPreviewHtml()
    {
        activeActions  = 1;
        changeCstatus("Getting data from Live Preview...");
        $.ajax({
            url: $("#post-preview").attr("href"),
            async:true,
            success: function(data)
            {
                previewHTML = data;
                activeActions  = 0;
            }
        });
    }
    function changeCstatus(val)
    {
        $("#statusImg").show();
        $("#cStats").html(val);
    }
    function hideCstatus()
    {
        $("#statusImg").hide();
        $("#cStats").html("");
    }
    function showAlert(type, msg)
    {
        html = '<span class="alert alert_' + type + '">'
            + msg
            + '</span>';
        $("#alerts").html(html);
    }
    function windowScrolling()
    {
        $("#sst-meta-box").removeClass("closed");
        var elemOff = $("#sst-meta-box").offset().top;
        elemOff = elemOff-100;
        activeActions = 1;
        $("html, body").animate({ scrollTop: elemOff }, 1000, function(){
            activeActions = 0;
        });

    }
    function doAction(fn){
        var interval = setInterval(function(){
            if(activeActions == 0){
                clearInterval(interval);
                fn();
            }
        },100);
    }
    function get_hostname(url) {
        var m = url.match(/^http:\/\/[^/]+/);
        if(m)
        {
            return m[0];
        }
        var n = url.match(/^https:\/\/[^/]+/);
        if(n)
        {
            return n[0];
        }
        return null;
    }

    function sendRequests(innerText, accKey){

        var jobhash = innerText.hash;
        var jobrecall = innerText.recall;
        var totalcalls = innerText.totalQueries;

        var mainSite = remoteUrl;
        var plagBtn = $(".sba_btnCheck");
        $("#plagResult").show();

        function doneRequests(){
            $("#loadGif").hide();
            plagBtn.removeClass("disable");
            $("#checkStatus").html("<br>COMPLETE<br>");
            $(".currentStatus").hide();
        }

        function w(data)
        {
            var adminURL  = $("#sstAdminURL").html();
            var postId = $("#post_ID").val();
            var sstNonceSecurity = $("#sstNonceSecurity").html();
            $.ajax({
                type: 'POST',
                url : adminURL + "post.php",
                data : {"data" : data, "id": postId, "sst_post_meta" : 1 , "sst_nonce_security" : sstNonceSecurity},
                async:true
            });
        }

        plagBtn.addClass("disable");

        $("#statusImg").hide();

        $(".resultsBars").html("");
        $(".queriesBars").html("");
        $(".resultsBars").hide();
        $("#result-main").show();
        $("#loadGif").show();

        $("#checkStatus").html("Checking:");
        $("#plagResultsTsst").show();

        var values = totalcalls;
        var roundUnique = 0;
        var isPlagOnce = 0;
        var totalChecked = 0;
        $("#alerts").html("");

        function doRequest(index) {
            var plugDir = $("#sstpluginDir").html();
            var adminURL  = $("#sstAdminURL").html();
            var sstNonceSecurity = $("#sstNonceSecurity").html();
            $.ajax({
                type: 'POST',
                url : adminURL + "post.php",
                data : {"query" : index, "key" : accKey, "hash" : jobhash, "sst_check_plag" : 1, "sst_nonce_security" : sstNonceSecurity},
                async:true,
                success: function(response){
                    if(typeof response == "string" && response == "Wrong key provided."){
                        var errorHtml = '<span class="statBox plagSta"><span class="label label_warning">Unable to process this request.</span></span>';
                        $(".resultsBars").append(errorHtml);
                        $(".resultsBars").css("display","block");
                        doneRequests();
                        return false;
                    }
                    if(response.length <= 0 && index+1 <= values){
                        doRequest(index+1);
                    }
                    var resp = JSON.parse(response);
                    if(resp.details[0].unique == "true"){
                        var uniqueQuery = resp.details[0].query;
                        var alertHtml = '<span class="statBox uniqueSta"><span class="txt">'+uniqueQuery+'<b> - Unique</b></span></span>';
                    }else{
                        isPlagOnce = 1;
                        var plagQuery = resp.details[0].query;
                        alertHtml = '<span class="statBox plagSta"><span class="txt">'+plagQuery+ ' <b>- plagiarized</b></span><span class="check"><a class="button button-primary" style="color:#fff;" href="https://www.google.com/search?q=%22'+encodeURI(plagQuery)+'%22" target="_blank">Compare</a></span>';

                        $.each(resp.details[0].webs, function( key1, links){
                            var encdurl = window.btoa(encodeURIComponent(links.url));
                            if($( "div[id='"+encdurl+"']").length < 1){
                                var matchHtml = '<div class="match" url = "'+links.url+'" id="'+encdurl+'" ><a class="title" target="_blank" href="'+links.url+'">'+links.title+'</a><span class="des">'+links.des+'</span><a target="_blank" href="'+links.url+'" class="link">'+links.url+'</a></div>';
                                $(".resultsBars").append(matchHtml);
                            }
                        });
                    }

                    $(".queriesBars").append(alertHtml);
                    totalChecked = (index/values) * 100;
                    var totalRound = financial(totalChecked,0);
                    var roundPlag = 0;
                    roundUnique = 0;
                    $(".uniquePercent").html("0%");
                    $(".plagPercent").html("0%");
                    $("#percentChecked").html(totalRound + "%");
                    if (index+1 <= values) {
                        doRequest(index+1);
                    }else{
                        doneRequests();
                        if(resp.plagPercent != 0){
                            $(".plagPercent").html(resp.plagPercent);
                        }
                        if(resp.uniquePercent != 0){
                            roundUnique = resp.uniquePercent;
                            $("#uniqueCount").html(roundUnique);
                            $("#uniqueBar").animate({"width" : roundUnique+"%"}, 500);
                            if(roundUnique < 40){
                                htmlIn = '<b>Criticle Error Found: </b> Your Content is only <strong class="red">'+roundUnique+'% Unique</strong> this may hurt your page SEO, Try to make it at least <strong class="green">65% Unique</strong>. with unique content. ';
                                showAlert("error", htmlIn);
                            } else if (roundUnique < 60) {
                                htmlIn = '<b>Warning: </b> Your Content is only <strong class="red">'+roundUnique+'% Unique</strong> this may hurt your page SEO, '
                                    + 'Try to make it at least <strong class="green">65% Unique</strong>. '
                                    + 'with unique content. ';
                                showAlert("warning", htmlIn);
                            }
                            $(".uniquePercent").html(roundUnique + "%");
                        }else if(resp.uniquePercent == 0){
                            htmlIn = '<b>Criticle Error Found: </b> Your Content is only <strong class="red">'+roundUnique+'% Unique</strong> this may hurt your page SEO, Try to make it at least <strong class="green">65% Unique</strong>. with unique content. ';
                            showAlert("error", htmlIn);
                        }
                        $("#percentChecked").html("100%");
                        //saveMeta(roundUnique);
                    }
                }
            });
        }
        if(values > 0){
            doRequest(1);
        }
    }
    function financial(n,l) {
        return parseFloat(parseFloat(n).toFixed(l));
    }

    function compareResults()
    {
        $(".ppsCompare").click(function(){
            var idNo = $(this).attr("id").split("-")[1];
            var data = $("#ppscomData-"+idNo).val();
            $("#ppsCompareData").val(data);
            $("#ppsCompareForm").submit();
        });
    }

    $(".btn-switch").click(function(){
        $(".btn-switch").removeClass("btn-switch-active");
        $(this).addClass("btn-switch-active");
        if($(this).hasClass("queriesBtn"))
        {
            $(".queriesBars").show();
            $(".resultsBars").hide();
        } else {
            $(".queriesBars").hide();
            $(".resultsBars").show();
        }
    });
});
