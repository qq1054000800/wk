/*ɾ��cookie*/
function clear_cookie(){
		var url = window.location.href+"&hid_del_cookie=1";
		$.post(url,function (json){
			if(json.status==1){
				$("#history_collect").html("");
			} 
		},'json'); 
}

 /*�洢cookie*/
function save_cookie(){
    var url = window.location.href + "&hid_save_cookie=1";
    $.post(url, function(json){
        if (json.status == 1) {
  
            $("#success").fadeIn(1000);
            $("#success").fadeOut(500);
			var li_length = $("#history_collect").children('li').length;
			if(li_length>=5){
				   $("#history_collect li:first").remove();
			} 
            var html = "<li><a href=" + window.location.href + ">"+json.data+"</a></li>";
           // $("#history_collect").append(html);
        } 
		if(json.status==2){
			$("#success").html(json.msg);	  
			$("#success").fadeIn(1000);
            $("#success").fadeOut(500); 
		} 
    }, 'json');
} 




/* �������� */ 
function search_area(){  
	var province = $("#province").val();
	var city = $("#city").val(); 
	var area = $("#area").val();
	var searck_address ;
	if(province){
		searck_address = province;
	}
	if(city){
		searck_address = searck_address+","+city;
	}
	if(area){
		searck_address = searck_address+","+area;
	}
	local_search(searck_address);
}







//�ͽ�����
function task_cash_reset(cookie_val){
	setcookie(cookie_val, '',-999); 
            $("#cool_search").hide();
            $("#general_search").show();

}


//�ͽ������Զ���
function custom_search_cash(cookie_val){
	setcookie(cookie_val,1); 

    $("#general_search").hide();
    $("#cool_search").show();
  
}

