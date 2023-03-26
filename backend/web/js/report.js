let report = {
    css : {
        id : {
            branch_group_id : '[name*="branch_group_id"]',
            branch_id : '[name*="branch_id"]',
            branch_employee_id : '[name*="branch_employee_id"]',
            service_id : '[name*="service_id"]',
            employee_id : '[name*="[employee_id]"]',
            start_date : '[name*="start_date"]',
            end_date : '[name*="end_date"]',
            time_type : '[name*="time_type"]',
            js_report : '#js-report'
        }
    },
    suffix : '',
    setSuffix : function (suffix) {
        return this.suffix = suffix;
    },
    url : {
        branch : function() {
            return baseUrl + '/get-branch' + report.suffix;
        },
        service : function () {
            return baseUrl + '/get-service' + report.suffix;
        },
        employee : function () {
            return baseUrl + '/get-employee' + report.suffix;
        },
        report : function () {
            return baseUrl + '/get-report' + report.suffix;
        }
    },
    load : {
        branch :  function (_that, select) {
            _that = $(_that);
            let id = _that.val();
            let data = {id: id};
            this.ajax({select: select, url: report.url.branch(), data: data, result_type: 'option', key_cookie : 'branch_id'});
        },
        branch_employee :  function (_that, select) {
            _that = $(_that);
            let id = _that.val();
            let data = {id: id};
            this.ajax({select: select, url: report.url.branch(), data: data, result_type: 'option', key_cookie : 'branch_employee_id'});
        },
        service : function (_that, select) {
            _that = $(_that);
            let id = _that.val();
            id = parseInt(id);
            if (typeof id === 'string' || isNaN(id)) {
                id = 0;
            }
            let data = {id: id};
            this.ajax({select: select, url: report.url.service(), data: data, result_type: 'option', key_cookie : 'service_id'});
        },
        employee : function (_that, select) {
            _that = $(_that);
            let id = _that.val();
            id = parseInt(id);
            if (typeof id === 'string' || isNaN(id)) {
                id = 0;
            }
            let data = {id: id};
            this.ajax({select: select, url: report.url.employee(), data: data, result_type: 'option', key_cookie : 'employee_id'});
        },
        ajax : function (parameters) {
            let select = parameters.select;
            let url = parameters.url;
            let data = parameters.data;
            let type = parameters.type;
            let result_type = parameters.result_type;
            let key_cookie = parameters.key_cookie;
            let select1 = select;
            select = $(select);
            $.ajax({
                type : type,
                url : url,
                data : data
            }).success(function(result) {
                if(result_type === 'html') {
                    select.html(result);
                    return false;
                }
                let html = "";
                result.data.forEach(function(item, index) {
                    // console.log(readCookie(key_cookie));
                    if (result_type === 'option') {
                        let selected = (readCookie(key_cookie) === item.id) ? 'selected' : "";
                        html += '<option value="'+item.id+'" ' + selected + ' >' + item.name + '</option>';
                    }
                });
                select.html(html);
            }).error(function(error) {
                select.html(error);
            });
        }
    }
};

function showHideTime(select) {
    select = $(select);
    let val = select.val();
    let parent_start = $(report.css.id.start_date).parents('div[class$="start_date"]').parent().parent('.row');
    let parent_end = $(report.css.id.end_date).parents('div[class$="end_date"]').parent().parent('.row');
    if (val === "range") {
        parent_start.removeClass('hide');
        parent_end.removeClass('hide');
    }
    else {
        parent_start.addClass('hide');
        parent_end.addClass('hide');
    }
}

$(function () {
    $(report.css.id.time_type).change(function() {
        showHideTime(this);
    });

    $(window).load(function(event) {
        showHideTime(report.css.id.time_type);
        if ($(report.css.id.branch_id).length) {
            report.load.branch(report.css.id.branch_group_id, report.css.id.branch_id);
            report.load.service(report.css.id.branch_id, report.css.id.service_id);
        }
        else if ($(report.css.id.branch_employee_id).length) {
            report.load.branch_employee(report.css.id.branch_group_id, report.css.id.branch_employee_id);
            report.load.employee(report.css.id.branch_employee_id, report.css.id.employee_id);
        }
    });

    $(report.css.id.branch_id).change(function(event) {
        report.load.service(this, report.css.id.service_id);
    });

    $(report.css.id.branch_employee_id).change(function(event) {
        report.load.employee(this, report.css.id.employee_id);
    });

    $(report.css.id.branch_group_id).change(function(event) {
        if ($(report.css.id.branch_id).length) {
            report.load.branch(report.css.id.branch_group_id, report.css.id.branch_id);
            report.load.service(report.css.id.branch_id, report.css.id.service_id);
        }
        else if ($(report.css.id.branch_employee_id).length) {
            report.load.branch_employee(report.css.id.branch_group_id, report.css.id.branch_employee_id);
            report.load.employee(report.css.id.branch_employee_id, report.css.id.employee_id);
        }
    });

    $('#js-filter-report').click(function() {
        $(report.css.id.js_report).submit(function(event) {
            event.preventDefault();
        });
        if ($(report.css.id.time_type).val() === 'range') {
            let start_date = $(report.css.id.start_date).val();
            let end_date = $(report.css.id.end_date).val();
            let start_date_time = new Date(start_date).getTime();
            let end_date_time = new Date(end_date).getTime();
            if (start_date !== '' && end_date !== '') {
                if (start_date > end_date) {
                    alert("Ngày bắt đầy không được lớn hơn ngày kết thúc");
                    return false;
                }
            }
            else {
                let mess = '';
                mess += (start_date === '') ? 'Ngày bắt đầu không được để rỗng <br>' : '';
                mess += (end_date === '') ? 'Ngày kết thúc không được để rỗng ' : '';
                alert(mess);
                return false;
            }
        }
        $(report.css.id.js_report).unbind('submit');
        $(report.css.id.js_report).submit();
    });
});

function readCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(let i=0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0)===' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function openWin(url) {
    window.open(url,'', 'fullscreen=1,scrollbars=1,directories=0,location=0,menubar=0,status=0,titlebar=0,toolbar=0,resizable=1');
}
