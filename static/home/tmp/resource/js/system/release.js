/*
 *任务发布公有js 
*/

$(function(){
	// 计算任务发布费用
	sum_pay_cost();
	// 支付费用复选框鼠标点击事件
	$("#cb_pay_item_cash,#cb_pay_total_cash").click(function(){
		if( $(this).attr("id") == 'cb_pay_item_cash' && !$("#cb_pay_item_cash").is(":checked")){
			// 附加费用取消或增加事件设置
				//清空增值费用数值
				$("input[item_type=buy]").each(function(){
					$(this).val(0);
					//alert( $(this).attr("item_code") );
					add_payitem($(this),'del',0);
					$("#ago_item_cash").val(0);
					$("#item_cash,#item_count,#ago_item_cash,#pay_item_cash").text(0);
				});
		}
		count_pay_cash();
	});
	
	
	
	$("#qq").click(function(){
		$("#ct_qq").toggle();
	})
	$("#msn").click(function(){
		$("#ct_msn").toggle();
	})
		contact();
	$(":radio[name='contact_type']").click(function(){$(this).attr("checked","checked");contact()});
	pay_type();
	$(":radio[name='pay_type']").click(function(){$(this).attr("checked","checked");pay_type()});
	$(".show_more a").toggle(function(){
		$(this).children('span').removeClass('arrow_b').addClass('arrow_t');
		$("#task_dec").height('auto');
	},function(){
		$(this).children('span').removeClass('arrow_t').addClass('arrow_b');
		$("#task_dec").height('6em');
	});
//	var s = setInterval(function(){
//		
//		if($("#point").val()!=''){
//			
//		}
//	},2000);
	
	 $("input[item_type='use'][id!='payitem_map']").each(function(){
		$("#remain_num_"+$(this).attr("item_code")).text(parseInt($("#remain_num_"+$(this).attr("item_code")).text())-$(this).val());
	})
	
//	contact();
//	$(":radio[name='contact_type']").click(function(){$(this).attr("checked","checked");contact()});
	
	$("#tar_content").blur(function(){
		contentCheck('tar_content',L.t_require,50,1000);
	})
	$(".agreement_link").toggle(function(){
		$(".agreement_part").show();
	},function(){
		$(".agreement_part").hide();
	});
	
	 $(".action_show").toggle(function(){
         $('#help_center').removeClass('hidden');
		   $(this).children('span').removeClass('arrow_b').addClass('arrow_t');
      },function(){
			$('#help_center').addClass('hidden');
			$(this).children('span').removeClass('arrow_t').addClass('arrow_b');
	});
		$("#input_tags").keyup(function(){
			//alert(111);
			var k = $.trim($(this).val());
			//alert(k);
			 k = k.toString();
		
		     k = k.replace(/"/g, "&quot;");
		     k = k.replace(/'/g, "&#39;");
		    //alert(k);
			//alert("index.php?do=ajax&view=ajax&ac=ajax_search_tags&kw="+k);return false;
			if(k){
				var url = encodeURI("index.php?do=ajax&view=ajax&ac=ajax_search_tags&kw="+k);
				//alert(url);return false;
				$.get(url,function(data){
					if(data){
						$("#tags_search_div").show().html(data);
					}
				},'data');
			}else{
				$("#tags_search_div").hide().html('');
			}
		});
})
/**
 * 联系方式清空
 */
function select_this(tag_name){
	    var tags = $("#txt_tags").val();
		$("#show_tags").append('<a href="javascript:void(0)" rel=\''+tag_name+'\'>'+tag_name+'&times;</a>');
		$("#tags_search_div").hide().html('');
		$("#input_tags").val('任务标签');
}


$('#show_tags a').live('click',function(){
	$(this).remove();
});	
function contact(){
	var contact_type = parseInt($(":radio[name='contact_type']:checked").val())+0;
		if(contact_type=='1'){
			$(".lit_form input:[type='text']").removeAttr("ignore").removeAttr("disabled").val('');
		}else{
			$(".lit_form input:[type='text']").each(function(){
				$(this).attr("disabled","disabled").attr("ignore","true").val($(this).attr("ext"));
			})
		}
}
function pay_type(){
	var pay_type = parseInt($(":radio[name='pay_type']:checked").val())+0;
	if(pay_type=='2'){
		$("#txt_code").removeClass('hidden');
	}else if(pay_type=='1'){
		$("#txt_code").addClass('hidden');
		$("#txt_code").val('');
	}
}
/**
 * 获取相应预算范围内的最大天数
 * @param task_cash
 */
function getMaxDday(task_cash){
	if(task_cash){
		$.getJSON(basic_url,{ajax:'getmaxday',task_cash:task_cash},function(json){
			$(".lit_form .pad10 span:last-child").removeClass().text('');
			if(json.status==1){ 	
				 //$("#txt_task_day").attr("limit","required:true;type:date;than:min;less:"+json.msg).val(json.msg);
				 $("#max").val(json.msg); 
				 var min_day = $("#txt_task_day").attr("min_day");
				 title=L.t_allow_min_day+min_day+L.t_allow_max_day+json.data;
				 $("#txt_task_day").attr("title",title); 
				 $("#txt_task_day").attr("max",json.msg); 
				 $("#txt_task_day").attr("msg",title);
			}else
				return false;
			})
	}
}



//显示隐藏使用天数的输入框
function show_payitem_num(obj,item_code){
	
	var item_code = item_code;
	var checked = $(obj).attr("checked");  
	if(checked ==true){ 
		if(item_code=='map'){
			$("#set_map").show(); 
			add_payitem($("#item_map"),'add',1);  
		}else{
			$("#span_"+item_code).show();  
		}
	}else{ 	
		if(item_code=='map'){
			add_payitem($("#item_map"),'del',1);  
			$("#set_map").hide(); 
		}else{
			del_payitem(item_code);//删除增值服务
			$("#span_"+item_code).hide(); 
			$("#payitem_"+item_code).val(""); 
		} 
	} 
}

//计算合作支付金额
function sum_pay_cost(){
	
	//var ago_total = $("#ago_total").val();
	//var procedures_cash = $("#procedures_cash").text().toString();
	//alert(procedures_cash);
	//甲方保证金、
	var txt_g_margin = $("#txt_g_margin").val();
	if(txt_g_margin){
		txt_g_margin = txt_g_margin;
	}else{
		txt_g_margin = 0;
	}
	//乙方保证金
	var txt_w_margin = $("#txt_w_margin").val();
	if(txt_w_margin){
		txt_w_margin = txt_w_margin;
	}else{
		txt_w_margin = 0;
	}
	//金额
	var txt_invest_amount = $("#txt_invest_amount").val();
	if(txt_invest_amount){
		txt_invest_amount = txt_invest_amount;
	}else{
		txt_invest_amount = 0;
	}
	
	//合作时间
	var txt_coop_days = $("#txt_coop_days").val();
	if(txt_coop_days){
		txt_coop_days = txt_coop_days;
	}else{
		txt_coop_days = 0;
	}
	
	//网站手续费基数
	
	var jieti_cash = $("#jieti_cash").val();
	var every_cash = ($("#every_cash").val());
	if( !every_cash){
		every_cash = 0;
	}
	var procedures_cash=0;
	if(txt_coop_days>0&&every_cash>0){
		procedures_cash = txt_coop_days*every_cash*( Math.ceil(txt_g_margin/jieti_cash) );
	}else{
		procedures_cash = 0;
	}
	
	//增值工具费用
	var ago_item_cash = $("#ago_item_cash").val();
	if(ago_item_cash){
		ago_item_cash = ago_item_cash;
	}else{
		ago_item_cash = 0;
	}
	
	var tol_cost=0;
//	if(ago_total){
//		tol_cost = parseFloat(ago_total)+parseFloat(txt_g_margin)+parseFloat(procedures_cash)+parseFloat(ago_item_cash);
//	}else{
//		tol_cost = parseFloat(tol_cost)+parseFloat(txt_g_margin)+parseFloat(procedures_cash)+parseFloat(ago_item_cash);
//	}
	
//	alert(procedures_cash+ago_item_cash);
	tol_cost = parseFloat(txt_g_margin)+parseFloat(procedures_cash);
	
	
	$("#procedures_cash").text(toMoeny(procedures_cash));
	$("#pay_item_cash").text(toMoeny(ago_item_cash));
	$("#total").text(toMoeny(tol_cost));
	$("#pay_other").text(toMoeny(tol_cost));
	$("#lock_cash").text(toMoeny(txt_g_margin));
	$("#ago_total").val(tol_cost);
	$("#ago_lock_cash").val(txt_g_margin);
	$("#ago_web_cash").val(procedures_cash);
	
	// 计算此次支付费用
	count_pay_cash();
}

//计算商品支付金额
function sum_pay_shop_cost(type){
	//消息保证金
	var txt_recognizance = $("#txt_recognizance").val();
	if(txt_recognizance){
		txt_recognizance = txt_recognizance;
	}else{
		txt_recognizance = 0;
	}
	//软件保证金
	var txt_g_recognizance = $("#txt_g_recognizance").val();
	if(txt_g_recognizance){
		txt_g_recognizance = txt_g_recognizance;
	}else{
		txt_g_recognizance = 0;
	}
	
	//增值工具费用
	var ago_item_cash = $("#ago_item_cash").val();
	if(ago_item_cash){
		ago_item_cash = ago_item_cash;
	}else{
		ago_item_cash = 0;
	}
	
	//网站手续费基数
	var service_profit = $("#service_profit").val();
	if(service_profit){
		service_profit = service_profit;
	}else{
		service_profit = 0;
	}
	var procedures_cash=0;
	if(txt_recognizance>0&&service_profit>0){
		procedures_cash = (txt_recognizance*(service_profit/100)).toFixed(2);
	}
	
	var tol_cost=0;
	if(type=="message"){
		tol_cost = parseFloat(txt_recognizance)+parseFloat(ago_item_cash);
	}else if(type=="soft"){
		tol_cost = parseFloat(txt_g_recognizance)+parseFloat(ago_item_cash);
	}
	var total = 0;
	if(type=="message"){
		total = parseFloat(txt_recognizance)+parseFloat(ago_item_cash)+parseFloat(procedures_cash);
	}else if(type=="soft"){
		total = parseFloat(txt_g_recognizance)+parseFloat(ago_item_cash);
	}
	$("#total").text(toMoeny(total));
	$("#pay_other").text(toMoeny(total));
	$("#lock_cash").text(toMoeny(tol_cost));
	$("#ago_total").val(tol_cost);
	$("#ago_lock_cash").val(tol_cost);
	$("#ago_web_cash").val(procedures_cash);
	$("#procedures_cash").text(toMoeny(procedures_cash));
}



//数字转换为金额
function toMoeny(price, chars)
{
	chars = chars ? chars.toString() : '￥';
	if(price > 0)
	{
		var priceString = price.toString();
		var priceInt = parseInt(price);
		var len = priceInt.toString().length;
		var num = len / 3;
		var remainder = len % 3;
		var priceStr = '';
		for(var i = 1; i <= len; i++)
		{
			priceStr += priceString.charAt(i-1);
			if(i == (remainder) && len > remainder) priceStr += ',';
			if((i - remainder) % 3 == 0 && i < len && i > remainder) priceStr += ',';
		}
		if(priceString.indexOf('.') < 0)
		{
			priceStr = priceStr + '.00';
		} else {
			priceStr += priceString.substr(priceString.indexOf('.'));
			if(priceString.length - priceString.indexOf('.') - 1  < 2)
			{
				priceStr = priceStr + '0';
			}
		}
		return chars + priceStr;
	}
	else{
		return chars + price;	
	}
}

//编辑增值服务
function edit_payitem(item_code,model){
//	var item_code = item_code;
//	if($("#payitem_"+item_code).val()){
//		var payitem_num = parseInt($("#payitem_"+item_code).val())+0;
//	}else{
//		var payitem_num = 0;
//	}
//	
//	var item_cash = parseFloat($("#payitem_"+item_code).attr("item_cash"));
//	var item_pay = parseFloat(item_cash*payitem_num);
//	var total_cash = parseInt( $("#ago_total").val());
//	var tol_cost=0;
//	if(total_cash){
//		tol_cost = parseFloat(total_cash)+item_pay;
//	}else{
//		tol_cost = parseFloat(tol_cost)+item_pay;
//	}
//	$("#total").text(toMoeny(tol_cost));
//	$("#ago_total").val(tol_cost);
//	alert(payitem_num);return false;
	//add_payitem($("#payitem_"+item_code),'add',payitem_num); 
	
	var item_code = item_code;
	if($("#payitem_"+item_code).val()){
		var payitem_num = parseInt($("#payitem_"+item_code).val())+0;
	}else{
		var payitem_num = 0;
	}
	
	var item_cash = parseFloat($("#item_cash_"+item_code).val());
	var total_cash = parseInt( $("#ago_total").val()); 
//	alert(payitem_num);return false;
	add_payitem($("#payitem_"+item_code),'add',payitem_num,model); 
}

//删除增值服务
function del_payitem(item_code){
	var item_code = item_code;
	var payitem_num = parseInt($("#payitem_"+item_code).val()); 
	add_payitem($("#checkbox_"+item_code),'del',payitem_num);  
}

/**
 * 检查任务周期
 * @returns {Boolean}
 */
function checkDay(){
	var max_day = parseInt($("#txt_task_day").attr("max"))+0;
	var day     = parseInt($("#txt_task_day").val())      +0;
	
	if(day>max_day){
		$("#span_task_day").html("<span>"+L.t_amount_allowable_period+max_day+L.day+"</span>");
		return false;
	}else
		return true;
}
/**
 * 检测是否同意协议
 */
function checkAgreement(){
	if($("#agreement").attr("checked")==false){
		showDialog(L.t_publishing_agreement,"alert",L.operate_notice);return false;
	}else return true;
}
function stepCheck(model_id){
	//alert(1);return false;
	var i 	 = checkForm(document.getElementById('frm_'+r_step));
	var pass = false;
	switch(r_step){
		case "step1":
			if(checkDay()){
				if(i){
					pass=true;
				}
			}
			break;
		case "step2": 
			if(i){ 
				if(contentCheck('tar_content',L.t_require,50,1000,0,'',editor)&&checkAgreement()){
					pass = true;
				}
				if(model_id==8&&checkAgreement()){
					pass = true;
				}
			}
			break;
		case "step3":
			if($("input[id=payitem_map][item_type=buy]").val()!=0&&$("#payitem_map").attr("item_type")=='buy'&&$.trim($("#point").val())==''){
				showDialog('您没有设置地图','alert','操作提示');return false;
			}else{
				if(i){
					//alert(i);return false;
					$("#frm_"+r_step).submit();
					$("button[name='is_submit']").unbind("click").addClass('disabled');
				}else{
					return false;
				}				
			}
			break;
		case "step4":
			
			break;
	}
	if(pass==true){
	
		check_pub_priv();
	}
}
/**
 * 发布权限检测
 * @returns {Boolean}
 */
function check_pub_priv(){
	
	$.getJSON(basic_url,{ajax:"check_priv"},function(json){
		if(json.status=='1'){
			$("#frm_"+r_step).submit();
		}else{
			
			showDialog(json.data,"alert",json.msg);return false;
		}
	})
}
/**
 * 增值项添加
 * @param obj 当前对象
 * @param action当前动作  add增加/del删除
 */
function add_payitem(obj,action,item_num,model){
	//alert(action);
	//alert(item_num+'+++++');
	//alert(obj,action,item_num);
	var item_id = parseInt($(obj).attr('item_id'))+0;
	var item_cash = parseFloat($(obj).attr('item_cash')*item_num);
	var item_name = $.trim($(obj).val());
	var item_code = $.trim($(obj).attr("item_code"));
	var item_type = $(obj).attr("item_type");
	var exist_num = $(obj).attr("exist_num");
	var total_cash = parseFloat($("#ago_total").val());//总金
	var lock_cash = parseFloat($("#ago_lock_cash").val());//冻结费用
	var web_cash = parseFloat($("#ago_web_cash").val());//网站手续费
	//alert(item_cash);
	//alert(web_cash);
	//alert(lock_cash);
    //return false;
	if(item_code=='map'&&item_type=='buy'){
		if(item_num){
			$("#buy_map").show();
		}else{
			$("#buy_map").hide();
			$("#point").val('');
			$("#hdn_province").val('');
		}
	}
	//alert(item_type);
	//alert(item_num);return false;
	if(item_type=='use'){
		//alert(exist_num);
			var remain = parseInt(exist_num)-item_num;
			$("#remain_num_"+item_code).text(remain);
	}
//	if(item_num=='use'&&item_code=='map'&&$("#point").val()){
//		var remain = parseInt($("#remain_num_"+item_code).text())-item_num;
//		$("#remain_num_"+item_code).text(remain);
//	}
var url=(model==6||model==7)?'index.php?do=shop_release&model_id='+model:'index.php?do=release&model_id='+model;
	switch(action){
		case "add":
                
			$.post(url,
					{ajax:"save_payitem",
					item_id:item_id,
					item_name:item_name,
					item_cash:item_cash,
					item_code:item_code,
					item_num:item_num,
					item_type:item_type,
					lock_cash:lock_cash,
					web_cash:web_cash},function(json){
				$("#pay_total").val(toMoeny((json.msg).replace('￥','').replace('元','')));
				$("#ago_total").val(json.msg);
				$("#item_count").html(json.data.item_count);
				$("#item_cash").html(toMoeny(json.data.item_cash));
				$("#pay_item_cash").html(toMoeny(json.data.item_cash));
				$("#ago_item_cash").val(json.data.item_cash);
				// 计算此次支付费用
				sum_pay_cost();
			},'json');
			break;
		case "del":
			$.post(url,{ajax:"rm_payitem",item_id:item_id},function(json){
				//	$("#total").html((json.msg).replace('￥',''));
				//	$("#pay_total").html(toMoeny((json.msg).replace('￥','')));
					// 计算此次支付费用
					sum_pay_cost();
			},'json');
			break;
	}

}
/**
 * 上传完成后的页面响应
 * @param json json数据
 */
function uploadResponse(json){
	if($("#"+json.fid).length<1){//判断是否已有同样的li、
		var file_ids = $("#file_ids").val();
		if(file_ids){
			$("#file_ids").val(file_ids+','+json.fid)
		}else{	
			$("#file_ids").val(json.fid);
		}
	}
 
}
// 费用支付额度计算
function count_pay_cash(){
	var pay_item_cash;
	var total_cash;
	pay_item_cash = $("#ago_item_cash").val();
	
	if( $("#cb_pay_total_cash").is(":checked")){
		total_cash = $("#ago_total").val();
	}
	
	if( !pay_item_cash ){
		pay_item_cash = 0;
	}
	
	if( !total_cash ){
		total_cash = 0;
	}
	
	var pay_total_cash = parseFloat(pay_item_cash)+parseFloat(total_cash);
	$("#pay_total").val(pay_total_cash.toFixed(2));

	if( pay_item_cash > 0 ){
		$("#cb_pay_item_cash").attr("checked",true);
	}else{
		$("#cb_pay_item_cash").attr("checked",false);
	}
	
	
	if( pay_total_cash > 0 ){
		$("#span_txt_code").removeClass();
	}else{
		$("#span_txt_code").addClass("hidden");
	}
	
	$("#pay_total_cash").text( pay_total_cash.toFixed(2));
}


//附件上传json相应
function setFidToInput(json){
	if(json.msg){
 		if(json.msg.filename == 'upload_fujian') {
			xiaoxi_uploadResponse(json);return false;
		}else{
			att_uploadResponse(json);return false;
		}
	}else{
		var val = $.trim($("#file_ids").val(),',');
			val+=val?',':'',
			$("#file_ids").val(val+json.fid);
	}
}

function att_uploadResponse(json){
	var val = $.trim($("#file_path_2").val(),',');
		val+=val?',':'',
		$("#file_path_2").val(val+json.msg.url);
}
function xiaoxi_uploadResponse(json){
	var val = $.trim($("#file_fujian").val(),',');
		val+=val?',':'',
		$("#file_fujian").val(val+json.msg.url);
}