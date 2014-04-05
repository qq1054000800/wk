/**
 * ��Ʒ����js
 */
$(function(){
 
	
	var submit_method = $(":radio[name='submit_method']:checked");
	if(submit_method.val()=='outside'){
		$("#submit_method").hide();
	}
	$(":radio[name='submit_method']").click(function(){
		if($(this).val()=='outside'){
			$("#submit_method").hide();
			$("#kefu_help").show();
			$("#file_btn").show();
			$("#cfile_btn").hide();
		}else{
			$("#submit_method").show();
			$("#kefu_help").hide();
			$("#cfile_btn").show();
			$("#file_btn").hide();
		}
	});
	$(".show_more a").toggle(function(){
		$(this).children('span').removeClass('arrow_b').addClass('arrow_t');
		$("#task_dec").height('auto');
	},function(){
		$(this).children('span').removeClass('arrow_t').addClass('arrow_b');
		$("#task_dec").height('6em');
	});
	
	$(".agreement_link").toggle(function(){
		$("#agreement_info").removeClass('hidden');
	},function(){
		$("#agreement_info").addClass('hidden');
	});
	
	$(".action_show").toggle(function(){
        $('#help_center').removeClass('hidden');
		   $(this).children('span').removeClass('arrow_b').addClass('arrow_t');
     },function(){
			$('#help_center').addClass('hidden');
			$(this).children('span').removeClass('arrow_t').addClass('arrow_b');
	});
})
/**
 * ��ȡ��Ʒ��ҵ
 * @param indus_pid
 */
function showIndus(indus_pid){
	if(indus_pid){
		$.post("index.php?do=ajax&view=indus",{indus_pid: indus_pid}, function(html){
			var str_data = html;
			if (trim(str_data) == '') {
				$("#indus_id").html('<option value="-1"> '+L.select_sub_industry+' </option>');
			}
			else {
				$("#indus_id").html(str_data);
			}
		},'text');
	}
}
function checkAgreement(){
	if($("#agreement").attr("checked")==false){
		showDialog(L.s_publishing_agreement,"alert",L.operate_notice);return false;
	}else return true;
}
function stepCheck(){
	//alert(1111); return false;
	var i 	 = checkForm(document.getElementById('frm_'+r_step));
	var pass = false;
    
	switch(r_step){
//		case "step1":
//					pass=true;
//			break;
		case "step1":
			if(i){
				if(contentCheck('tar_content',L.s_description,5,1000,0,'',editor)&&checkAgreement()){
					pass=true;
				}else{
					pass=false;
				}
			}
			break;
		case "step2":
			//if($("#item_map").attr("checked")==true&&$.trim($("#point").val())==''){
			//set_map();pass=false;
			//alert(111);return false;
			if($("input[id=payitem_map][item_type=buy]").val()!=0&&$("#payitem_map").attr("item_type")=='buy'&&$.trim($("#point").val())==''){
				showDialog('��û�����õ�ͼ','alert','������ʾ');return false;
			}else{
				pass=true;
			}
			
			break;
		case "step3":
			
			break;
	}
	if(pass==true){
		
		$("#frm_"+r_step).submit();
	}
}


/**
 * ��ֵ�����
 * @param obj ��ǰ����
 * @param action��ǰ����  add����/delɾ��
 */
function add_payitem(obj,action,item_num){
//	var item_id = parseInt($(obj).attr('item_id'))+0;
//	var item_cash = parseFloat($(obj).attr('item_cash')*Number(item_num));
//	if(!item_cash){
//		item_cash = 0;
//		} 
//	var item_name = $.trim($(obj).val());
//	var item_code = $.trim($(obj).attr("item_code"));
//	var item_num = item_num;
	var item_id = parseInt($(obj).attr('item_id'))+0;
	var item_cash = parseFloat($(obj).attr('item_cash')*item_num);
	var item_name = $.trim($(obj).val());
	var item_code = $.trim($(obj).attr("item_code"));
	var item_type = $(obj).attr("item_type");
	var exist_num = $(obj).attr("exist_num");
	var total_cash = parseFloat($("#total").text().toString());//�ܽ�
	if(item_code=='map'&&item_type=='buy'){
		if(item_num){
			$("#buy_map").show();
		}else{
			$("#buy_map").hide();
			$("#point").val('');
			$("#hdn_province").val('');
		}
	}
	if(item_type=='use'){
			var remain = parseInt(exist_num)-item_num;
			$("#remain_num_"+item_code).text(remain);
	}
	switch(action){
		case "add":
			$.post(basic_url,{ajax:"save_payitem",item_id:item_id,item_name:item_name,item_cash:item_cash,item_code:item_code,item_num:item_num,item_type:item_type},function(json){
				var a = parseFloat($("#ago_total").val());
				var b = parseFloat(json.data.item_cash);
				
				if(b&&b != NaN&&b !=''&&b !="undefined"){
					var c = a + b ;
				}else{
					c = a;
				}
				$("#total").html("��"+c+"Ԫ");
				$("#item_count").html(json.data.item_count);
				$("#item_cash").html(json.data.item_cash);
			},'json')
			break;
		case "del":
			$.post(basic_url,{ajax:"rm_payitem",item_id:item_id},function(json){
				$("#total").html(json.msg);
			},'json')
			break;
	}
}
/**
 * �ϴ���ɺ��ҳ����Ӧ
 * @param json json����
 */
function uploadResponse(json){
	if(json.msg){
		if(json.msg.filename == 'upload_fujian') {
			xiaoxi_uploadResponse(json);return false;
		}else{
			att_uploadResponse(json);return false;
		}
	}else{
		var val = $.trim($("#file_ids").val(),',');
			val+=val?',':'',
			$("#file_ids").val(val+json.path);
	}
}
//����json��Ӧ
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


//��ʾ����ʹ�������������
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
			del_payitem(item_code);//ɾ����ֵ����
			$("#span_"+item_code).hide(); 
			$("#span_"+item_code).val(""); 
		} 
	} 
}




//�༭��ֵ����
function edit_payitem(item_code){
	var item_code = item_code;
	if($("#payitem_"+item_code).val()){
		var payitem_num = parseInt($("#payitem_"+item_code).val())+0;
	}else{
		var payitem_num = 0;
	}
	var item_cash = parseFloat($("#item_cash_"+item_code).val());
	var total_cash = parseInt( $("#ago_total").val()); 
	//var payitem_num = parseInt($("#payitem_"+item_code).val());
	//var item_cash = Number($("#checkbox_"+item_code).attr("item_cash"));
	//var total_cash = Number( $("#ago_total").val()); 
	add_payitem($("#payitem_"+item_code),'add',payitem_num); 
}

//ɾ����ֵ����
function del_payitem(item_code){
	var item_code = item_code;
	var payitem_num = parseInt($("#payitem_"+item_code).val());
	add_payitem($("#checkbox_"+item_code),'del',payitem_num);  
}