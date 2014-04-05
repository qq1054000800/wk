<?php error_reporting(0)?>
<!DOCTYPE HTML>
<!--[if lt IE 7]> <html dir="ltr" lang="zh-cn" id="ie6"> <![endif]-->
<!--[if IE 7]>    <html dir="ltr" lang="zh-cn" id="ie7"> <![endif]-->
<!--[if IE 8]>    <html dir="ltr" lang="zh-cn" id="ie8"> <![endif]-->
<!--[if gt IE 8]><!--> <html dir="ltr" lang="zh-cn"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>{$page_title}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
    <meta name="keywords" content="{$page_keyword}">
    <meta name="description" content="{$page_description}">
    <meta name="generator" content="操盘手" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style” content=black" />
    <meta name="author" content="操盘手" />
    <meta name="copyright" content="操盘手" />
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="apple-touch-icon" href="favicon.ico"/>
    <script type="text/javascript">
        var SITEURL= "{$_K['siteurl']}",
            SKIN_PATH = '{SKIN_PATH}',
            LANG       = '{$language}',
            INDEX      = '{$do}',
            CHARSET    = "{$_K['charset']}";
    </script>
    <?php fb(SKIN_PATH);?>
    <link href="{SKIN_PATH}/resource/css/reset.css" rel="stylesheet" charset="utf-8">
    <!--公用样式-->
    <link href="{SKIN_PATH}/resource/css/base.css" rel="stylesheet" charset="utf-8">
    <!--布局样式-->
    <link rel="stylesheet" media="all" href="{SKIN_PATH}/resource/css/layout/960.min.css" charset="utf-8">
    <!--box样式-->
    <link href="{SKIN_PATH}/resource/css/box.css" rel="stylesheet" charset="utf-8">
    <link href="{SKIN_PATH}/resource/css/animate.css" rel="stylesheet" charset="utf-8">
    <link href="{SKIN_PATH}/css/style.css" rel="stylesheet" charset="utf-8">
    <link href="{SKIN_PATH}/resource/js/jqplugins/tipsy/tipsy.css" rel="stylesheet">
    <link href="{SKIN_PATH}/resource/css/button/stylesheets/css3buttons.css" rel="stylesheet" charset="utf-8">
    <link rel="stylesheet" type="text/css" href="{SKIN_PATH}/resource/js/jGrowl-master/jquery.jgrowl.css"/>
    <!-- <link href="{SKIN_PATH}/css/common.css" rel="stylesheet" charset="utf-8">-->
    <link href="{SKIN_PATH}/theme/{$_K['theme']}/css/nav.css" rel="stylesheet" charset="utf-8">
    <!--[if lt IE 9]>
    <script src="{SKIN_PATH}/resource/js/system/html5.js" type="text/javascript"></script>
    <script src="{SKIN_PATH}/resource/js/system/respond.min.js" type="text/javascript"></script>
    <![endif]-->
    <!--jQuery1.4.4库-->
    <script src="{SKIN_PATH}/resource/js/jquery.js" type="text/javascript"></script>
    <script src="{SKIN_PATH}/resource/js/My97DatePicker/WdatePicker.js" type="text/javascript"></script>
    <script src="lang/{$language}/script/lang.js" type="text/javascript"></script>
    <script src="{SKIN_PATH}/resource/js/system/keke.js" type="text/javascript"></script>
    <script src="{SKIN_PATH}/resource/js/in.js" type="text/javascript"></script>
    <script src="{SKIN_PATH}/resource/js/jGrowl-master/jquery.jgrowl.js"></script>
    <script src="{SKIN_PATH}/resource/js/jqplugins/zclip/jquery.zclip.min.js"></script>
    <script type="text/javascript">
        //js异步加载预定义
        In.add('mouseDelay',{path:"{SKIN_PATH}/resource/js/jqplugins/jQuery.mouseDelay.js",type:'js'});
        In.add('waypoints',{path:"{SKIN_PATH}/resource/js/jqplugins/waypoints/waypoints.min.js",type:'js'});
        In.add('custom',{path:"{SKIN_PATH}/resource/js/system/custom.js",type:'js',rely:['waypoints']});
        In.add('form',{path:"{SKIN_PATH}/resource/js/system/form_and_validation.js",type:'js'});
        In.add('print',{path:"{SKIN_PATH}/resource/js/jqplugins/jquery.print.js",type:'js'});
        In.add('task',{path:"{SKIN_PATH}/resource/js/system/task.js",type:'js'});
        In.add('calendar',{path:"{SKIN_PATH}/resource/js/system/script_calendar.js",type:'js'});
        In.add('xheditor',{path:"{SKIN_PATH}/resource/js/xheditor/xheditor.js",type:'js'});
        In.add('script_city',{path:"{SKIN_PATH}/resource/js/system/script_city.js",type:'js'});
        In.add('lavalamp',{path:"{SKIN_PATH}/resource/js/jqplugins/lavalamp/jquery.lavalamp-1.3.5.min.js",type:'js'});
        In.add('tipsy',{path:"{SKIN_PATH}/resource/js/jqplugins/tipsy/jquery.tipsy.js",type:'js'});
        In.add('autoIMG',{path:"{SKIN_PATH}/resource/js/jqplugins/autoimg/jQuery.autoIMG.min.js",type:'js'});
        In.add('slides',{path:"{SKIN_PATH}/resource/js/jqplugins/slides.min.jquery.js",type:'js'});
        In.add('ajaxfileupload',{path:"{SKIN_PATH}/resource/js/system/ajaxfileupload.js",type:'js'});
        In.add('header_top',{path:"{SKIN_PATH}/resource/js/system/header_top.js",type:'js',rely:['mouseDelay']});
        In.add('lazy',{path:"{SKIN_PATH}/resource/js/system/lazy.js",type:'js'});
        In.add('pcas',{path:"{SKIN_PATH}/resource/js/system/PCASClass.js",type:'js'});
    </script>

