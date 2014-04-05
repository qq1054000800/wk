{include header}
<link href="{SKIN_PATH}/css/top.css" rel="stylesheet">
<script type="text/javascript" src="{SKIN_PATH}/resource/js/jqplugins/slides.min.jquery.js"></script>
<script type="text/javascript" src="{SKIN_PATH}/resource/js/jqplugins/easySlider1.5.js"></script>
<script type="text/javascript" src="{SKIN_PATH}/js/index.js"></script>

<div class="wrapper">
<!-- bannner start -->
<div class="container_24">
    <div class="zhishu"><img src="{SKIN_PATH}/theme/blue/img/style/index1_r11_c8.jpg" alt="火利市" width="120" height="39" />
        <div class="zhisucenter">二月：需求114,934个 人才总数154,934名 成交额 ￥114,934,256元</div>
        <div class="gsggao"></div>
    </div>
    <div class="logo-tit"></div>
</div>
<div class="banner">
    <div class="container_24 clearfix" style=" height: 320px; margin: 0 auto; position: relative;">
        <div id="slides">
            <div class="slides_container">
                <a><img src="{$style_path}/theme/blue/img/style/index1_r16_c2.jpg"></a> <a><img src="{$style_path}/theme/blue/img/style/index1_r16_c2.jpg"></a>
                <a><img src="{$style_path}/theme/blue/img/style/index1_r16_c2.jpg"></a> <a><img src="{$style_path}/theme/blue/img/style/index1_r16_c2.jpg"></a>
            </div>
        </div>
    </div>
</div>
<!-- bannner end -->
<div class="container_24 clearfix">

<div class="grid_24 mb_10 mt_10">
    <div class="con-liuccx2">
        <div class="con-liucbuttoncxline">
            <h2>　求助流程:</h2>
            <div class="zqlc">
                <div class="zqlcredline">发布需求</div>
                <div class="zqlcredlinejt"><img src="{SKIN_PATH}/image/liuc1_2.jpg" width="12" height="32" /></div>
                <div class="zqlcredline2">选择满意的参与者</div>
                <div class="zqlcredlinejt"><img src="{SKIN_PATH}/image/liuc1_3.jpg" alt="火利市" width="16" height="32" /></div>
                <div class="zqlcredline2">冻结诚意金</div>
                <div class="zqlcredlinejt"><img src="{SKIN_PATH}/image/liuc1_3.jpg" alt="火利市" width="16" height="32" /></div>
                <div class="zqlcredline2">进入合作</div>
                <div class="zqlcredlinejt"><img src="{SKIN_PATH}/image/liuc1_3.jpg" alt="火利市" width="16" height="32" /></div>
                <div class="zqlcredline2">验收结算</div>
            </div>
            <h2>　赚钱流程:</h2>
            <div class="zqlc">
                <div class="zqlcredline">发布或参与需求</div>
                <div class="zqlcredlinejt"><img src="{SKIN_PATH}/image/liuc1_2.jpg" alt="火利市" width="12" height="32" /></div>
                <div class="zqlcredline2">达成合作</div>
                <div class="zqlcredlinejt"><img src="{SKIN_PATH}/image/liuc1_3.jpg" alt="火利市" width="16" height="32" /></div>
                <div class="zqlcredline2">冻结诚意金</div>
                <div class="zqlcredlinejt"><img src="{SKIN_PATH}/image/liuc1_3.jpg" alt="火利市" width="16" height="32" /></div>
                <div class="zqlcredline2">进入合作</div>
                <div class="zqlcredlinejt"><img src="{SKIN_PATH}/image/liuc1_3.jpg" alt="火利市" width="16" height="32" /></div>
                <div class="zqlcredline2">验收结算</div>
            </div>
        </div>
        <div class="con-liucbuttoncx2">
            <div class="con-liucbuttomcx2"><a class="prev2" href="javascript:void();">Previous</a></div>
            <div class="con-liucbuttom2cx2"><a class="prev2" href="javascript:void();">Previous</a></div>
        </div>
        <div class="gsggaocentercx2"><h2><a href="{$pre_url}index.php?do=article&view=article_list&art_cat_id={$bulletin_arr[0]['art_cat_id']}" target="_blank">网站公告</a></h2>
            <ul class="gsggaocenter_phcx2">
                {loop $bulletin_arr $k $v}{if $k<4}
                <li><a href="{if $static}
{$pre_url}html/article/info/{$v['art_cat_id']}_{$v['art_id']}.htm
{else}
{$pre_url}index.php?do=article&view=article_info&art_cat_id=$v['art_cat_id']&art_id=$v['art_id']
{/if}"><img src="{SKIN_PATH}/image/ico-d2.jpg" alt="{$v['art_title']}" width="3" height="3" /> {$v['art_title']}</a></li>
                {/if}{/loop}
            </ul>
        </div>
    </div>
