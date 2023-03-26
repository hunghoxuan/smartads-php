$(document).ready(function () {
    $('a[href^="#scroll-"]').on('click', function (e) {
        e.preventDefault();

        var target = this.hash;
        var $target = $(target);

        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 900, 'swing', function () {
            window.location.hash = target;
        });
    });

    jQuery.fn.extend({
        value: function() {
            var item = $(this).filter(':checked').val();
            if (item == undefined || item == '' || item == 'undefined') {
                //console.log($(this).val());
                val = $(this).val();
            }
            else {
                //console.log($(this).filter(':checked').val());
                val = $(this).filter(':checked').val();
            }
            return val;
        },
        dataArray: function(){
            var data = {};
            var dataArray = $(this).serializeArray();
            for(var i=0;i<dataArray.length;i++){
                data[dataArray[i].name] = dataArray[i].value;
            }
            return data;
        }
    });


    $('body').on('change', '.auto-refresh', function() {
        var field = $(this).find(":selected").attr('field');
        var val = $(this).find(":selected").attr('value');
        var url = $(this).find(":selected").attr('url');
        var pjax = $(this).find(":selected").attr('refresh_content_id');

        if (url == '' || url == undefined) {

            if (field == '' || field === undefined)
                return;

            if (val == 'all' || val == '...') {
                val = '';
            }

            url = window.location.href;
            url = append_url_param(url, field, val);
        }

        if (pjax == '' || pjax == undefined) {
            window.location.href = url;
        } else {
            $.pjax.reload({container: "#" + pjax, timeout: false, url: url });
        }
    });
});

function append_url_param(url, param, value){
    var hash       = {};
    var parser     = document.createElement('a');

    parser.href    = url;

    var parameters = parser.search.split(/\?|&/);

    for(var i=0; i < parameters.length; i++) {
        if(!parameters[i])
            continue;

        var ary      = parameters[i].split('=');
        hash[ary[0]] = ary[1];
    }

    hash[param] = value;

    var list = [];
    Object.keys(hash).forEach(function (key) {
        var value1 = hash[key];
        if (value1 != '' && value1 !== undefined)
            list.push(key + '=' + value1);
    });

    parser.search = '?' + list.join('&');
    return parser.href;
}

function refresh_url(url, field, val, container) {
    if (url == '' || url == undefined) {

        if (field == '' || field === undefined)
            return;

        if (val == 'all' || val == '...') {
            val = '';
        }

        url = window.location.href;
        url = append_url_param(url, field, val);
    }

    if (container == '' || container == undefined) {
        window.location.href = url;
    } else {
        $.pjax.reload({container: container, timeout: false, url: url});
    }
}

function getFormData(form_id, form_name) {
    var formData = new FormData($(form_id)[0]);
    params   = $(form_id).serializeArray();

    $.each(params, function(i, val) {
        var itemName = val.name; itemName = itemName.replace(form_name + "[", ""); itemName = itemName.replace("]", "");
        formData.append(itemName, val.value);
        console.log(itemName + ': ' + val.value + '; ');
    });
    return formData;
}

function appendFormData(formData, field_name, field_value) {
    if (formData == undefined || field_name == undefined || field_value == undefined)
        return;
    formData.append(field_name, field_value);
}

function getVal(input) {
    var type = input.attr("type");
    var id = input.attr("id");
    var name = input.attr("name");
    var value = input.attr("value");
    var files = input.prop("files");

    if (files) {
        value = files[0];
        console.log('getVal (File): id: ' + id + '. name: ' + name + '. type: ' + type + '. value: ' + value);

        return value;
    }

    if (type && type == 'checkbox') {
        value = $("#" + id + " :checked").val();
        if (value == true)
            value = 1;
        else
            value = 0;
    }
    else if (type && type == 'radio')
        value = $("#" + id + " :checked").val();
    else if (type && type == 'radiolist') {
        value = $("#" + id + " :checked").val();
    }
    else {
        value = input.val();
    }

    if (type == 'hidden' && id == undefined) { //
        value = input.is(":checked");
        if (value == true)
            value = 1;
        else
            value = 0;
    }

    if (type == undefined && value == undefined) {
        value = input.attr("value");
    }

    if (type == undefined && value == undefined) {
        value = null;
    }

    console.log('getVal: id: ' + id + '. name: ' + name + '. type: ' + type + '. value: ' + value);

    return value;
}

function showEditor($editorid) {
    $("#" + $editorid + "-label").hide();
    $("#" + $editorid + "-form").show();
}
function closeEditor($editorid) {
    $("#" + $editorid + "-label").show();
    $("#" + $editorid + "-form").hide();
}

function processReturnData(data, $containter) {
    if(data == 1 || data == "") // save true
    {
        new PNotify({
            title: 'Success!',
            text: 'Done Successfully !',
            type: 'success',
        });

        refreshPage('#' + $containter);
        return false;
    }
    else
    if (typeof data == "string") alert(data);
}

