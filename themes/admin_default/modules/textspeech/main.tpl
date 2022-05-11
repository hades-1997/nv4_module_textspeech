<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_DATA}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
                    <th class="text-center"><a href="{base_url_name}">{LANG.name}</a></th>
                    <th class="text-center"><a href="{base_url_publtime}">{LANG.content_publ_date}</a></th>
                    <th>{LANG.author}</th>
                    <th class="text-center">{LANG.audio} <em title="{LANG.audio}" class="fa fa-file-audio-o">&nbsp;</em></th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr class="{ROW.class}">
                    <td class="text-center">
                        <!-- BEGIN: checkrow -->
                        <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
                        <!-- END: checkrow -->
                    </td>
                    <td class="text-left">
                        <!-- BEGIN: sort -->
                        <a href="javascript:void(0);" title="{LANG.order_articles_number}: {ROW.weight}" onclick="nv_sort_content({ROW.id}, {ROW.weight})"><i class="fa fa-fw fa-sort" aria-hidden="true"></i></a>
                        <!-- END: sort -->
                        <!-- BEGIN: is_editing -->
                        <i class="fa fa-fw fa-{LEV_EDITING} text-warning" data-toggle="tooltip" title="{USER_EDITING} {LANG.post_is_editing}."></i>
                        <!-- END: is_editing -->
                        <!-- BEGIN: text -->
                        <strong><em>{LANG.status_4}</em></strong>:
                        <!-- END: text -->
                        <a target="_blank" href="{ROW.link}" id="id_{ROW.id}" title="{ROW.title}">{ROW.title_clean}</a>
                    </td>
                    <td>{ROW.publtime}</td>
                    <td>{ROW.author}</td>
                    <td class="text-center" > <!-- BEGIN: audio --><a href="{AUDIO}"><em title="{LANG.title}" class="fa fa-headphones">&nbsp;</em></a> <!-- END: audio --></td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <tr class="text-left">
                    <td colspan="12">
                        <select class="form-control w150" name="action" id="action">
                            <!-- BEGIN: action -->
                            <option value="{ACTION.value}">{ACTION.title}</option>
                            <!-- END: action -->
                        </select>
                        <input type="button" class="btn btn-primary" onclick=" speed_action(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')" value="{LANG.action}" />
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>


<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<script type="text/javascript">
$(function() {
    $( "#order_articles" ).dialog({
        autoOpen: false,
        show: {
            effect: "blind",
            duration: 500
        },
        hide: {
            effect: "explode",
            duration: 500
        }
    });

    $("#catid").select2({
        language : '{NV_LANG_DATA}'
    });
});



</script>
<!-- END: main -->