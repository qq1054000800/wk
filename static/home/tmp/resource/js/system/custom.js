
//jQuery

    $(function(){
       // alert(1111);
		$(".top,.scrollTop").click(function(){
			$("html,body").animate({scrollTop: $("#pageTop").offset().top});
		})	
    		
    	//�������۽�
    	$(".togg").focus(function(){
    		$(this).removeClass("c999");
    		if(this.value==L.input_task_service||this.value=='��������'||this.value=='������Ʒ'){
    			this.value='';
			}
    		;
    	}).blur(function(){
    		$(this).addClass("c999");
    			this.value==''?this.value=$(this).attr(this.title?'title':'original-title'):'';
    	})
			$('.operate a,a.prev,a.next,a.small_nav,.border_n a ').not('.nav .operate a').hover(function(){
				$(this).children('.icon16').not('.deep_style .icon16,.deep_style .icon32').addClass("reverse");
				}, function(){
				$(this).children('.icon16').not('.deep_style .icon16,.deep_style .icon32').removeClass("reverse");
			});
			//��������ƶ��¼���ʾ������
			$(".top1,.comment_item").hover(function(){
				$(this).children('.operate').removeClass('hidden');
				
			},function(){
				$(this).children('.operate').addClass('hidden');
			});
		
        //Ϊ����������������ż�в�ͬɫ
        $(".data_list table tbody tr:odd").not('table.jqTransformTextarea tr').addClass("odd");
        //Ϊ�б������ż�в�ͬɫ
        $(".list dd:odd").not('dd.tags').addClass("odd");
        //Ϊ�б����ع�����
        $(".list dd").children('.operate').addClass('hidden');
        
        //Ϊ����������������¼�
        $('.data_list table tbody tr,.list dd,.category_list .item,.case_con').not('.list dd.tags,table.jqTransformTextarea tr').hover(function(){
            $(this).addClass("hover").children('.operate').removeClass('hidden');
        }, function(){
            $(this).removeClass("hover").children('.operate').addClass('hidden');
        });
        
        
        $('.talent_list li').mouseenter(function(){
        	$(this).children('.private').css('visibility','visible');
        }).mouseleave(function(){
        	$(this).children('.private').css('visibility','hidden');
        })
        
        $('#ie6 .login_left').height($('.login_after').height()+100);
        

		/*$(".tar_comment").click(function(event){
			tarClick($(this),event);event.stopPropagation();
		})
		$(".tar_comment").blur(function(event){
			tarBlur($(this),event);event.stopPropagation();
		})
        
    	$(".tar_comment").live("click",function(event){
    		tarClick($(this),event);event.stopPropagation();
    	})
    	$(".tar_comment").live("blur",function(event){
    		tarBlur($(this),event);event.stopPropagation();
    	})
        var tarClick = function(obj,event){
        	if($(obj).val()== L.i_want_say){
        		$(obj).val('').css({height:"50px"}).next().show();
        	}
        	event.stopPropagation();
        }
        var tarBlur = function(obj,event){
        	$("html,body").click(function(event){
        		if(!$(event.target).hasClass("answer-zone")){
        			$(obj).val(L.i_want_say).css({height:"23px"}).next().hide().find(".answer_word").text(L.input_100_words);
        		}
        	})
        }*/
        
        $('.review a').click(function(){
        	$(this).parent().addClass('hidden').next().removeClass('hidden');
        	return false;
        });
    	$("#tar_comment").focus(function(){
			if(this.value=="��Ҫ˵����..."){
				 this.value = ''; 
			 }
		}).blur(function(){
			this.value==''?this.value="��Ҫ˵����...":'';
		})
        var s = $('.messages');
        //msgshow(s);

        // ��Ϣ
        $('.messages .close').click(function() {
        	var s = $(this).parent('.messages');
        	msghide(s);
        });

        // �ر���Ϣ
        function msghide(ele) {
        	ele.animate({
        		opacity : .01
        	}, 200, function() {
        		ele.slideUp(200, function() {
        			ele.remove();
        		});
        	});
        };
	
        // input��
        $(function(){
        	
        	$("input[type=text],input[type=password").addClass("txt_input"),
        	$("this").removeClass("search_input");
        });
     // iconͼ��
        $('.deep_style .icon16,.deep_style .icon32').addClass('reverse');
   //���ض���
        
        $.waypoints.settings.scrollThrottle = 30;
        $('#wrapper').waypoint(function(event, direction){
            $('.top').toggleClass('fadeIn').toggleClass('hidden', direction === "up");
        },{
            offset: '-1%'
        });
        


    });
    
  //�˵��̶�����
    if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style && location.href.indexOf('do=browser') < 0) {
	}
	else {
    
        if ( $(".second_menu").length > 0 ) { 
        	
        	$('.section').waypoint(function(event, direction) {
    			$(this).children('.second_menu').toggleClass('fixed-top', direction === "down");
    			event.stopPropagation();
    		});
        } 
	}
    