</div>
<div class="grid_16 mb_10 m_h">
    <div class="box model yellow">
        <div class="inner">
            <!--案例  start-->
            <div class="case">
                <!--头部 start-->
                <header class="box_header clearfix">
                    <div class="grid_8 alpha omega">
                        <h1 class="box_title">
                            <span>金牌介绍</span>
                        </h1>
                    </div>
                    <div class="btns">
                        <a title="更多信息" class="button" href="index.php?do=case">更多信息?</a>
                    </div>
                </header>
                <!--头部 end--><!--detail内容 start-->
                <article class="box_detail no_bottom clearfix">
                    <!--列表内容 start-->
                    <ul class="small_list  clearfix">
                        <!--头条 start-->                 {loop $golden_member $k $v}
                        <li class="first">
                            <div class="main_img">
                                <a title="{$v[shop_name]}" href="index.php?do=space&amp;member_id={$v[uid]}"> <img alt="{$v[shop_name]}" src="data/avatar/{$v[pic]}" original-title=""> </a>
                            </div>
                            <div class="">
                                <a href="index.php?do=service&amp;sid=5">
                                    <span class="cc00 mr_100"></span>
                                    {$v[shop_name]}</a>
                            </div>
                        </li>
                        {/loop}
                    </ul>
                    <!--列表内容 end-->
                    <div class="clear"></div>
                </article>
                <!--detail内容 end-->
            </div>
            <!--案例 end-->
        </div>
    </div>
</div>


<div class="grid_8 mb_10 m_h">
    <div class="box model rose">
        <div class="inner">
            <!--第一桶金 start-->
            <div class="news">
                <header class="box_header clearfix">
                    <div class="grid_10 alpha omega">
                        <!--标题 start-->
                        <h1 class="box_title">
                            <span>他们从这里获得第一桶金</span>
                        </h1>
                        <!--标题 end-->
                    </div>
                </header>

                <!--detail内容 start-->
                <article id="div_news_1" class="box_detail no_bottom clearfix">
                    <!--列表内容 start-->
                    <ul class="small_list clearfix">
                        {loop $indus_recomm_task $k $v}
                        <li>
                            <!--头条标题 start-->
                            <div class="main_title clearfix">
                                <a title="{$v[task_title]}" href="index.php?do=task&amp;task_id={$v[task_id]}">{$v[task_title]}</a>
                                <span class="date"><!--{eval echo date("Y-m-d",$v[end_time])}--></span>
                            </div>
                        </li>
                        {/loop}
                    </ul>
                    <br />
                    <div class="btns">
                        <a title="更多信息" class="button" href="index.php?do=task_list">更多信息?</a>
                    </div>
                    <!--列表内容 end-->
                </article>
                <!--detail内容 end-->
            </div>
            <!--第一桶金 end-->
        </div>
    </div>
</div>


<!-- 热门需求start -->
<div class="hot_require">
<div class=" grid_24 title_header clearfix mb_10">
    <h2>找Ta合作</h2>
