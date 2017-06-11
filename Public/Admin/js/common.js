/**
 * Created by KU04PC on 2015/5/14.
 */


(function(){

   // tips弹出层
   if ($('a.J_dialog_tips').length) {
       Wind.css('layer',function(){
           Wind.use('layer', function () {
               $('.J_dialog_tips').on('mouseover', function (e) {
                   e.preventDefault();
                   e.stopPropagation();
                   var _this = $(this);
                   var content = _this.data('content');
                   layer.tips(content,_this,{
                       time: 100000
                   });
               });

               $('.J_dialog_tips').on('mouseout',function(){
                   layer.closeAll('tips');
               });
           });
       });
   }

    // 图片放大预层
    if ($('img.J_dialog_preview').length) {
        Wind.css('layer',function(){
            Wind.use('layer', function () {
                $('.J_dialog_preview').on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var _this = $(this);
                    var src = _this.attr('src');
                    layer.open({
                        type: 1,
                        title: false,
                        closeBtn: 0,
                        skin: 'layui-layer-nobg', //没有背景色
                        shadeClose: true,
                        content: '<img src="'+ src +'" style="width: 100%;height: 100%;"/>'
                    });
                });

                $('.J_dialog_tips').on('mouseout',function(){
                    layer.closeAll('tips');
                });
            });
        });
    }

    //dialog弹窗内的关闭方法
    $('#J_dialog_close').on('click', function (e) {
        e.preventDefault();
        if (window.parent.Wind.dialog) {
            window.parent.Wind.dialog.closeAll();
        }
    });

    //所有的删除操作，删除数据后刷新页面
    if ($('a.J_ajax_del').length) {
        Wind.use('dialog', function () {

            $('.J_ajax_del').on('click', function (e) {
                e.preventDefault();
                var $this = $(this), href = $this.prop('href'), msg = $this.data('msg'), pdata = $this.data('pdata');
                if(msg === false){
                    $.ajax({
                        url: href,
                        type: 'post',
                        dataType: 'json',
                        data: function () {
                            if (pdata) {
                                pdata = $.parseJSON(pdata.replace(/'/g, '"'));
                                return pdata
                            }
                        }(),
                        success: function (data) {
                            if (data.state === 'success') {
                                if (data.referer) {
                                    location.href = decodeURIComponent(data.referer);
                                } else {
                                    Wind.dialog.alert(data.message,function(){
                                        reloadPage(window);
                                    });
                                }
                            } else if (data.state === 'fail') {
                                Wind.dialog.alert(data.message);
                            }
                        }
                    });
                }else{
                    var params = {
                        message: msg ? msg : '确定要删除吗？',
                        type: 'confirm',
                        isMask: false,
                        follow: $(this),//跟随触发事件的元素显示
                        onOk: function () {
                            $.ajax({
                                url: href,
                                type: 'post',
                                dataType: 'json',
                                data: function () {
                                    if (pdata) {
                                        pdata = $.parseJSON(pdata.replace(/'/g, '"'));
                                        return pdata
                                    }
                                }(),
                                success: function (data) {
                                    if (data.state === 'success') {
                                        if (data.referer) {
                                            location.href = decodeURIComponent(data.referer);
                                        } else {
                                            Wind.dialog.alert(data.message);
                                            reloadPage(window);
                                        }
                                    } else if (data.state === 'fail') {
                                        Wind.dialog.alert(data.message);
                                    }
                                }
                            });
                        }
                    };
                    Wind.dialog(params);
                }
            });

        });
    }

    //所有加了dialog类名的a链接，自动弹出它的href
    if ($('a.J_dialog').length) {
        Wind.use('dialog', function () {
            $('.J_dialog').on('click', function (e) {
                e.preventDefault();
                var _this = $(this);
                Wind.dialog.open($(this).prop('href'), {
                    onClose: function () {
                        _this.focus();//关闭时让触发弹窗的元素获取焦点
                    },
                    title: _this.prop('title')
                });
            }).attr('role', 'button');

        });
    }

    //tab标签选项卡
    if($(".J_tab").length){
        Wind.use('tabs',function(){
            $(".J_tab ul").idTabs();
        });
    }

    //自动完成
    if($(".J_complete").length){
        Wind.css('autoComplete',function(){
            Wind.use('autoComplete',function(){
                //var countries = [
                //    { value: 'Andorra', data: 'AD' },
                //    { value: 'Zimbabwe', data: 'ZZ' }
                //];
                $(".J_complete").focus(function(){
                    var href = $(this).data('href');
                    $(this).autocomplete({
                        //lookup: countries,
                        serviceUrl:href,
                        onSelect: function (suggestion) {
                            $(".J_complete").trigger('blur');

                            if(typeof complete == 'function'){
                                complete(suggestion);
                            }
                        }
                    });
                });
            });
        });
    }

    //重置按钮
    $('.btn_reset').on('click',function(){
        $('.search_type').find('input,select').filter(function(index){
            if($(this).attr("disabled") == "disabled") return false;
            if($(this).attr("type") != "hidden") return true;
            if($(this).data("reset")) return true;
        }).val('');
        $(this).siblings('button').trigger('click');
    });

    //日期选择器
    var dateInput = $("input.J_date")
    if (dateInput.length) {
        var dateBegin = $("input.J_date_begin")
        var dateEnd = $("input.J_date_end")

        if(dateBegin.val() != undefined && dateEnd.val() != undefined){
            dateBegin.focus(function(){
                if(dateEnd.val() != undefined){
                    Wind.use('datePicker', function () {
                        dateBegin.datePicker();
                    });
                }else{
                    Wind.use('datePicker', function () {
                        dateBegin.datePicker();
                    });
                }
            });

            dateEnd.focus(function(){
                if(dateBegin.val() != undefined){
                    Wind.use('datePicker', function () {
                        dateEnd.datePicker();
                    });
                }else{
                    Wind.use('datePicker', function () {
                        dateEnd.datePicker();
                    });
                }
            });

        }else{
            Wind.use('datePicker', function () {
                dateInput.datePicker();
            });
        }

    }

    //日期+时间选择器
    var dateTimeInput = $("input.J_datetime");
    if (dateTimeInput.length) {
        var dateTimeBegin = $("input.J_datetime_begin")
        var dateTimeEnd = $("input.J_datetime_end")

        if(dateTimeBegin.val() != undefined && dateTimeEnd.val() != undefined){
            dateTimeBegin.focus(function(){
                if(dateTimeEnd.val() != undefined){
                    Wind.use('datePicker', function () {
                        dateTimeBegin.datePicker({'time':true});
                    });
                }else{
                    Wind.use('datePicker', function () {
                        dateTimeBegin.datePicker({'time': true});
                    });
                }
            });

            dateTimeEnd.focus(function(){
                if(dateTimeBegin.val() != undefined){
                    Wind.use('datePicker', function () {
                        dateTimeEnd.datePicker({'time':true});
                    });
                }else{
                    Wind.use('datePicker', function () {
                        dateTimeEnd.datePicker({'time': true});
                    });
                }
            });

            dateTimeInput.each(function(index,item){
                if(!$(item).hasClass('J_datetime_begin') && !$(item).hasClass('J_datetime_end')){
                    Wind.use('datePicker', function () {
                        $(item).datePicker({'time': true});
                    });
                }
            });
        }else{
            Wind.use('datePicker', function () {
                dateTimeInput.datePicker({'time': true});
            });
        }
    }

    //所有的ajax form提交,由于大多业务逻辑都是一样的，故统一处理
    var ajaxForm_list = $('form.J_ajaxForm');
    if (ajaxForm_list.length) {
        Wind.use('dialog', 'ajaxForm', function () {

            if ($.browser.msie) {
                //ie8及以下，表单中只有一个可见的input:text时，会整个页面会跳转提交
                ajaxForm_list.on('submit', function (e) {
                    //表单中只有一个可见的input:text时，enter提交无效
                    e.preventDefault();
                });
            }

            $('button.J_ajax_submit_btn').on('click', function (e) {
                e.preventDefault();
                /*var btn = $(this).find('button.J_ajax_submit_btn'),
                 form = $(this);*/

                var btn = $(this),
                    form = btn.parents('form.J_ajaxForm');


                //批量操作 判断选项
                if (btn.data('subcheck')) {
                    btn.parent().find('span').remove();
                    if (form.find('input.J_check:checked').length) {
                        var msg = btn.data('msg');
                        if (msg) {
                            Wind.dialog({
                                type: 'confirm',
                                isMask: false,
                                message: btn.data('msg'),
                                follow: btn,
                                onOk: function () {
                                    btn.data('subcheck', false);
                                    btn.click();
                                }
                            });
                        } else {
                            btn.data('subcheck', false);
                            btn.click();
                        }

                    } else {
                        $('<span class="tips_error">请至少选择一项</span>').appendTo(btn.parent()).fadeIn('fast');
                    }
                    return false;
                }

                //ie处理placeholder提交问题
                if ($.browser.msie) {
                    form.find('[placeholder]').each(function () {
                        var input = $(this);
                        if (input.val() == input.attr('placeholder')) {
                            input.val('');
                        }
                    });
                }

                form.ajaxSubmit({
                    url: btn.data('action') ? btn.data('action') : form.attr('action'),			//按钮上是否自定义提交地址(多按钮情况)
                    dataType: 'json',
                    data: btn.data('value') ? {btn:btn.data('value')}:{},   // 将按钮上的值传递
                    beforeSubmit: function (arr, $form, options) {
                        var text = btn.text();

                        //按钮文案、状态修改
                        btn.text(text + '中...').prop('disabled', true).addClass('disabled');
                    },
                    success: function (data, statusText, xhr, $form) {
                        var text = btn.text();

                        //按钮文案、状态修改
                        btn.removeClass('disabled').text(text.replace('中...', '')).parent().find('span').remove();

                        if (data.state === 'success') {

                            if(data.href){
                                //window.parent.Wind.dialog.closeAll();
                                window.parent.Wind.dialog.open(data.href, {
                                    onClose: function () {
                                        reloadPage(window.parent);
                                    },
                                    title: data.message,
                                    isDrag:false
                                });
                            }else{
                                $('<span class="tips_success">' + data.message + '</span>').appendTo(btn.parent()).fadeIn('slow').delay(500).fadeOut(function () {
                                    if (typeof callback =='function') {

                                        callback(data.data);

                                        if (window.parent.Wind.dialog) {
                                            window.parent.Wind.dialog.closeAll()
                                        }

                                    }
                                    if (data.referer) {
                                        //返回带跳转地址
                                        //if (window.parent.Wind.dialog) {
                                        //    //iframe弹出页
                                        //    window.parent.location.href = decodeURIComponent(data.referer);
                                        //} else {
                                        window.location.href = decodeURIComponent(data.referer);
                                        //}
                                    } else {
                                        if (window.parent.Wind != undefined && window.parent.Wind.dialog) {
                                            reloadPage(window.parent);
                                        } else {
                                            reloadPage(window);
                                        }
                                    }

                                });
                            }

                        } else if (data.state === 'fail') {
                            $('<span class="tips_error">' + data.message + '</span>').appendTo(btn.parent()).fadeIn('fast');
                            btn.removeProp('disabled').removeClass('disabled');
                        }
                    }
                });
            });

        });
    }


    /*复选框全选(支持多个，纵横双控全选)。
     *实例：版块编辑-权限相关（双控），验证机制-验证策略（单控）
     *说明：
     *	"J_check"的"data-xid"对应其左侧"J_check_all"的"data-checklist"；
     *	"J_check"的"data-yid"对应其上方"J_check_all"的"data-checklist"；
     *	全选框的"data-direction"代表其控制的全选方向(x或y)；
     *	"J_check_wrap"同一块全选操作区域的父标签class，多个调用考虑
     */

    if ($('.J_check_wrap').length) {
        var total_check_all = $('input.J_check_all');

        //遍历所有全选框
        $.each(total_check_all, function () {
            var check_all = $(this), check_items;

            //分组各纵横项
            var check_all_direction = check_all.data('direction');
            check_items = $('input.J_check[data-' + check_all_direction + 'id="' + check_all.data('checklist') + '"]');

            //点击全选框
            check_all.change(function (e) {
                var check_wrap = check_all.parents('.J_check_wrap'); //当前操作区域所有复选框的父标签（重用考虑）

                if ($(this).attr('checked')) {
                    //全选状态
                    check_items.attr('checked', true);

                    //所有项都被选中
                    if (check_wrap.find('input.J_check').length === check_wrap.find('input.J_check:checked').length) {
                        check_wrap.find(total_check_all).attr('checked', true);
                    }

                } else {
                    //非全选状态
                    check_items.removeAttr('checked');

                    //另一方向的全选框取消全选状态
                    var direction_invert = check_all_direction === 'x' ? 'y' : 'x';
                    check_wrap.find($('input.J_check_all[data-direction="' + direction_invert + '"]')).removeAttr('checked');
                }

            });

            //点击非全选时判断是否全部勾选
            check_items.change(function () {

                if ($(this).attr('checked')) {

                    if (check_items.filter(':checked').length === check_items.length) {
                        //已选择和未选择的复选框数相等
                        check_all.attr('checked', true);
                    }

                } else {
                    check_all.removeAttr('checked');
                }

            });


        });

    }

    /**
     * 基于ajax的联动下拉选择
     * @type {*|jQuery|HTMLElement}
     */
    var ajax_select = $(".J_ajax_select");
    if (ajax_select.length) {
        //ajax_select.hide();
        ajax_select.each(function(){
            if($(this).data('init')){
                var valId=$(this).data('val');
                valId = $('#'+valId);

                var select = $(this);
                $.post($(this).data('url'),function(rsb){
                    var option ="";
                    if(select.data('type')=='all'){
                        option = "<option value=''>全部</option>";
                    }else{
                        option = "<option value=''>请选择</option>";
                    }

                    for(var i=0;i<rsb.length;i++){
                        option+='<option value="'+rsb[i].id+'">'+rsb[i].name+'</option>';
                    }
                    select.html(option);
                    select.show();
                    if(select.data('value')!=''){
                        select.val(select.data('value'));
                        select.change();
                    }
                    valId.val(select.data('value'));

                },'json');

            }
            $(this).change(function(){
                var valId=$(this).data('val');
                var select = $(this);
                valId = $('#'+valId);
                var target = $(this).data('target');
                target=$('#'+target);
                if(target.length>0){
                    var option ="";
                    if(target.data('type')=='all'){
                        option = "<option value='"+select.val()+"'>全部</option>";
                    }else{
                        option = "<option value=''>请选择</option>";
                    }
                    if(select.val()!=''){
                        $.post(target.data('url'),"id="+select.val(),function(rsb){
                            if(rsb.length>0){
                                for(var i=0;i<rsb.length;i++){
                                    option+='<option value="'+rsb[i].id+'">'+rsb[i].name+'</option>';
                                }
                                valId.val('');
                                target.show();
                            }else{
                                valId.val(select.val());
                                target.hide();
                            }
                            target.html(option);
                            if(target.val() == undefined || target.val() == ''){
                                valId.val(select.val());
                            }
                            if(target.data('value')!=undefined){
                                target.val(target.data('value'));
                            }
                            target.change();

                        },'json');
                    }else if(select.find('option:selected').text()!='全部'){
                        //target.hide();
                        target.html(option);
                        var prevselect = select.prev().val();
                        valId.val(prevselect);
                    }else{
                        //target.hide();
                        valId.val(select.val());
                    }
                }else{
                    if(select.find('option').length>1 && !(select.val() == undefined || select.val() == '')){
                        valId.val(select.val());
                    }else{
                        var prevselect = select.prev().val();
                        valId.val(prevselect);
                    }
                }
            });
        });

    }


    /*
     * 注册文件上传方法(基于百度webuploader)
     */
    $.fn.thinkWebUpload = function (options) {
        var ele = this;
        //console.log(this);
        Wind.css('webUploader',function(){
            Wind.use('webUploader',function(){
                var uploader = initWebUpload(ele, options);
            });
        });
    }

    // $.fn.uploadify = function(options){
    //     var ele = this;
    //     Wind.css('uploadify',function(){
    //         Wind.use('uploadify',function(){
    //             initWebUploadify(ele,options);
    //         });
    //     });
    // }

})(window);


//重新刷新页面，使用location.reload()有可能导致重新提交
function reloadPage(win) {
    var location = win.location;
    location.href = location.pathname + location.search;
}

// 删除单张图片上传图像
function removeImageSingle(_this,node,input){
    console.log(node);
    console.log(input);
    Wind.confirm('确认要删除吗',function(){
        // 移除隐藏域元素
        var index = $('#' + node + ' li').index($(_this).parents('li'));
        $("#" + input + " input[name='file']").val('');
        $($('#' + input + ' input').get(index + 1)).val('');

        // 移除节点元素
        $(_this).parents('li').remove();
    });
}

// 删除多张图片上传图像
function removeImageMulti(_this,node,input){
    console.log(node);
    console.log(input);
    Wind.confirm('确认要删除吗',function(){
        // 移除隐藏域元素
        var index = $('#' + node + ' li').index($(_this).parents('li'));
        $("#" + input + " input[name='file']").val('');
        $('#' + input + ' input').get(index + 1).remove();

        // 移除节点元素
        $(_this).parents('li').remove();
    });
}

// 上传单张图像
function uploadImageSingle(selector,preview_box,preview_input,hidden_input){
    var element = document.querySelector('#'+selector);
    var upload_url = $('#'+selector).data('href');
    var base_url = $('#'+selector).data('url') + '/';
    element.onchange = function (e) {
        lrz(this.files[0], {width: 640, fieldName: "file1"}).then(function (rst) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', upload_url);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    var obj = eval('(' + xhr.responseText + ')');
                    console.log(obj.data);
                    $('#'+preview_box).html('<li class="weui_uploader_file weui_uploader_status" style="background-image:url(' + base_url + obj.href + '?' + Math.random() +')"><div class="weui_uploader_status_content"><a href="javascript:void(0);" onclick="removeImageSingle(this,\'' + preview_box + '\',\'' + preview_input + '\')" class="weui_icon_cancel">删除</a></div></li>');
                    $("#"+preview_input+" input[name='"+hidden_input+"']").val(obj.href);
                } else {
                    // 处理其他情况
                }
            };

            // 添加参数
            rst.formData.append('size', rst.fileLen);
            rst.formData.append('base64', rst.base64);
            rst.formData.append('origin', rst.origin);
            // 触发上传
            xhr.send(rst.formData);

            return rst;
        }).catch(function (err) {
            alert(err);
        }).always(function () {
            // 不管是成功失败，这里都会执行
        });
    }
}