var checkall = function(){
    if ($('#checkbox').attr('value') == 0) {
    	$("#checkbox").attr("value",1);
    	$('input[type=checkbox]').attr('checked', true);
    }  else {
    	$("#checkbox").attr("value",0);
        $('input[type=checkbox]').attr('checked', false);
    }

}
     //���select ��Ⱦ
/*	function jq_select(){
	$("#reload_indus div.jqTransformSelectWrapper ul li a").click(function(){
			 $("#indus_id").removeClass("jqTransformHidden").css('display:none');
			 $("select").jqTransSelect().addClass("jqTransformHidden");
		});
	}*/
	
	/**
	 * ��ȡ������ҵ
	 * @param indus_pid
	 */
	function showIndus(indus_pid){
		if(indus_pid){
			$.post("index.php?do=ajax&view=indus",{indus_pid: indus_pid}, function(html){
				var str_data = html;
				if (trim(str_data) == '') {
					$("#indus_id").html('<option value="-1"> '+L.select_a_subsector+' </option>');
				}
				else {
					$("#indus_id").html(str_data);
					$("#reload_indus div.jqTransformSelectWrapper ul li a").triggerHandler("click");
				}
			},'text');
		}
	}

	/**
	 * ��ȡ�󶨵����񼰷�����Ʒ��ҵ
	 * @param indus_pid
	 */
	function showTaskIndus(indus_pid){
		
		if(indus_pid){
			$.post(basic_url,{indus_pid: indus_pid,ajax:'show_indus'}, function(html){
				var str_data = html;
				if (trim(str_data) == '') {
					$("#indus_id").html('<option value="-1"> '+L.select_a_subsector+' </option>');
				}
				else {
					$("#indus_id").html(str_data);
					$("#reload_indus div.jqTransformSelectWrapper ul li a").triggerHandler("click");
				}
			},'text');
		}
	}
/**
 * �����������
 * 
 * @param obj
 *            �������
 * @param ��󳤶�
 */
function checkInner(obj,maxLength,e){
	var  len   = obj.value.length;
		e.keyCode==8?len-=1:len+=1;
		len<0?len=0:'';
	
	var Remain = Math.abs(maxLength-len);
 
	if(maxLength>=len){
       
        $("#length_show").text(L.has_input_length+len+','+L.can_also_input+Remain+L.word);
	}else{
		$("#length_show").text(L.can_input+maxLength+L.word+','+L.has_exceeded_length+Remain+L.word);
	}
}



/**
 * 
 * @param string  form ��ID���߲�������
 * @param int     type �������ͣ�Ϊ����ʱĬ��Ϊ1��Ϊ��ʱΪ2��
 * @param boolean check �Ƿ���֤����Ĭ��Ϊfalse������֤������Ϊtrue 
 */
function formSub(form,type,check){

	var t      = type=='form'?'form':'url';//�������� 1Ϊ�����ͣ���Ϊ����
	var c      = check==true?true:false;//�Ƿ�����֤�� trueΪ��֤,Ĭ��Ϊfalse
	var pass   = true;//Ĭ��Ϊͨ�� ,������֤����ʱΪfalse;
	switch(t){
		case 'url'://����
			var url = form;
			break;
		case 'form'://��
			if(c==true){
				pass = checkForm(document.getElementById(form));
			}
			break;
	}
	
	if(pass==true){
		if(t=='url'){
			showWindow('sitesub',url,'get','0');return false;
		}else{
			showWindow('sitesub',form,'post','0');return false;
		}
	}
}