</div>
<div class="require_detail">
    <div class="grid_8">
        <div class="require_box">
            <div class="require_header">
                <div class="header_van">
                    <a href="#"><img src="tpl/default/img/index/tuli.jpg" class="img_round"></a>
                </div>
                <div class="header_detail">
                    <p><a href="#">他们是在这里获得资金</a></p>
                    <p>我也去发布合作</p>
                </div>
            </div>
            <div class="tops">
                <ul>

                    {loop $money_list $k $v}
                    <li class="clearfix">
                        <div class="item">
                            <div class=" fl_l mr_10">
                                <a title="{$v['company']}" href="index.php?do=space&amp;member_id={$v[uid]}"> <img class="pic_small" uid="{$v[uid]}" src="{$v[licen_pic]}" original-title=""></a>
                            </div>
                            <div class="shoper_info">
                                <ul>
                                    <li>
                                        <a title="{$v['company']}" class="font14" href="index.php?do=space&amp;member_id={$v[uid]}"><strong>
                                                <a href="index.php?do=space&amp;member_id={$v[uid]}">{$v['company']}</a></strong></a>
                                    </li>
                                    <li>
                                        好评率：
                                        <span class="cc00">{if $v['seller_total_num']}<!--{eval echo number_format($v[seller_good_num]*100/$v[seller_total_num],2)}-->{else}0{/if}%</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    {/loop}

                </ul>
            </div>
        </div>
    </div>
    <div class="grid_8">
        <div class="require_box">
            <div class="require_header">
                <div class="header_van">
                    <a href="#"><img src="tpl/default/img/index/tuli.jpg"></a>
                </div>
                <div class="header_detail">
                    <p><a href="#">他们都找他代理</a></p>
                    <p>我也去发布合作</p>
                </div>
            </div>
            <div class="tops">
                <ul>
                    {loop $exchange_list $k $v}
                    <li class="clearfix">
                        <div class="item">
                            <div class=" fl_l mr_10">
                                <a title="{$v['company']}" href="index.php?do=space&amp;member_id={$v[uid]}"> <img class="pic_small" uid="{$v[uid]}" src="{$v[licen_pic]}" original-title=""></a>
                            </div>
                            <div class="shoper_info">
                                <ul>
                                    <li>
                                        <a title="{$v['company']}" class="font14" href="index.php?do=space&amp;member_id={$v[uid]}"><strong>
                                                <a href="index.php?do=space&amp;member_id={$v[uid]}">{$v['company']}</a></strong></a>
                                    </li>
                                    <li>
                                        好评率：
                                        <span class="cc00">{if $v['seller_total_num']}<!--{eval echo number_format($v[seller_good_num]*100/$v[seller_total_num],2)}-->{else}0{/if}%</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    {/loop}
                </ul>
            </div>
        </div>
    </div>
    <div class="grid_8">
        <div class="require_box">
            <div class="require_header">
                <div class="header_van">
                    <a href="#"><img src="tpl/default/img/index/tuli.jpg"></a>
                </div>
                <div class="header_detail">
                    <p><a href="#">他们都找他开户</a></p>
                    <p>我也去积分比赛</p>
                </div>
            </div>
            <div class="tops">
                <ul>
                    {loop $shop_list $k $v}
                    <li class="clearfix">
                        <div class="item">
                            <div class=" fl_l mr_10">
                                <a title="{$v['company']}" href="index.php?do=space&amp;member_id={$v[uid]}"> <img class="pic_small" uid="{$v[uid]}" src="{$v[licen_pic]}" original-title=""></a>
                            </div>
                            <div class="shoper_info">
                                <ul>
                                    <li>
                                        <a title="{$v['company']}" class="font14" href="index.php?do=space&amp;member_id={$v[uid]}"><strong>
                                                <a href="index.php?do=space&amp;member_id={$v[uid]}">{$v['company']}</a></strong></a>
                                    </li>
                                    <li>
                                        好评率：
                                        <span class="cc00">{if $v['seller_total_num']}<!--{eval echo number_format($v[seller_good_num]*100/$v[seller_total_num],2)}-->{else}0{/if}%</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    {/loop}
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="require_detail">
    <div class="grid_8">
        <div class="require_box">
            <div class="require_header">
                <div class="header_van">
                    <a href="#"><img src="tpl/default/img/index/tuli.jpg"></a>
                </div>
                <div class="header_detail">
                    <p><a href="#">他们从这里获得信息</a></p>
                    <p>我也去发布合作</p>
                </div>
            </div>
            <div class="tops">
                <ul>
                    {loop $shop_msg_list $k $v}
                    <li class="clearfix">
                        <div class="item">
                            <div class=" fl_l mr_10">
                                <a title="{$v['company']}" href="index.php?do=space&amp;member_id={$v[uid]}">
                                    <!--{userpic('$v[uid]','small')}-->
                            </div>
                            <div class="shoper_info">
                                <ul>
                                    <li>
                                        <a title="{$v['shop_name']}" class="font14" href="index.php?do=space&amp;member_id={$v[uid]}"><strong>
                                                <a href="index.php?do=space&amp;member_id={$v[uid]}">{$v['shop_name']}</a></strong></a>
                                    </li>
                                    <li>
                                        好评率：
                                        <span class="cc00">{if $v['seller_total_num']}<!--{eval echo number_format($v[seller_good_num]*100/$v[seller_total_num],2)}-->{else}0{/if}%</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    {/loop}
                </ul>
            </div>
        </div>
    </div>
    <div class="grid_8">
        <div class="require_box">
            <div class="require_header">
                <div class="header_van">
                    <a href="#"><img src="tpl/default/img/index/tuli.jpg"></a>
                </div>
                <div class="header_detail">
                    <p><a href="#">他们从这里获得技术</a></p>
                    <p>我也去发布合作</p>
                </div>
            </div>
            <div class="tops">
                <ul>
                    {loop $shop_qjs_list $k $v}
                    <li class="clearfix">
                        <div class="item">
                            <div class=" fl_l mr_10">
                                <a title="{$v['shop_name']}" href="index.php?do=space&amp;member_id={$v[uid]}">
                                    <!--{userpic('$v[uid]','small')}-->
                            </div>
                            <div class="shoper_info">
                                <ul>
                                    <li>
                                        <a title="{$v['shop_name']}" class="font14" href="index.php?do=space&amp;member_id={$v[uid]}"><strong>
                                                <a href="index.php?do=space&amp;member_id={$v[uid]}">{$v['shop_name']}</a></strong></a>
                                    </li>
                                    <li>
                                        好评率：
                                        <span class="cc00">{if $v['seller_total_num']}<!--{eval echo number_format($v[seller_good_num]*100/$v[seller_total_num],2)}-->{else}0{/if}%</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    {/loop}
                </ul>
            </div>
        </div>
    </div>
    <div class="grid_8">
        <div class="require_box">
            <div class="require_header">
                <div class="header_van">
                    <a href="#"><img src="tpl/default/img/index/tuli.jpg"></a>
                </div>
                <div class="header_detail">
                    <p><a href="#">他们从这里获得软件</a></p>
                    <p>我也去积分比赛</p>
                </div>
            </div>
            <div class="tops">
                <ul>
                    {loop $shop_soft_list $k $v}
                    <li class="clearfix">
                        <div class="item">
                            <div class=" fl_l mr_10">
                                <a title="{$v['shop_name']}" href="index.php?do=space&amp;member_id={$v[uid]}">
                                    <!--{userpic('$v[uid]','small')}-->
                            </div>
                            <div class="shoper_info">
                                <ul>
                                    <li>
                                        <a title="{$v['shop_name']}" class="font14" href="index.php?do=space&amp;member_id={$v[uid]}"><strong>
                                                <a href="index.php?do=space&amp;member_id={$v[uid]}">{$v['shop_name']}</a></strong></a>
                                    </li>
                                    <li>
                                        好评率：
                                        <span class="cc00">{if $v['seller_total_num']}<!--{eval echo number_format($v[seller_good_num]*100/$v[seller_total_num],2)}-->{else}0{/if}%</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    {/loop}
                </ul>
            </div>
        </div>
    </div>