// 上传多张图像
function uploadImageMulti(selector,preview_box,preview_input,hidden_input){
    var element = document.querySelector('#'+selector);
    var upload_url = $('#'+selector).data('href');
    var base_url = $('#'+selector).data('url') + '/';
    element.onchange = function (e) {
        var files = e.target.files;
        var len = files.length;
        for (var i = 0; i < len; i++) {
            lrz(files[i], {width: 640, fieldName: "file1"}).then(function (rst) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', upload_url);

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var obj = eval('(' + xhr.responseText + ')');
                        console.log(obj.data);
                        $('#'+preview_box).append('<li class="weui_uploader_file weui_uploader_status" style="background-image:url(' + base_url + obj.href + ')"><div class="weui_uploader_status_content"><a href="javascript:void(0);" onclick="removeImageMulti(this,\'' + preview_box + '\',\'' + preview_input + '\')" class="weui_icon_cancel">删除</a></div></li>');
                        $('#'+preview_input).append('<input value="' + obj.href + '"  type="hidden"  name="' + hidden_input + '" />');
                    } else {
                        // 处理其他情况
                    }
                };

                // 添加参数
                rst.formData.append('size', rst.fileLen);
                rst.formData.append('base64', rst.base64);
                // 触发上传
                xhr.send(rst.formData);

                return rst;
            }).catch(function (err) {
                alert(err);
            }).always(function () {
                // 不管是成功失败，这里都会执行
            });

        }
    }
}

