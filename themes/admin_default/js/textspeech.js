/**
 * @Project NUKEVIET 4.x
 * @Author DacLoi.,JSC <saka.dacloi@gmail.com>
 * @Copyright (C) 2022 DacLoi.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 09 May 2022 08:02:27 GMT
 */
 
function speed_action(oForm, checkss, msgnocheck) {
	
	 var fa = oForm['idcheck[]'];
    var listid = '';
    if (fa.length) {
        for (var i = 0; i < fa.length; i++) {
            if (fa[i].checked) {
                listid = listid + fa[i].value + ',';
            }
        }
    } else {
        if (fa.checked) {
            listid = listid + fa.value + ',';
        }
    }

    if (listid != '') {
        var action = document.getElementById('action').value;
        if (action == 'delete') {
            if (confirm(nv_is_del_confirm[0])) {
                $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(), 'listid=' + listid + '&checkss=' + checkss, function(res) {
                    nv_del_content_result(res);
                });
            }
        } else {
            window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + action + '&listid=' + listid + '&checkss=' + checkss;
        }
    } else {
        alert(msgnocheck);
    }
}

function nv_del_content(id, checkss, base_adminurl, detail) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + checkss, function(res) {
            nv_del_content_result(res);
        });
    }
    return false;
}

function nv_check_movecat(oForm, msgnocheck) {
    var fa = oForm['catidnews'];
    if (fa.value == 0) {
        alert(msgnocheck);
        return false;
    }
}

function nv_del_content_result(res) {
    var r_split = res.split('_');
    if (r_split[0] == 'OK') {
        window.location.href = window.location.href;
    } else if (r_split[0] == 'ERR') {
        alert(r_split[1]);
    } else {
        alert(nv_is_del_confirm[2]);
    }
    return false;
}
