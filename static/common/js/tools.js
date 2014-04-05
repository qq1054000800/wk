/**
 * flash 文件上传
 * @param (Object)
 * 		  paramReg 上传基本参数注册
 * @param (Object)
 * 		  contrReg 站内业务参数注册
 */
function uploadify(paramReg,contrReg){
    var paramReg  = paramReg?paramReg:{};
    var contrReg  = contrReg?contrReg:{};
    var uploadify = {};
    var auto 	  = paramReg.auto==true?true:false;//是否自动提交
    var debug     = paramReg.debug==true?true:false;//是否开启debug调试
    var hide      = paramReg.hide==true?true:false;//上传完成后是否隐藏文件域
    var swf  	  = paramReg.swf?paramReg.swf:'resource/js/uploadify/uploadify.swf';//flash路径
    var uploader  = paramReg.uploader?paramReg.uploader:'index.php?do=ajax&view=upload&flash=1';////上传基本路径
    var deleter   = paramReg.deleter?paramReg.deleter:'index.php?do=ajax&view=file&ajax=delete';//文件删除路径
    var file=fname= paramReg.file?paramReg.file:'upload';//file 表单名name=id=upload
    var resText   = paramReg.restext?paramReg.restext:'file_ids';//上传完成后结果保存表单名.name=id=file_ids;
    var size      = paramReg.size;//文件大小限制
    var exts      = paramReg.exts;//文件类型限制
    var method    = paramReg.m?paramReg.m:'post';//上传方式
    var limit     = paramReg.limit?paramReg.limit:1;//上传个数限制
    var qlimit    = paramReg.qlimit?paramReg.qlimit:999;
    var text      = paramReg.text?paramReg.text:L.upload_file;//按钮文字

    var task_id   =	parseInt(contrReg.task_id)+0;
    var work_id   = parseInt(contrReg.work_id)+0;
    var obj_id    = parseInt(contrReg.obj_id)+0;
    var pre       = contrReg.mode=='back'?'../../':'';
    var fileType  = contrReg.fileType?contrReg.fileType:'att';
    var objType   = contrReg.objType?contrReg.objType:'task';
    swf		  = pre+swf;
    deleter   = pre+deleter;
    uploader  = pre+uploader+'&file_name='+file+'&file_type='+fileType+'&obj_type='+objType+'&task_id='+task_id+'&work_id='+work_id+'&obj_id='+obj_id+'&PHPSESSID='+xyq;

    uploadify.auto			  =	auto;
    uploadify.debug			  =	debug;
    uploadify.hide			  =	hide;
    uploadify.swf			  =	swf;
    uploadify.uploader		  = uploader;
    uploadify.deleter 		  = deleter;
    uploadify.fileObjName	  =	file;
    uploadify.resText    	  =	resText;
    uploadify.fileSizeLimit	  =	size;
    uploadify.fileTypeExts	  =	exts;
    uploadify.uploadLimit     = limit;
    uploadify.queueSizeLimit  = qlimit;
    uploadify.method		  = method;
    uploadify.buttonText	  = text;
    uploadify.onUploadSuccess =	function(file,json,response){
        json = eval('('+json+')');
        if(json.err){
            if(msgType==1){
                tipsAppend(showTarget,json.err,'error','red');
            }else{
                showDialog(decodeURI(json.err), 'alert', L.error_tips,'',0);
            }
            return false;
        }else{
            //json.filename  = json.filename;
            typeof(setFidToInput)=='function'&&setFidToInput(json);
            typeof(uploadResponse)=='function'&&uploadResponse(json);//todo:新增的上传文件回调方法 by:wgb
        }
    };
    $("#"+file).uploadify(uploadify);
}