function saveEditor($url, $editorid, $containter) {
    $("#" + $editorid + "-label").html($("#" + $editorid).val());
    closeEditor($editorid);
    saveChange($url, $editorid, $containter);
}

function saveBoolean($url, $editorid, $containter) {
    saveChange($url, $editorid, $containter);
}

function saveChange($url, $input, $containter) {
    if (typeof $input == 'string')
        $input = $("#" + $input);

    files = $input.prop("files");
    if (files !== undefined && typeof files == "array" && files.length > 0) {
        $file = files[0];
        $data = new FormData();
        $data.append($file.name, $file);
    }
    else {
        $data = getVal($input);
        $file = "";
    }

    $.ajax({
        url: $url,
        type: "POST",
        data: {
            data: $data,
            id: $input.attr("model_id"),
            field:  $input.attr("model_field"),
            object: $input.attr("object_type"),
            processData: false,
            contentType: false,
            file: $file
        },
        success: function (data, textStatus, jqXHR) {
            processReturnData(data, $containter);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log('ERRORS: ' + textStatus);
        },
        complete: function()
        {
            // STOP LOADING SPINNER
        }
    })
}

function refreshPage($div) {
    if ($div == '#')
        return;

    if ($.isArray($div)) {
        $.each($div , function(index, container) {
            if (index+1 < $div.length) {
                $(container).one('pjax:end', function (xhr, options) {
                    $.pjax.reload($div[index+1], {timeout: false}) ;
                });
            }
        });

        $.pjax.reload($div[0], {timeout: false}) ;
        return;
    }

    if ($div == '' || $div == undefined)
        $div = 'crud-datatable-pjax';

    $.pjax.reload($div, {timeout: 20000});
    //$.hideLoading();
}

function reloadPage() {
    location.reload();
}

function alert($text, $title) {
    if ($text == null || $text == '' || $text == 'undefined')
        return '';
    // new PNotify({
    //     title: $title,
    //     text: $text,
    //     type: 'error',
    //     hide: true,
    //     animate: {
    //         animate: true,
    //         in_class: 'slideInDown',
    //         out_class: 'slideOutUp'
    //     }
    // });
    eModal.alert($text, $title);
}

function promt($text, $title) {
    if ($text == null || $text == '' || $text == 'undefined')
        return '';
    new PNotify({
        title: $title,
        text: $text,
        type: 'info',
        hide: true,
        animate: {
            animate: true,
            in_class: 'slideInDown',
            out_class: 'slideOutUp'
        }
    });
    //eModal.promt($text, $title);
}

function confirm($text, $title, confirmCallback, optionalCancelCallback) {
    if ($text == null || $text == '' || $text == 'undefined')
        return '';
    eModal.confirm($text, $title).then(confirmCallback, optionalCancelCallback);
}

function iframe($text, $title) {
    if ($text == null || $text == '' || $text == 'undefined')
        return '';
    eModal.iframe($text, $title);
}

function ajax($text, $title, ajaxOnLoadCallback) {
    if ($text == null || $text == '' || $text == 'undefined')
        return '';
    eModal.ajax($text, $title).then(ajaxOnLoadCallback);
}

function makeTabsAnchorOnCurrentURL() {
    $(document).ready(function() {
        if (location.hash) {
            $("a[href='" + location.hash + "']").tab("show");
        }
        $(document.body).on("click", "a[data-toggle]", function(event) {
            location.hash = this.getAttribute("href");
        });
    });

    $(window).on("popstate", function() {
        var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
        $("a[href='" + anchor + "']").tab("show");
    });
}

function getAjax(_url, _data, callbackSuccess, callbackError) {
    $.ajax({
        type : 'get',
        url : _url,
        data : _data,
        success : function (result) {
            if (typeof callbackSuccess === 'function') {
                return callbackSuccess(result);
            }
            return callbackSuccess;
        },
        error : function (error) {
            if (typeof callbackError === 'function') {
                return callbackError(error);
            }
            return callbackError;
        }
    });
}

function showModalIframe(_this) {
    url = '';
    let pjax_container = '';
    if(typeof _this === 'string') {
        url = _this;
    }
    else {
        url = $(_this).attr('data-url');
        pjax_container = $(_this).attr('pjax_container');
    }

    $("#modalIframe").find("#modalIframe-body").attr('src', url);
    $("#modalIframe").find(".btn.btn-sm.btn-kv.btn-default.btn-outline-secondary.btn-close").attr('pjax_container', pjax_container);
    $("#modalIframe").modal("show");

}

$(function() {
    $(document).on('click', '#modalIframe .btn.btn-sm.btn-kv.btn-default.btn-outline-secondary.btn-close', function() {
        var pjax_container = $(this).attr('pjax_container');
        let url = window.location;
        $.pjax.reload({container : "#" + pjax_container, timeout : false, url : url});
    }) ;
});
function showModal(_this) {
    url = '';
    if(typeof _this === 'string') {
        url = _this;
    }
    else {
        url = $(_this).attr('data-url');
    }
    $("#ajaxCrubModal").find("#modalAjax-body").load(url);
    $("#ajaxCrubModal").modal("show");
}