</div>
</div>


<!-- 她们在线start -->

<div class="online">
    <div class="title_header">
        <h2></h2>
    </div>
    <div class="online_detail">
        <div class="grid_8">
            <div class="title_top">
                <span>让眼光变成钱</span>
                <span style="float:right;font-size:12px;"><a href="#">>>更多</a></span>
            </div>
            <div class="tops">
                <ul>
                    {loop $msg_list $k $v}
                    <li>
                        <a href="index.php?do=task&task_id={$v[task_id]}">
                            <span class="mr_10 cc00">{c:$v[recognizance]}</span>
                            {$v[title]}</a>
                    </li>
                    {/loop}
                </ul>
            </div>
        </div>
        <div class="grid_8">
            <div class="title_top">
                <span> 让能力变成钱</span>
                <span style="float:right;font-size:12px;"><a href="#">>>更多</a></span>
            </div>
            <div class="tops">
                <ul>
                    {loop $qjs_list $k $v}
                    <li>
                        赏金:<a href="index.php?do=service&sid={$v[service_id]}">
                            <span class="mr_10 cc00">{c:$v[reward_money]}</span>
                            {$v[task_title]}</a>
                    </li>
                    {/loop}
                </ul>
            </div>
        </div>
        <div class="grid_8">
            <div class="title_top">
                <span>生财有道</span>
                <span style="float:right;font-size:12px;"><a href="#">>>更多</a></span>
            </div>
            <div class="tops">
                <ul>
                    {loop $soft_list $k $v}
                    <li>
                        <a href="index.php?do=service&sid={$v[service_id]}">
                            <span class="mr_10 cc00">{c:$v[price]}</span>
                            {$v[title]}</a>
                    </li>
                    {/loop}
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- 新闻资讯start -->
<div class="title_header">
    <h2>新闻资讯
        <span style="float:right;font-size:12px;"><a href="#">>>更多资讯</a></span>
    </h2>
