#常用的js代码集合


###
    @init 初始化参数
    @data 传递值
###
uploadify = (init, data)->
    e = this #全局变量
    uploadify = {}
    uploadify.swf = if init.swf?  then init.swf else "#{__STATIC__}/common/uploadify/uploadify.swf" #swf上传组件
    uploadify.auto = if init.auto? then init.auto else false