function initWebUploadify(item, options){
    var $wrap = item;

    var upload_onSelectError = function(file, errorCode, errorMsg){
        var msgText = "上传失败\n";
        switch (errorCode) {
            case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
                msgText += "每次最多上传 " + this.settings.queueSizeLimit + "个文件";
                break;
            case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                msgText += "文件大小超过限制( " + this.settings.fileSizeLimit + " )";
                break;
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                msgText += "文件大小为0";
                break;
            case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
                msgText += "文件格式不正确，仅限 " + this.settings.fileTypeExts;
                break;
            default:
                msgText += "错误代码：" + errorCode + "\n" + errorMsg;
        }
        Wind.alert(msgText);
    };

    var upload_onUploadComplete = function(file){
        return true;
    };

    var upload_onUploadError = function(file, errorCode, errorMsg, errorString) {
        // 手工取消不弹出提示
        if (errorCode == SWFUpload.UPLOAD_ERROR.FILE_CANCELLED
            || errorCode == SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED) {
            return;
        }
        var msgText = "上传失败\n";
        switch (errorCode) {
            case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
                msgText += "HTTP 错误\n" + errorMsg;
                break;
            case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
                msgText += "上传文件丢失，请重新上传";
                break;
            case SWFUpload.UPLOAD_ERROR.IO_ERROR:
                msgText += "IO错误";
                break;
            case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
                msgText += "安全性错误\n" + errorMsg;
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                msgText += "每次最多上传 " + this.settings.uploadLimit + "个";
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
                msgText += errorMsg;
                break;
            case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
                msgText += "找不到指定文件，请重新操作";
                break;
            case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
                msgText += "参数错误";
                break;
            default:
                msgText += "文件:" + file.name + "\n错误码:" + errorCode + "\n"
                + errorMsg + "\n" + errorString;
        }
        Wind.alert(msgText);
    };

    var upload_onUploadSuccess = function(file, data, response) {
        var data = eval('('+ data +')');
        console.log(response);
        if(data.code == 300){
            // 上传失败
            Wind.alert(data.message);
        }else if(data.code == 200){
            // 设置上传成功的影藏字段的值
            var hidden_name = $wrap.attr('name');

            var html = '';
            if(data.data.fileType == 'image'){
                html = '<input type="hidden" name="'+ hidden_name +'" value="'+ data.data.id +'"><img src="'+ data.data.path +'" title="'+ file.name +'" width="110px" height="110px"/>';

                $('#' + opts.htmlAreaId).html(html);
            }else{
                html = '<input type="hidden" name="'+ hidden_name +'" value="'+ data.data.id +'"><a href="'+ data.data.path +'">'+ file.name +'</a>';

                $('#' + opts.htmlAreaId).html(html);
            }
            Wind.alert(data.message);
        }else{
            console.log('未知错误');
        }
    };

    var defaults = {
        swf: GV.JS_ROOT + '/util_libs/uploadify/uploadify.swf',
        buttonText: '上传文件',
        fileTypeExts: '*.jpg;*.gif;*.jpeg;*.png;*.bmp;',
        uploadLimit: 6,
        removeCompleted : true,
        removeTimeout : 1,
        buttonClass: 'btn_upload',
        filesizelimit: '50M',    // 50 M
        //overrideEvents : ['onDialogClose', 'onUploadSuccess', 'onUploadError', 'onSelectError' , 'onUploadComplete'],
        onSelectError: upload_onSelectError,
        onUploadError: upload_onUploadError,
        onUploadComplete: upload_onUploadComplete,
        onUploadSuccess: upload_onUploadSuccess,
        hiddenInputId:'hidden'
    };


    var opts = $.extend({}, defaults, options);

    $wrap.uploadify(opts);
}



