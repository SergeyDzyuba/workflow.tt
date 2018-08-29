var process_id = '';
var options = '';
var options_updated = false;
$(document).ready(function () {
    if ($('#process_id').length !== 0) {
        var last_process_id = (typeof $('#process_id').val() !== 'undefined') ? $('#process_id').val() : '';
        process_id = last_process_id;
        if (typeof process_id !== 'undefined')
            options = getOptions(process_id);
        options_updated = true;
        var elements_arr_len = $("select[id^='aow_actions_param_parent_module']").length;
        setInterval(function () {
            //слушатель изменения релейт поля (срабатывает на изменение скрытого input с id)
            if ((typeof $('#process_id').val() !== 'undefined') && $('#process_id').val() !== last_process_id) {
                // console.log('id changed');
                last_process_id = $('#process_id').val();
                process_id = $('#process_id').val();
                options = getOptions(process_id);
                options_updated = true;
            }

            if ($("select[id^='aow_actions_param_parent_module']").length !== elements_arr_len || options_updated) {
                elements_arr_len = $("select[id^='aow_actions_param_parent_module']").length;
                $("select[id^='aow_actions_param_parent_module']").each(function (i, el) {
                    var line_num = el.id.substring(el.id.length - 1, el.id.length);
                    // var options_length = options.match(/\<\/option\>/ig).length;
                    if (($('#' + el.id).children().length === 0 || options_updated) && $('input[name="aow_actions_param[' + line_num + '][relate_to_workflow]"]').is(':checked')) {
                        $('#' + el.id).html(options);
                        options_updated = false;
                        //атрибут обновления и выбор сохраненного значения выпадашки
                        if ((typeof $('#' + el.id).attr('updated') === 'undefined' || $('#' + el.id).attr('updated') !== 'updated') &&
                            (typeof cr_selected_parent_module[line_num] !== 'undefined' && cr_selected_parent_module[line_num].length > 0)) {
                            $('#' + el.id).attr('updated', 'updated');
                            $('#' + el.id).val(cr_selected_parent_module[line_num]);
                        }
                    }
                });
            }
        }, 500);//1000? vs 500?
    }
});

function getOptions(parent_process_id) {
    var response = '';
    $.ajax({
        type: "POST",
        datatype: "html",
        url: 'index.php?module=AOW_Workflow&action=getProcessHierarchy&to_pdf=true',
        data: {process_id: parent_process_id},
        async: false,
        success: function (data) {
            response = data;
        }
    });
    return response;
}