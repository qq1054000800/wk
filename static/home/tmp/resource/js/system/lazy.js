//�ô˳���ʱ����Ҫjquery�����ʹ�ã���Ϊ�õ�����jquery�Ķ���
//��Ҫ��ʱ���ص�ͼƬҪ�������壺<img name="lazyImg" lazy_src="<%=photourl%>" width="110" height="83" border="0" alt="<%=trmNm%>">
var allImgObjs = new Array();
var times = 400;//���������߶ȵ�ʱ����
var myTimer =null;
var myTop = -1;
var currentTop = 0;
var allImgObjs;

//������Ĵ���ע�͵�������Ϊ��վ����ײ���js��Ҫ��ʱ���أ����������Ĵ��룬����Ҫ�ڵײ���ʱ���صĴ��������Ż����ͼƬ��
//������Ĵ����װ������ĺ���������������ط�����
function loadPics(){
	allImgObjs = $("img[name='lazyImg']");	
	LazyImg();
}

function LazyImg()
{
	var scrollsTop =GetScrollTop();//������ҳ������
	var winTop = $(window).height();//��ǰ���ڸ߶ȡ�
	currentTop = scrollsTop-(-winTop);//������λ�ø߶�
	if(myTop != currentTop )
	{
		clearInterval(myTimer);		
		myTop = currentTop;//��ǰλ��
		for(var i=0;i<allImgObjs.length;i++)
		{
			if(currentTop > $(allImgObjs[i]).offset().top)//����ͼƬ����λ��
			{
				if($(allImgObjs[i]).attr("src") == null || $(allImgObjs[i]).attr("src").length < 1)
				{
					$(allImgObjs[i]).attr("src",$(allImgObjs[i]).attr("lazy_src"));
//��"?"+new Date()��ȡ��������Ӹ����������Ҫ�ǿ���ͼƬÿ�ο��Ա�֤�ӷ��������������µģ������������Ĵ����������ÿ�ζ��Ǵӷ�����������ͼƬ�������Ƕ�����
//					$(allImgObjs[i]).attr("src",$(allImgObjs[i]).attr("lazy_src"));
				}				
			}			
		}
		myTimer = setInterval(function(){LazyImg()},times);
	}
	else
	{
		clearInterval(myTimer);
		myTimer = setInterval(function(){LazyImg()},times);
	}
}
function IeTrueBody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body;
}
function GetScrollTop(){
  return $.browser.msie? IeTrueBody().scrollTop : window.pageYOffset;
}