</head>
<body id="{$do}">
<!--  <input type="button" onclick="$('#one').jGrowl('Bottom Right Positioning');" value="Bottom Right"/>-->
<div class="{$_K['theme']}_style" id="wrapper">

<div id="append_parent">
</div>
<div id="ajaxwaitid">
    <div>
        <img src="{SKIN_PATH}/theme/{$_K['theme']}/img/system/loading.gif" alt="loading"/>
        {$_lang['request_processing']}
    </div>
</div>

<!--无刷新临时替换层-->
<div id="noflushwarper">
    <div id="noflushwarper_sub"></div>
</div>

<!--body start-->
<!--顶部广告位 start-->
<div class="t_c site_messages">
    <!--{ad_show(GLOBAL_TOP_BANNER)}-->
</div>
<!--顶部广告位-->
<!--头部 start-->
<header class="header" id="pageTop">
    <div class="container_24 clearfix">
        <!--logo start-->
        <hgroup class="logo">
            操盘手
        </hgroup>
        <!--logo end-->
        <!--            <div class=" nav">
                        <ul>
                            <li><a href="index.php" {if !$do||$do=='index'||$do=='login_index'}class="selected"{/if}>首页</a></li>
                            <li><a href="index.php?do=market" {if $do=='market'}class="selected"{/if}>操作中心</a></li>
                            <li><a href="index.php?do=case" {if $do=='case'}class="selected"{/if}>成功案例</a></li>
                            <li><a href="index.php?do=help" {if $do=='help'}class="selected"{/if}>新手帮助</a></li>
                               <li><a href="index.php?do=prom" {if $do=='prom'}class="selected"{/if}>我的推广</a></li>

                        </ul>
                    </div>-->
        <!--主搜索 start-->
        <div class="grid_14">
            <div class="search clearfix po_re" style=" float: right">
                <!--搜索框和选项 start-->
                <div class="search_box">
                    <form action="" method="post" id="frm_search">
                        <input type="text"  class="search_input txt_input text_input" id="search_key">
                        <a href="javascript:void(0)" onclick="topSearch();" id="search_btn" class="search_btn"><span class="icon16 zoom"></span></a>
                        <div class="about-ser" style="display:none;" id="search_div">

                        </div>
                    </form>
                </div>
                <script type="text/javascript">
                    $(function(){
                        $("#search_key").keyup(function(){
                            var k = $.trim($(this).val());
                            k = k.toString();

                            k = k.replace(/"/g, "&quot;");
                            k = k.replace(/'/g, "&#39;");
                            if(k){
                                var url = encodeURI('index.php?do=ajax&view=ajax&ac=ajax_search&kw='+k);
                                //alert(url);
                                $.get(url,function(data){
                                    if(data){
                                        $("#search_div").show().html(data);
                                    }
                                },'data');
                            }else{
                                $("#search_div").hide().html('');
                            }
                        });

                        $("#search_div li p.title").each(function(){

                        }).live('click',function(){
                                location.href = $(this).attr('data-url');

                            });

                    });

                    function show_more_tag(){
                        if($("#show_more").is(':visible')){
                            $("#show_more").slideUp();
                        }else{
                            $("#show_more").slideDown();
                        }
                    }
                </script>
                <!--搜索框和选项 end-->

            </div>

        </div>
        <!-- 用户登录注册 start -->
        <div class="clearfix grid_4 {if $uid||$oauth_user_info}hidden{/if}" style=" float: right">
            <!-- 注册登录按钮 start -->
            <ul  class="user_login ">
                <li>
                    <a href="index.php?do=register">免费注册</a>
                </li>
                <li>|</li>
                <li><a href="index.php?do=login" >登录</a></li>
            </ul>
            <!--  注册登录按钮 end -->
            <div class="clear"></div>
        </div>
        <!-- 用户登录注册 end -->
        <!-- 用户登录之后 start -->
        <div class="login_show {if !$uid&&!$oauth_user_info}hidden{/if}" style=" float: right">
            <div class="acvan">
                <a href="index.php?do=user">
                    <!--{userpic($uid,'small')}-->
                </a>
            </div>
            <div class="show_detail">
                <ul>
                    <li class="login_rel ">

                        <a href="index.php?do=user">
                            {if $username}{$username}{elseif $oauth_user_info}{$oauth_user_info['account']}{/if} <span class="arrow_b"></span>
                        </a>
                        <div class="letter_sub hover">
                            <ul>
                                <li style="float: none; width: 70px; display: block;">
                                    <a href="index.php?do=login_index&view=task" class="deep_red">发布任务</a>
                                </li>
                                <li style="float: none; width: 70px; display: block;">
                                    <a href="index.php?do=login_index&view=shop">发布商品</a>
                                </li>

                            </ul>
                        </div>
                        <!--用户登录后导航菜单 start-->
                        <div id="user_menu" class="user_nav_pop grid_5 alpha omega hidden">
                            <ul class="nav_list clearfix">
                                <li class="clearfix {if $uid ==ADMIN_UID || $user_info['group_id']>0}{else}hidden{/if}" id="manage_center"><a href="control/admin/index.php" title="{$_lang['manage_center']}" ><div class="icon16 key reverse"></div>{$_lang['manage_center']}</a></li>
                                <li class="clearfix"><a href="index.php?do=user&view=index" title="{$_lang['user_center']}"><div class="icon16 cog reverse"></div>{$_lang['user_center']}</a></li>
                                <li class="clearfix">
                                    <a href="<!--{eval echo kekezu::build_space_url($uid);}-->" title="{$_lang['my_space']}" id="space">
                                        <div class="icon16 compass reverse"></div>{$_lang['my_space']}
                                    </a>
                                </li>
                                <!--<li class="clearfix"><a href="index.php?do=user&view=message" title="{$_lang['website_msg']}"><div class="icon16 mail reverse"></div>{$_lang['website_msg']}</a></li>-->
                                <li class="clearfix"><a onclick="showWindow('out','index.php?do=logout');return false;" title="{$_lang['logout']}" href="index.php?do=logout">{$_lang['logout']}</a></li>
                            </ul>
                        </div>
                        <!--用户登录后导航菜单 end-->
                    </li>
                    <li >
                        <a href="index.php?do=user&view=finance" >
                            <span class="yen"></span>
                        </a>
                    </li>
                    <li class="letter_rel ">
                        <a href="index.php?do=user&view=letter">
                            <span class="letter"></span>
                            <span class="arrow_b"></span>
                        </a>
                        <div class="letter_sub hover" style="left:0;">
                            <ul>
                                <li>
                                    <a href="index.php?do=user&view=letter&op=private" class="deep_red">查看聊天记录&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                </li>

                                <li>
                                    <a href="index.php?do=user&view=letter&op=system" class="deep_red">查看系统通知&nbsp;&nbsp;<span id="message_num"></span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="set_rel ">
                        <a href="index.php?do=user&view=setting">
                            <span class="set"></span>
                        </a>
                        <div class="letter_sub hover" style="right: -39px; width: 70px;">
                            <ul>
                                <li style="float: none; width: 70px; display: block;">
                                    <a href="index.php?do=user&view=basic" class=" deep_red">账号设置</a>
                                </li>
                                <li style="float: none; width: 70px; display: block;">
                                    <a href="index.php?do=user&view=payitem">增值服务</a>
                                </li>
                                <li style="float: none; width: 70px; display: block;">
                                    <a href="index.php?do=user&view=store" class="deep_red">我的店铺</a>
                                </li>
                                {if $user_info['user_type'] == 3}<li><a href="index.php?do=user&view=account">在线开户</a></li>{/if}
                                <li style="float: none; width: 70px; display: block;">
                                    <a onclick="showWindow('out','index.php?do=logout');return false;" href="index.php?do=logout">退&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;出</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- 用户登录之后 end -->
    </div>
</header>
<!--头部 end-->
<script type="text/javascript">

    $(function(){
        $(".set_rel,.letter_rel,.login_rel").hover(function(){
            $(this).addClass("hover");
        },function(){
            $(this).removeClass("hover");
        });
        //window.setInterval(function(){getMessage()},1000);
        var uid = {$uid} ;
        if( !!uid ){
            getMessage();
        }
        //window.setInterval(function(){getMessage()},10000);
    });

    function getMessage(uid){
        var url = "index.php?op=get_message&uid="+uid;
        $.post(url,function(json){
            if (json.status == "1") {
                $("#message_num").html("<font size='4' color='blue'>"+json.data[0]+"</font>");
                var content = "<p>您有"+json.data[0]+"条系统消息未查看,<a href='index.php?do=user&view=letter&op=system&type=1'>请查看</a></p>";
                content = content+"<p>您有"+json.data[1]+"条聊天消息未查看,<a href='index.php?do=user&view=letter&op=private&type=1'>请查看</a></p>";
                content = content+"<p>您有"+json.data[2]+"条好友任务消息未查看,<a href='index.php?do=user&view=letter&op=friend&type=1'>请查看</a></p>";
                $.jGrowl(content, {closer: false, position: 'bottom-right', sticky: true,life: 12000 });
                //var num = json.data[0];
                $.jGrowl.defaults.pool = 1;
                //var i = 1;
                //var y = 1;
                //setInterval( function() {
                //if ( i < num ) {

                //$.each( json.data[1], function (w, o) {
                //alert("text:" + o.title + ", value:"  + o.content);
                //$.jGrowl("您有第"+w+"条新消息,请查看"+o.title+":"+o.content, {
                //header:			"您有"+json.data[0]+"条提示消息未查看",
                //sticky:			(i % 3 == 0) ? true : false,
                //position:		'bottom-right',
                //life: 			12000,
                //log: 			function() {
                //console.log("Creating message " + i + "...");
                //},
                //beforeOpen: 	function() {
                //console.log("Rendering message " + y + "...");
                //y++;
                //}
                //});
                //});
                //}
                //i++;
                //} , 1000 );
            } else {
                $("#message_num").html("");
            }
        },'json')
    }
</script>
<!--{if $_K['inajax']}-->
<!--{if !isset($ajaxmenu)}-->
<h3 class="flb"><em>{$title}</em><span><a href="javascript:;" class="flbc" onClick="hideWindow('$handlekey');" title="close">{$_lang['close']}</a></span></h3>
<!--{/if}-->
<!--{else}-->
<!--{template header_top}-->
<!--tool_E-->

<!--   <nav id="nav" class="nav m_h">
        <div class="container_24" >
        	<div class="menu grid_24 clearfix">
                <ul class="clearfix">
                	{loop $nav_arr $k $v}
                   		<li>
                   			<a href="{$v['nav_url']}" {if isset($nav_active_index) && $v['nav_style']==$nav_active_index}class="selected"{/if} {if $v['newwindow']}target="_blank"{/if}>
                   			<span>{$v['nav_title']}</span></a>
						</li>
						<li class="line"></li>
					{/loop}
                </ul>

                  <div class="operate po_ab">
                    	<a href="index.php?do=help" target="_blank" title="{$_lang['help_center']}">
                        	<span class="icon16 help reverse"></span>
							{$_lang['help_center']}
                        </a>
                   </div>

			</div>
                <div class="clear"></div>
        </div>
    </nav>-->

<div id="top-box3">
    <div class="topdhle">
        <div class="li"> <a  href="index.php" {if !$do||$do=='index'||$do=='login_index'}class="selected"{/if}>首页</a></div>
        <div class="line3"><img src="{SKIN_PATH}/image/spacer.gif" width="1" height="1" alt="操盘手" /></div>
        <div class="li"> <a href="index.php?do=task_list" {if $do=='task_list'}class="selected"{/if}>合作大厅</a></div>
        <div class="line3"><img src="{SKIN_PATH}/image/spacer.gif" width="1" height="1" alt="操盘手" /></div>
        <div class="li"> <a href="index.php?do=seller_list" {if $do=='seller_list'}class="selected"{/if}>人才大厅</a></div>
        <div class="line3"><img src="{SKIN_PATH}/image/spacer.gif" width="1" height="1" alt="操盘手" /></div>
        <div class="li"> <a href="index.php?do=shop_list" {if $do=='shop_list'}class="selected"{/if}>商城</a></div>
        <div class="line3"><img src="{SKIN_PATH}/image/spacer.gif" width="1" height="1" alt="操盘手" /></div>
        <div class="li"> <a href="#">代理开户</a></div>
        <div class="line3"><img src="{SKIN_PATH}/image/spacer.gif" width="1" height="1" alt="操盘手" /></div>
        <div class="li"> <a href="index.php?do=task_zxdt" {if $do=='task_zxdt'}class="selected"{/if}>资讯大厅</a></div>
        <div class="line3"><img src="{SKIN_PATH}/image/spacer.gif" width="1" height="1" alt="操盘手" /></div>  <div class="li"> <a href="index.php?do=case" {if $do=='case'}class="selected"{/if}>成功案例</a></div>
        <div class="line3"><img src="{SKIN_PATH}/image/spacer.gif" width="1" height="1" alt="操盘手" /></div>  <div class="li"> <a href="#">软件下载</a></div>
        <div class="line3"><img src="{SKIN_PATH}/image/spacer.gif" width="1" height="1" alt="操盘手" /></div>  <div class="li"> <a href="#">诚信保证</a></div>



    </div>
</div>
<div class="clear"></div>
<!--{/if}-->