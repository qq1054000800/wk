/**
 * �����̳�js
 */
$(function() {
	$("#leave").click(function() {
		$("html,body").animate({
			scrollTop : $(".lyk").offset().top
		});
	})
	
	$(".arrow-bottom-left,.arrow-top-right").click(function(){
		$("#left_nav").toggleClass("hidden");
		$("#top_nav").toggleClass("hidden");
		setcookie('nav-arrow-'+sid,$(this).attr("id"),3600);
	})
	var nav_arrow = getcookie('nav-arrow-'+sid);
	if(nav_arrow){
		if(nav_arrow=='arrow-bottom-left'){
			$("#top_nav").addClass("hidden");
			$("#left_nav").removeClass("hidden");
		}else if(nav_arrow=='arrow-top-right'){
				$("#left_nav").addClass("hidden");
				$("#top_nav").removeClass("hidden");
			}	
	}
})

/**
 * ���ݼ��
 * @param obj
 * @param event
 */
function checkCommentInner(obj,e){
	var  num   = obj.value.length;
		e.keyCode==8?num-=1:num+=1;
		num<0?num=0:'';
	var Remain = Math.abs(100-num);
		if(num<=100){
			$(obj).next().find(".answer_word").text(L.can_input+Remain+L.word);
		}else{
			var nt = $(obj).val().toString().substr(0,100);
			$(obj).val(nt);	
		}
}
/**
 * �༭
 */
function seEdit(sid){
    if (typeof(sid) == 'undefined' || isNaN(sid)) {
        showDialog("$_lang['none_exists_service']", "alert", "{$_lang['operate_notice']}");
        return false;
    }
    else {
        var url = "index.php?do=user&view=witkey&op=g_pub&model_id="+mid+"&ac=edit&ser_id=" + sid;
		showWindow('service_edit',url,'get',0);return false;
    }
}
/**
 * ����
 */
function service_op(t){
	if(!uid){
		return false;
	}
	showDialog(L.operate_confirm,'confirm',L.operate_notice,function(){
		var url = SITEURL+'/index.php?do=service&sid='+sid+'&view=service_op&t='+t;
		showWindow('service_op',url,'get',0);return false;
	});
}
/**
 * ��Ʒ�µ�
 * @param type
 *            ���� ��������
 * @param sid
 *            ��ƷID
 * @param s_uid
 *            ����uid
 */
function sub_order(type,sid,s_uid){
	if(check_user_login()){
		if(uid==s_uid){
			showDialog(L.s_can_not_buy_your_own+type,"alert",L.operate_notice);return false;
		}else{
			//showDialog(L.s_confirm_to_buy,"confirm",L.operate_notice,"location.href='index.php?do=shop_order&op=confirm&sid="+sid+"'");return false;
			showDialog(L.s_confirm_to_buy,"confirm",L.operate_notice,function(){formSub('index.php?do=shop_order&op=confirm&sid='+sid,'url',false)});return false;
		}
	}
}
var fixSize = function(o){
	var img= $(o);
	var pw = img.parent().width();
	var ph = img.parent().height();
	var mw = img.width();
	var mh = img.height();

	var objcz = mw -mh;
	
		if(objcz<=0){
			img.width('auto').height(ph+'px');
		}else{
			img.width(pw+'px').height('auto');
			var sh = img.height();
			var hcz = ph - sh;
			img.css({'margin-top':hcz/2 + 'px'});
			
		}
}
/* ��Ʒ����ͼ */
window.onload=function(){
	fixSize($('.pro_decs_img img'));
}
/**
 * �л�
 */
var si = 0;
function slide(t){
	var to = $(".pro_decs_img img").length;
	if(to>1){
		switch(t){
			case 'prev':
					if(si==0){
						si=to;
					}
					si--;
					$(".pro_decs_img img:eq("+si+")").removeClass('hidden').siblings().addClass('hidden');
				break;
			case 'next':
				if(si+1==to){
					si=-1;
				}
				si++;
				$(".pro_decs_img img:eq("+si+")").removeClass('hidden').siblings().addClass('hidden');
				break;
		}
		fixSize($(".pro_decs_img img:eq("+si+") img"));
		$('#num').html(si+1);
	}
}