function initWebUpload(item, options) {

    var $wrap = item,

    // 图片容器
    $queue = $( '<ul class="filelist"></ul>' )
        .appendTo( $wrap.find( '.queueList' ) ),

    // 状态栏，包括进度和控制按钮
    $statusBar = $wrap.find( '.statusBar' ),

    // 文件总体选择信息。
    $info = $statusBar.find( '.info' ),

    // 没选择文件之前的内容。
    $placeHolder = $wrap.find( '.placeholder' ),

    $progress = $statusBar.find( '.progress' ).hide(),

    // 添加的文件数量
    fileCount = 0,

    // 添加的文件总大小
    fileSize = 0,

    // 优化retina, 在retina下这个值是2
    ratio = window.devicePixelRatio || 1,

    // 缩略图大小
    thumbnailWidth = 110 * ratio,
    thumbnailHeight = 110 * ratio,

    // 可能有pedding, ready, uploading, confirm, done.
    state = 'pedding',

    // 所有文件的进度信息，key为file id
    percentages = {},
    // 判断浏览器是否支持图片的base64
    isSupportBase64 = ( function() {
        var data = new Image();
        var support = true;
        data.onload = data.onerror = function() {
            if( this.width != 1 || this.height != 1 ) {
                support = false;
            }
        }
        data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
        return support;
    } )(),

    // 检测是否已经安装flash，检测flash的版本
    flashVersion = ( function() {
        var version;

        try {
            version = navigator.plugins[ 'Shockwave Flash' ];
            version = version.description;
        } catch ( ex ) {
            try {
                version = new ActiveXObject('ShockwaveFlash.ShockwaveFlash')
                    .GetVariable('$version');
            } catch ( ex2 ) {
                version = '0.0';
            }
        }
        version = version.match( /\d+/g );
        return parseFloat( version[ 0 ] + '.' + version[ 1 ], 10 );
    } )(),

    supportTransition = (function(){
        var s = document.createElement('p').style,
            r = 'transition' in s ||
                'WebkitTransition' in s ||
                'MozTransition' in s ||
                'msTransition' in s ||
                'OTransition' in s;
        s = null;
        return r;
    })(),

    // WebUploader实例
        uploader;

    if ( !WebUploader.Uploader.support('flash') && WebUploader.browser.ie ) {

        // flash 安装了但是版本过低。
        if (flashVersion) {
            (function(container) {
                window['expressinstallcallback'] = function( state ) {
                    switch(state) {
                        case 'Download.Cancelled':
                            alert('您取消了更新！');
                            break;

                        case 'Download.Failed':
                            alert('安装失败');
                            break;

                        default:
                            alert('安装已成功，请刷新！');
                            break;
                    }
                    delete window['expressinstallcallback'];
                };

                var swf = './expressInstall.swf';
                // insert flash object
                var html = '<object type="application/' +
                    'x-shockwave-flash" data="' +  swf + '" ';

                if (WebUploader.browser.ie) {
                    html += 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ';
                }

                html += 'width="100%" height="100%" style="outline:0">'  +
                '<param name="movie" value="' + swf + '" />' +
                '<param name="wmode" value="transparent" />' +
                '<param name="allowscriptaccess" value="always" />' +
                '</object>';

                container.html(html);

            })($wrap);

            // 压根就没有安转。
        } else {
            $wrap.html('<a href="http://www.adobe.com/go/getflashplayer" target="_blank" border="0"><img alt="get flash player" src="http://www.adobe.com/macromedia/style_guide/images/160x41_Get_Flash_Player.jpg" /></a>');
        }

        return;
    } else if (!WebUploader.Uploader.support()) {
        alert( 'Web Uploader 不支持您的浏览器！');
        return;
    }


    var defaults = {
        hiddenInputId: "uploadifyHiddenInputId", // input hidden id
        innerOptions: {
            //accept: {
            //    title: 'Images',
            //    extensions: 'gif,jpg,jpeg,bmp,png',
            //    mimeTypes: 'image/*'
            //}
        },
        fileNumLimit: 6,
        fileSizeLimit: 200 * 1024 * 1024,    // 200 M
        fileSingleSizeLimit: 50 * 1024 * 1024,    // 50 M
        PostbackHold: false
    };

    var opts = $.extend({}, defaults, options);

    var target = $wrap;//容器
    var pickerid = "";
    if (typeof guidGenerator36 != 'undefined')//给一个唯一ID
        pickerid = guidGenerator36();
    else
        pickerid = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);

    var webuploaderoptions = $.extend({

            auto: true,
            // swf文件路径
            swf: GV.JS_ROOT + '/util_libs/webuploader/Uploader.swf',

            // 文件接收服务端。
            server: opts.url,

            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: {
                id : '#' + pickerid,
                multiple : false
            },

            // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
            resize: false,
            fileNumLimit: opts.fileNumLimit,
            fileSizeLimit: opts.fileSizeLimit,
            fileSingleSizeLimit: opts.fileSingleSizeLimit,

            duplicate: true
        },
        opts.innerOptions);

    //var uploaderStrdiv = '<div id="' + pickerid + '">选择文件</div><div class="uploadBtn">开始上传</div>';
    var uploaderStrdiv = '<div id="' + pickerid + '">上传文件</div>';
    target.find('.btns').prepend(uploaderStrdiv);

    // 上传按钮
    $upload = $wrap.find( '.uploadBtn' ),

    // 实例化
    uploader = WebUploader.create(webuploaderoptions);

    // 拖拽时不接受 js, txt 文件。
    uploader.on( 'dndAccept', function( items ) {
        var denied = false,
            len = items.length,
            i = 0,
        // 修改js类型
            unAllowed = 'text/plain;application/javascript ';

        for ( ; i < len; i++ ) {
            // 如果在列表里面
            if ( ~unAllowed.indexOf( items[ i ].type ) ) {
                denied = true;
                break;
            }
        }

        return !denied;
    });

    uploader.on('dialogOpen', function() {
        console.log('here');
    });

    // uploader.on('filesQueued', function() {
    //     uploader.sort(function( a, b ) {
    //         if ( a.name < b.name )
    //           return -1;
    //         if ( a.name > b.name )
    //           return 1;
    //         return 0;
    //     });
    // });

    uploader.on('ready', function() {
        window.uploader = uploader;
    });

    // 新增编辑时图片显示
    $('.filelist li').live( 'mouseenter', function() {
        $(this).find('.file-panel').stop().animate({height: 30});
    });

    $('.filelist li').live( 'mouseleave', function() {
        $(this).find('.file-panel').stop().animate({height: 0});
    });

    $('.file-panel span').live( 'click',function(e) {
        e.stopPropagation();
        var _this = this;
        Wind.dialog({
            type: 'confirm',
            isMask: false,
            message: '确定要删除吗？',
            onOk: function () {
                // 删除显示视图
                $(_this).parents('li').remove();

                // 删除影藏表单中的元素
                var inputIds = $('#' + $(_this).data('val')).val(),idx;

                // 删除队列中的file
                var files = uploader.getFiles();
                for(var j = 0;j < files.length;j++){
                    uploader.removeFile(files[j],true);
                }

                if(inputIds){
                    var inputIdsArr = inputIds.split(',');
                    var id = $(_this).data('key');

                    for(var i = 0;i < inputIdsArr.length;i++){
                        if(inputIdsArr[i] == id){
                            idx = i;
                        }
                    }

                    inputIdsArr.splice(idx,1)
                    $('#' + $(_this).data('val')).val(inputIdsArr.join(','));
                }

                // 发送ajax请求删除
                //$.getJSON(url, function(data){});
            }
        });
    });

    // 当有文件添加进来时执行，负责view的创建
    function addFile( file ) {
        var $li = $( '<li id="' + file.id + '" class="file_' + file.ext + '">' +
            '<p class="title">' + file.name + '</p>' +
            '<p class="imgWrap"></p>'+
            '<p class="progress"><span></span></p>' +
            '</li>' ),

            $btns = $('<div class="file-panel">' +
            '<span class="cancel" data-val="'+opts.hiddenInputId+'">删除</span>' +
            //'<span class="rotateRight">向右旋转</span>' +
            //'<span class="rotateLeft">向左旋转</span></div>' +
            '').appendTo( $li ),
            $prgress = $li.find('p.progress span'),
            $wrap = $li.find( 'p.imgWrap' ),
            $info = $('<p class="error"></p>'),

            showError = function( code ) {
                switch( code ) {
                    case 'exceed_size':
                        text = '文件大小超出';
                        break;

                    case 'interrupt':
                        text = '上传暂停';
                        break;

                    default:
                        text = '上传失败，请重试';
                        break;
                }

                $info.text( text ).appendTo( $li );
            };

        if ( file.getStatus() === 'invalid' ) {
            showError( file.statusText );
        } else {
            // @todo lazyload
            $wrap.text( '预览中' );
            //console.log(file);
            uploader.makeThumb( file, function( error, src ) {
                var img;

                if ( error ) {
                    $wrap.text( '不能预览' );
                    return;
                }

                if( isSupportBase64 ) {
                    img = $('<img src="'+src+'">');
                    $wrap.empty().append( img );
                } else {
                    $.ajax('../../server/preview.php', {
                        method: 'POST',
                        data: src,
                        dataType:'json'
                    }).done(function( response ) {
                        if (response.result) {
                            img = $('<img src="'+response.result+'">');
                            $wrap.empty().append( img );
                        } else {
                            $wrap.text("预览出错");
                        }
                    });
                }
            }, thumbnailWidth, thumbnailHeight );

            percentages[ file.id ] = [ file.size, 0 ];
            file.rotation = 0;
        }

        file.on('statuschange', function( cur, prev ) {
            if ( prev === 'progress' ) {
                $prgress.hide().width(0);
            } else if ( prev === 'queued' ) {
                $li.off( 'mouseenter mouseleave' );
                //$btns.remove();
            }

            // 成功
            if ( cur === 'error' || cur === 'invalid' ) {
                showError( file.statusText );
                percentages[ file.id ][ 1 ] = 1;
            } else if ( cur === 'interrupt' ) {
                showError( 'interrupt' );
            } else if ( cur === 'queued' ) {
                percentages[ file.id ][ 1 ] = 0;
            } else if ( cur === 'progress' ) {
                $info.remove();
                $prgress.css('display', 'block');
            } else if ( cur === 'complete' ) {
                $li.append( '<span class="success"></span>' );
            }

            $li.removeClass( 'state-' + prev ).addClass( 'state-' + cur );
        });

        $li.on( 'mouseenter', function() {
            $btns.stop().animate({height: 30});
        });

        $li.on( 'mouseleave', function() {
            $btns.stop().animate({height: 0});
        });

        $btns.on( 'click', 'span', function() {
            var index = $(this).index(),
                deg;

            switch ( index ) {
                case 0:
                    //uploader.removeFile( file );
                    return;

                case 1:
                    file.rotation += 90;
                    break;

                case 2:
                    file.rotation -= 90;
                    break;
            }

            if ( supportTransition ) {
                deg = 'rotate(' + file.rotation + 'deg)';
                $wrap.css({
                    '-webkit-transform': deg,
                    '-mos-transform': deg,
                    '-o-transform': deg,
                    'transform': deg
                });
            } else {
                $wrap.css( 'filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ (~~((file.rotation/90)%4 + 4)%4) +')');
                // use jquery animate to rotation
                // $({
                //     rotation: rotation
                // }).animate({
                //     rotation: file.rotation
                // }, {
                //     easing: 'linear',
                //     step: function( now ) {
                //         now = now * Math.PI / 180;

                //         var cos = Math.cos( now ),
                //             sin = Math.sin( now );

                //         $wrap.css( 'filter', "progid:DXImageTransform.Microsoft.Matrix(M11=" + cos + ",M12=" + (-sin) + ",M21=" + sin + ",M22=" + cos + ",SizingMethod='auto expand')");
                //     }
                // });
            }


        });

        $li.appendTo( $queue );
    }

    // 负责view的销毁
    function removeFile( file ) {
        var $li = $('#'+file.id);

        delete percentages[ file.id ];
        updateTotalProgress();
        $li.off().find('.file-panel').off().end().remove();
    }

    function updateTotalProgress() {
        var loaded = 0,
            total = 0,
            spans = $progress.children(),
            percent;

        $.each( percentages, function( k, v ) {
            total += v[ 0 ];
            loaded += v[ 0 ] * v[ 1 ];
        } );

        percent = total ? loaded / total : 0;


        spans.eq( 0 ).text( Math.round( percent * 100 ) + '%' );
        spans.eq( 1 ).css( 'width', Math.round( percent * 100 ) + '%' );
        updateStatus();
    }

    function updateStatus() {
        var text = '', stats;

        if ( state === 'ready' ) {
            text = '选中' + fileCount + '个文件，共' +
            WebUploader.formatSize( fileSize ) + '。';
        } else if ( state === 'confirm' ) {
            stats = uploader.getStats();
            if ( stats.uploadFailNum ) {
                text = '已成功上传' + stats.successNum+ '个文件，'+
                stats.uploadFailNum + '个文件上传失败，重新上传'
            }

        } else {
            stats = uploader.getStats();
            text = '共' + fileCount + '张（' +
            WebUploader.formatSize( fileSize )  +
            '），已上传' + stats.successNum + '张';

            if ( stats.uploadFailNum ) {
                text += '，失败' + stats.uploadFailNum + '张';
            }
        }

        $info.html( text );
    }

    function setState( val ) {
        var file, stats;

        if ( val === state ) {
            return;
        }

        $upload.removeClass( 'state-' + state );
        $upload.addClass( 'state-' + val );
        state = val;

        switch ( state ) {
            case 'pedding':
                $placeHolder.removeClass( 'element-invisible' );
                $queue.hide();
                $statusBar.addClass( 'element-invisible' );
                uploader.refresh();
                break;

            case 'ready':
                $placeHolder.addClass( 'element-invisible' );
                //$( '#filePicker2' ).removeClass( 'element-invisible');
                $queue.show();
                $statusBar.removeClass('element-invisible');
                uploader.refresh();
                break;

            case 'uploading':
                //$( '#filePicker2' ).addClass( 'element-invisible' );
                $progress.show();
                $upload.text( '暂停上传' );
                break;

            case 'paused':
                $progress.show();
                $upload.text( '继续上传' );
                break;

            case 'confirm':
                $progress.hide();
                //$( '#filePicker2' ).removeClass( 'element-invisible' );
                $upload.text( '开始上传' );

                stats = uploader.getStats();
                if ( stats.successNum && !stats.uploadFailNum ) {
                    setState( 'finish' );
                    return;
                }
                break;
            case 'finish':
                stats = uploader.getStats();
                if ( stats.successNum ) {
                    Wind.use('dialog',function(){
                        var params = {
                            message: '上传成功',
                            type: 'alert',
                            isMask: false
                        };
                        Wind.dialog(params);
                    });
                    //alert( '上传成功' );
                } else {
                    // 没有成功的图片，重设
                    state = 'done';
                    location.reload();
                }
                break;
        }

        updateStatus();
    }

    uploader.onUploadProgress = function( file, percentage ) {
        /*var $li = $('#'+file.id),
            $percent = $li.find('.progress span');

        $percent.css( 'width', percentage * 100 + '%' );
        percentages[ file.id ][ 1 ] = percentage;
        updateTotalProgress();*/
    };

    uploader.on('beforeFileQueued',function(file){
    });

    uploader.on("fileQueued",function(file){
        var inputIds = $('#' + opts.hiddenInputId).val();

        var fileNum = inputIds == '' ? 0 : inputIds.split(',').length;

        if((fileNum) >= opts.fileNumLimit){
            var code = '你选择的文件数量超过限制';
            var params = {
                message: '警告: ' + code,
                type: 'alert',
                isMask: false
            };
            uploader.reset();
            Wind.dialog(params);
            return false;
        }
    });

    uploader.on('error',function(code,file){
        Wind.use('dialog',function(){
            switch(code){
                case 'Q_EXCEED_NUM_LIMIT':
                    code = '你选择的文件数量超过限制';
                    break;
                case 'Q_EXCEED_SIZE_LIMIT':
                    code = '你选择的文件大小超过限制';
                    break;
                case 'F_EXCEED_SIZE':
                    code = '你选择的文件大小超过限制';
                    break;
                case 'Q_TYPE_DENIED':
                    code = '你选择的文件格式不正确';
                    break;
                case 'F_DUPLICATE':
                    code = '上传文件重复';
                    break;
                default :
                    code = code;
            }

            var params = {
                message: '警告: ' + code,
                type: 'alert',
                isMask: false
            };
            uploader.reset();
            Wind.dialog(params);
            return false;
        });
    });

    uploader.onFileQueued = function( file ) {
        fileCount++;
        fileSize += file.size;

        if ( fileCount === 1 ) {
            $placeHolder.addClass( 'element-invisible' );
            $statusBar.show();
        }

        addFile( file );
        setState( 'ready' );
        updateTotalProgress();
    };

    uploader.onFileDequeued = function( file ) {
        fileCount--;
        fileSize -= file.size;

        if ( !fileCount ) {
            setState( 'pedding' );
        }

        removeFile( file );
        updateTotalProgress();

    };

    uploader.on( 'all', function( type ) {
        var stats;
        switch( type ) {
            case 'uploadFinished':
                setState('confirm');
                break;

            case 'startUpload':
                setState( 'uploading' );
                break;

            case 'stopUpload':
                setState( 'paused' );
                break;

        }
    });

    /*
    uploader.onError = function( code ,file) {
        Wind.use('dialog',function(){
            switch(code){
                case 'Q_EXCEED_NUM_LIMIT':
                    code = '你选择的文件数量超过限制';
                    break;
                case 'Q_EXCEED_SIZE_LIMIT':
                    code = '你选择的文件大小超过限制';
                    break;
                case 'F_EXCEED_SIZE':
                    code = '你选择的文件大小超过限制';
                    break;
                case 'Q_TYPE_DENIED':
                    code = '你选择的文件格式不正确';
                    break;
                case 'F_DUPLICATE':
                    code = '上传文件重复';
                    break;
                default :
                    code = code;
            }

            var params = {
                message: '警告: ' + code,
                type: 'alert',
                isMask: false
            };
            Wind.dialog(params);
        });
        //alert( 'Eroor: ' + code );
    };*/

    uploader.on('uploadAccept',function(obj,ret){
        //console.log(ret);
        if(ret.state == 'fail'){
            var params = {
                message: '警告: ' + ret.message,
                type: 'alert',
                isMask: false
            };
            Wind.dialog(params);
            return false;
        }
    });


    uploader.on('uploadSuccess',function(file,response){

        var inputIds = $('#' + opts.hiddenInputId).val();
        var ids = response.data + ',' + inputIds;
        if(inputIds != undefined){
            $('#' + opts.hiddenInputId).val(ids.replace(/,$/gi,''));
        }
    });

    uploader.on('uploadComplete',function(file,response){
        //console.log('complete')
    });

    $upload.on('click', function() {
        if ( $(this).hasClass( 'disabled' ) ) {
            return false;
        }

        if ( state === 'ready' ) {
            uploader.upload();
        } else if ( state === 'paused' ) {
            uploader.upload();
        } else if ( state === 'uploading' ) {
            uploader.stop();
        }
    });

    $upload.on('mouseover',function(){
        $(this).addClass('uploadBtn-hover')
    });

    $upload.on('mouseout',function(){
        $(this).removeClass('uploadBtn-hover')
    });

    $info.on( 'click', '.retry', function() {
        uploader.retry();
    } );

    $info.on( 'click', '.ignore', function() {
        alert( 'todo' );
    } );

    $upload.addClass( 'state-' + state );
    updateTotalProgress();

    return uploader;
}