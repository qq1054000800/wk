

	$(function(){
		
		$(".togg_a").focus(function(){
			//alert($(this).attr('rel'));
			 if(this.value==$(this).attr('rel')){
				 this.value = ''; 
			 }
        }).blur(function(){
        	if(this.value == '' ){
        		this.value = $(this).attr('rel');
        		$("#sub_button").attr("disabled",true);
        	}else{
        		if($("#tar_content").text()&&$("#tar_content").text()!='˵���������   >    ���ڶ�����������   >   ���ۺ�����'&&$("#tar_content").text()!='������ѷ���   >    ���ڶ�����������   >   ���ۺ�����'){
        			$("#sub_button").removeAttr("disabled");
        		}
        	}
                
        })
        	$(".togg_b").focus(function(){
        		 if(this.value==$(this).attr('rel')){
    				 this.value = ''; 
    			 }
        }).blur(function(){
        	if(this.value == '' ){
        		this.value = $(this).attr('rel');
        		$("#sub_button").attr("disabled",true);
        	}else{
        		if($("#txt_title").val()&&$("#txt_title").val()!='������Ʊ�־��ȡ����������վ��д�߻���...'&&$("#txt_title").val()!='��ѷ������...'){
        			$("#sub_button").removeAttr("disabled");
        		}
        	}
        })
		//����ѡ��
	$("#pub_select a.selected").click(function(){
		$(this).nextAll("a").removeClass("hidden");
		
	});
    
	$("#pub_select a").not(".selected").click(function(){
		$("#pub_select .selected").attr("rel",$(this).attr("rel")).children("span").html($(this).html()).end().nextAll("a").addClass("hidden");
		if($(this).attr("rel")=='free_task'){
			$("#txt_title").val("������Ʊ�־��ȡ����������վ��д�߻���...");
			$("#txt_title").attr("rel",'������Ʊ�־��ȡ����������վ��д�߻���...');
			$("#tar_content").val("˵���������   >    ���ڶ�����������   >   ���ۺ�����");
			$("#tar_content").attr("rel",'˵���������   >    ���ڶ�����������   >   ���ۺ�����');
		}else if($(this).attr("rel")=='free_service'){
			$("#txt_title").val("��ѷ������...");
			$("#txt_title").attr("rel",'��ѷ������...');
			$("#tar_content").val("������ѷ���   >    ���ڶ�����������   >   ���ۺ�����");
			$("#tar_content").attr("rel",'������ѷ���   >    ���ڶ�����������   >   ���ۺ�����');
		}
	})
	}) 
/**
 * �����¼�
 * @param json json����
 */
	function checkTitleLen(){
		var t_obj = $("#txt_title");
		var num1 = t_obj.val().length;
		var content_obj = $("#tar_content");
		var num2 = content_obj.text().length;
		if(num1>0&&num2>0&&t_obj.val()!='������Ʊ�־��ȡ����������վ��д�߻���...'&&t_obj.val()!='��ѷ���/��Ʒ����...'&&content_obj.val()!='˵���������   >    ���ڶ�����������   >   ���ۺ�����'&&content_obj.val!='������ѷ���/��Ʒ   >    ���ڶ�����������   >   ���ۺ�����'){
			$("#sub_button").removeAttr("disabled");
		}
		//alert(num);
	}
	/**
	 * ���ύ
	 * @param json json����
	 */
	function freeSub(){
		if(check_user_login()){
			var i       = checkForm(document.getElementById('free_form'));
			if(i){
				var shtml  = contentCheck('tar_content','�������',5,100,1,'upload_tip');
				if(shtml){
					var type      = $("#pub_select .selected").attr("rel");
					$("#pub_type").val(type);
						if($("#txt_title").val()=='������Ʊ�־��ȡ����������վ��д�߻���...'||$("#txt_title").val()=='��ѷ���/��Ʒ����...'){
							$("#txt_title").val('');
							$("#txt_title").focus();
							return false;
						}else if($("#tar_content").val()=='˵���������   >    ���ڶ�����������   >   ���ۺ�����'||$("#tar_content").val()=='������ѷ���/��Ʒ   >    ���ڶ�����������   >   ���ۺ�����'){
							$("#tar_content").val('');
							$("#tar_content").focus();
							return false;
						}else{
							formSub('free_form','form',false);return false;
						}
				}
			}
		}
	}
	$('a#add_file,a#add_pic').click(function(){
		var po_box = $(this).parent('li');
		$(this).addClass('selected').siblings('.add_des').show();
		po_box.css('zIndex','3').siblings().css('zIndex','2')
		po_box.siblings().children('.add_des').hide().siblings('a').removeClass('selected');
	});

	$("body").click(function(){
		$('.core_footer ul li a').removeClass('selected');
		$('.core_footer ul li .add_des').hide();
	});
	$(".core_footer ul li").click(function (e) {
		e.stopPropagation();
	});
	
	/**
 * �ϴ���ɺ��ҳ����Ӧ
 * @param json json����
 */
function uploadResponse(json){
		if(json.msg){
			att_uploadResponse(json);
			return false;
		}else{
			var val = $.trim($("#file_ids").val(),',');
				val+=val?',':'',
				$("#file_ids").val(val+json.fid);
		}
}
//����json��Ӧ
function att_uploadResponse(json){
	var val = $.trim($("#file_path_2").val(),',');
		val+=val?',':'',
	$("#file_path_2").val(val+json.fid);
}
var timer = setInterval(function(){
	//alert(last_id);
	var last_id = $("#last_id").val();
	var url = "index.php?do=square&view=index&t="+t+"&op=get_data&last_id="+last_id;
    $.post(url,{},function(json){
			if(json.data){
				//alert(json.data);
				var ids = json.data;
				
				$("#show_new").removeClass("hidden");
				$("#show_new").html("<a onclick=show_data('"+ids+"') class=block >��"+json.msg+"���¶�̬������鿴</a>");
			}
         
    },"json");
},5000);
function show_data(data){
	//alert(data);
	var url = "index.php?do=ajax&view=menu&t="+t+"&ajax=load_square&ids="+data;
	  if(data){
		  //alert(data);
		  $.post(url,function(text){
			  $("#data_contain").prepend(text);
			  $("#show_new").addClass("hidden");
			  var arr=new Array();
			  arr = data.split(',');
				$("#last_id").val(arr[0]);
		  },'text')
	  }
}




