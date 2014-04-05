<!--{if !isset($inajax)}-->
<!--<div class="footer">
	<div class="container_24">
	  <div class="footer_header">
	  {if !isset($footer_load) }
	    {$_lang['friend_link']} 
	    {loop $flink $k $v}
       		 <a href="{$v['link_url']}" target="_blank"><span>{$v['link_name']}</span></a>
		{/loop}
	    {/if}
	  </div>
	  <div class="footer_detail">
	  	<p>
	  		<a href="#">关于我们</a>|
	  		<a href="#">联系方式</a>|
	  		<a href="#">广告服务</a>|
	  		<a href="#">新闻中心</a>|
	  		<a href="#">网站地图</a>|
	  		<a href="#">公司资质</a>|
	  		<a href="#">加入我们</a>|
	  		<a href="#">支付方式</a>|
	  	</p>
	  	<p><span class="mr_10">联系方式：13001570965</span> 传真：0539-4272563</p>
	  	<p>Copyright 2005-2013 cps.com 版权所有 <span class="c333">ICP备案号：122020616号-2</span></p>
	  </div>
	</div>
</div>	-->
</div>
<div id="foot-box">

    <div id="bottom-content">
        <div class="botton-content">
            <h6>交易保障 </h6>
            <ul>
                <li><a href="#">担保交易</a></li>
                <li><a href="#">消费保障</a></li>
                <li><a href="#">服务认证</a></li>
                <li><a href="#">管理中心</a></li>
            </ul>

        </div>
        <div class="botton-content">
            <h6>新手入门</h6>
            <ul>
                <li><a href="#">帮助中心</a></li>
                <li><a href="#">规则中心</a></li>
                <li><a href="#">新手上路</a></li>
                <li><a href="#">在线客服</a></li>
            </ul>
        </div>
        <div class="botton-content">
            <h6>VIP会员 </h6>
            <ul>
                <li><a href="#">VIP服务介绍</a></li>
                <li><a href="#">VIP专栏</a></li>
                <li><a href="#">升级为VIP会员</a></li>
            </ul>
        </div>
        <div class="botton-content">
            <h6>特色服务 </h6>
            <ul>
                <li><a href="#">免费开店</a></li>
                <li><a href="#">推广员</a></li>
                <li><a href="#">理财投资攻略</a></li>
            </ul>
        </div>
        <div class="botton-content">
            <h6>关注我们</h6>
            <ul>
                <li><a href="#">新浪微博</a></li>
                <li><a href="#">腾讯微博</a></li>
                <li><a href="#">搜狐微博</a></li>
            </ul>
        </div>
    </div>


    <div class="footcenter">
        <div class="b1"><a href="#" target="_self">关于我们</a> |   <a href="#" target="_self">广告投放</a>   |  <a href="#" target="_self"> 联系我们</a>   |   <a href="#" target="_self">合作伙伴</a>   |   <a href="#" target="_self">网站地图</a>   |   <a href="#" target="_self">友情链接</a>   |   <a href="#" target="_self">免责声明</a>   |   <a href="#" target="_self">网站地图</a></div>
        <h5>联系方式版权所有 ICP备案

        </h5>
    </div>

</div>
{if $uid}
<!--{eval kekezu::update_oltime($uid,$username)}-->
{/if}
<script type="text/javascript">
    var uid='{$uid}';
    var xyq = "<!--{eval echo $xyq = session_id();}-->";
    {if $exec_time_traver}
        $(function(){
            $.get('js.php?op=time&r='+Math.random());
        })
        {/if}


            function notice_pay(url){
                if (isNaN(uid) || uid == 0) {
                    showDialog('此消息及会员可见，您需要登录', 'confirm','消息提示', 'redirect_url()', 0);
                    return false;
                } else {
                    showDialog('想查看信息需要成为缴费会员：个人100元每月  企业300元每月', 'confirm', '消息提示', 'jump("'+url+'");', 0);
                }
            }

            function jump(url){
                window.location.href=url;
            }


            //js异步加载
            In('header_top','custom','lavalamp','tipsy','autoIMG','slides');
</script>
<!--{/if}-->