</div>
<div class="index_news">

    <div class="require_detail" style="width:960px;border:1px solid #DBDBDB;">
        <div class="grid_8">
            <div class="require_box">
                <div class="require_box_detail">
                    <ul>
                        {loop $pay_news $k $v} {if $k<6}
                        <li>
                            <a target="_blank" href="{if $static}
                            {$pre_url}html/article/info/{$v['art_cat_id']}_{$v['art_id']}.htm
                            {else}
                            {$pre_url}index.php?do=article&view=article_info&art_cat_id=$v['art_cat_id']&art_id=$v['art_id']
                            {/if}">
                                <span class="font_simsun">&middot;</span>
                                $v[art_title]</a>
                        </li>
                        {/if} {/loop}
                    </ul>
                </div>
            </div>
        </div>
        <div class="grid_8">
            <div class="require_box">
                <div class="require_box_detail">
                    <div class="clear"></div>
                    <ul>
                        {loop $pay_news $k $v} {if $k>6&&$k<13}
                        <li>
                            <a target="_blank" href="{if $static}
                            {$pre_url}html/article/info/{$v['art_cat_id']}_{$v['art_id']}.htm
                            {else}
                            {$pre_url}index.php?do=article&view=article_info&art_cat_id=$v['art_cat_id']&art_id=$v['art_id']
                            {/if}">
                                <span class="font_simsun">&middot;</span>
                                $v[art_title]</a>
                        </li>
                        {/if} {/loop}
                    </ul>
                </div>
            </div>
        </div>

        <div class="grid_8">
            <div class="require_box">
                <div class="require_box_detail">
                    <div class="clear"></div>
                    <ul>
                        {loop $pay_news $k $v} {if $k>14}
                        <li>
                            <a target="_blank" href="{if $static}
                            {$pre_url}html/article/info/{$v['art_cat_id']}_{$v['art_id']}.htm
                            {else}
                            {$pre_url}index.php?do=article&view=article_info&art_cat_id=$v['art_cat_id']&art_id=$v['art_id']
                            {/if}">
                                <span class="font_simsun">&middot;</span>
                                $v[art_title]</a>
                        </li>
                        {/if} {/loop}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 热门需求start -->
<div class="hot_require">
    <div class="title_header">
        <h2>机构推广
            <span style="float:right;font-size:12px;"><a href="#">>>更多机构</a></span>
        </h2>
    </div>
    <div class="require_detail">

        {loop $exchange_list $k $v}
        <div class="grid_8">
            <div class="require_box">
                <div class="require_header">
                    <div class="header_van">
                        <a href="#"><img src="tpl/default/img/index/tuli.jpg"></a>
                    </div>
                    <div class="header_detail">
                        <p><a href="#">{$v['company']}</a></p>
                        <p>实力认证:</p>
                        <p>能力标签:</p>
                    </div>
                </div>
            </div>
        </div>
        {/loop}
    </div>
</div>
<!-- 热门需求end -->

<div class="online">
    <div class="title_header">
        <h2>更多热门服务</h2>
    </div>
    <div class="online_detail">
        <div class="grid_24">
            <div class="title_top">
                <h4>
                    <span><a href="#">股票</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">外汇</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">黄金</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">软件</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">期货</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">现金</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">理财</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">投资</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">开户</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span style="float:right;"><a href="#">>>更多标签</a></span>
                </h4>
            </div>
        </div>
    </div>
    <div class="online_detail">
        <div class="grid_24">
            <div class="title_top">
                <h4>
                    <span><a href="#">操盘手</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">投资客</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">指导师</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">服务师</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">指导师</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><a href="#">服务师</a></span>
                    &nbsp;&nbsp;&nbsp;
                    <span style="float:right;"><a href="#">>>更多标签</a></span>
                </h4>
            </div>
        </div>

    </div>
</div>
<!-- 她们在线end -->
{include footer}