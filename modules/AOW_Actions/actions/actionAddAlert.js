/**
     * Advanced OpenWorkflow, Automating SugaremailM.
 * @package Advanced OpenWorkflow for SugaremailM
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author SalesAgility <info@salesagility.com>
 */

var currentln;
var alertln = new Array();

function show_edit_template_link(field, ln) {
    var field1 = document.getElementById('aow_actions_edit_template_link' + ln);

    if (field.selectedIndex == 0) {
        field1.style.visibility = "hidden";
    } else {
        field1.style.visibility = "visible";
    }
}

function refresh_alert_template_list(template_id, template_name) {
    refresh_template_list(template_id, template_name,currentln);
}

function refresh_template_list(template_id, template_name, ln) {
    var field = document.getElementById('aow_actions_param_alert_template' + ln);
    var bfound = 0;
    for (var i = 0; i < field.options.length; i++) {
        if (field.options[i].value == template_id) {
            if (field.options[i].selected == false) {
                field.options[i].selected = true;
            }
            field.options[i].text = template_name;
            bfound = 1;
        }
    }
    //add item to selection list.
    if (bfound == 0) {
        var newElement = document.createElement('option');
        newElement.text = template_name;
        newElement.value = template_id;
        field.options.add(newElement);
        newElement.selected = true;
    }

    //enable the edit button.
    var field1 = document.getElementById('aow_actions_edit_template_link' + ln);
    field1.style.visibility = "visible";
}

// function open_alert_template_form(ln) {
//     currentln = ln;
//     URL = "index.php?module=EmailTemplates&action=EditView&inboundEmail=1&return_module=AOW_WorkFlow&base_module=AOW_WorkFlow";
//     URL += "&show_js=1";
//
//     windowName = 'alert_template';
//     windowFeatures = 'width=800' + ',height=600' + ',resizable=1,semailollbars=1';
//
//     win = window.open(URL, windowName, windowFeatures);
//     if (window.focus) {
//         // put the focus on the popup if the browser supports the focus() method
//         win.focus();
//     }
// }

// function edit_email_template_form(ln) {
//     currentln = ln;
//     var field = document.getElementById('aow_actions_param_email_template' + ln);
//     URL = "index.php?module=EmailTemplates&action=EditView&inboundEmail=1&return_module=AOW_WorkFlow&base_module=AOW_WorkFlow";
//     if (field.options[field.selectedIndex].value != 'undefined') {
//         URL += "&record=" + field.options[field.selectedIndex].value;
//     }
//     URL += "&show_js=1";
//
//     windowName = 'email_template';
//     windowFeatures = 'width=800' + ',height=600' + ',resizable=1,semailollbars=1';
//
//     win = window.open(URL, windowName, windowFeatures);
//     if (window.focus) {
//         // put the focus on the popup if the browser supports the focus() method
//         win.focus();
//     }
// }

function show_alertField(ln, cln, value){
    if (typeof value === 'undefined') { value = ''; }

    flow_module = document.getElementById('flow_module').value;
    var aow_alerttype = document.getElementById('aow_actions_param'+ln+'_alert_to_type'+cln).value;
    if(aow_alerttype != ''){
        var callback = {
            success: function(result) {
                document.getElementById('alertLine'+ln+'_field'+cln).innerHTML = result.responseText;
                SUGAR.util.evalScript(result.responseText);
                enableQS(false);
            },
            failure: function(result) {
                document.getElementById('alertLine'+ln+'_field'+cln).innerHTML = '';
            }
        }

        var aow_field_name = "aow_actions_param["+ln+"][alert]["+cln+"]";

        YAHOO.util.Connect.asyncRequest ("GET", "index.php?module=AOW_WorkFlow&action=getAlertField&aow_module="+flow_module+"&aow_newfieldname="+aow_field_name+"&aow_type="+aow_alerttype+"&aow_value="+value,callback);
    }
    else {
        document.getElementById('alertLine'+ln+'_field'+cln).innerHTML = '';
    }
}

// тип увеломления, пользователю или пользователям, айди юзверов, тема,ссылка, описание
function load_alertline(ln, type, target, value, alert_name, alert_link, alert_message){
    cln = add_alertLine(ln);
    document.getElementById("aow_actions_param"+ln+"_alert_to_type"+cln).value = type;
    document.getElementById("aow_actions_param["+ln+"][alert_name]["+cln+"]").value = alert_name;//aow_actions_param[1][alert_name][0]
    document.getElementById("aow_actions_param["+ln+"][alert_link]["+cln+"]").value = alert_link;//aow_actions_param[1][alert_link][0]
    document.getElementById("aow_actions_param"+ln+"_alert_message"+cln).value = alert_message;//aow_actions_param1_alert_message0
    show_alertField(ln, cln, value);
}

function add_alertLine(ln){

    var aow_alert_type_list = document.getElementById("aow_alert_type_list").value;
    var aow_alert_to_list = document.getElementById("aow_alert_to_list").value;
    if(alertln[ln] == null){alertln[ln] = 0}

    tablebody = document.createElement("tbody");
    tablebody.id = 'alertLine'+ln+'_body' + alertln[ln];
    document.getElementById('alertLine'+ln+'_table').appendChild(tablebody);
    var x = tablebody.insertRow(-1);
    x.id = 'alertLine'+ln+'_line' + alertln[ln];

    var a = x.insertCell(0);
    a.innerHTML = "<button type='button' id='alertLine"+ln+"_delete" + alertln[ln] + "' class='button' value='Remove Line' tabindex='116' onclick='clear_alertLine(" + ln + ",this);'><img src='themes/default/images/id-ff-remove-nobg.png' alt='Remove Line'></button> ";

    a.innerHTML += "<select tabindex='116' name='aow_actions_param["+ln+"][alert_target_type]["+alertln[ln]+"]' id='aow_actions_param"+ln+"_alert_target_type"+alertln[ln]+"'>" + aow_alert_type_list + "</select> ";

    a.innerHTML += "<select tabindex='116' name='aow_actions_param["+ln+"][alert_to_type]["+alertln[ln]+"]' id='aow_actions_param"+ln+"_alert_to_type"+alertln[ln]+"' onchange='show_alertField(" + ln + "," + alertln[ln] + ");'>" + aow_alert_to_list + "</select> ";

    a.innerHTML += "<span id=\"alertLine"+ln+"_field"+alertln[ln]+"\"><script language=\"javascript\">if(typeof sqs_objects == 'undefined'){var sqs_objects = new Array;}sqs_objects['EditView_modified_by_name']={\"form\":\"EditView\",\"method\":\"get_user_array\",\"field_list\":[\"user_name\",\"id\"],\"populate_list\":[\"modified_by_name\",\"modified_user_id\"],\"required_list\":[\"modified_user_id\"],\"conditions\":[{\"name\":\"user_name\",\"op\":\"like_custom\",\"end\":\"%\",\"value\":\"\"}],\"limit\":\"30\",\"no_match_text\":\"\u041d\u0435 \u0432\u044b\u0431\u0440\u0430\u043d\u043e\"};sqs_objects['EditView_created_by_name']={\"form\":\"EditView\",\"method\":\"get_user_array\",\"field_list\":[\"user_name\",\"id\"],\"populate_list\":[\"created_by_name\",\"created_by\"],\"required_list\":[\"created_by\"],\"conditions\":[{\"name\":\"user_name\",\"op\":\"like_custom\",\"end\":\"%\",\"value\":\"\"}],\"limit\":\"30\",\"no_match_text\":\"\u041d\u0435 \u0432\u044b\u0431\u0440\u0430\u043d\u043e\"};sqs_objects['EditView_aow_actions_param["+ln+"][alert][" + alertln[ln] + "]_display']={\"form\":\"EditView\",\"method\":\"get_user_array\",\"field_list\":[\"user_name\",\"id\"],\"populate_list\":[\"aow_actions_param["+ln+"][alert][" + alertln[ln] + "]_display\",\"aow_actions_param["+ln+"][alert][" + alertln[ln] + "]\"],\"required_list\":[\"aow_actions_param["+ln+"][alert][" + alertln[ln] + "]\"],\"conditions\":[{\"name\":\"user_name\",\"op\":\"like_custom\",\"end\":\"%\",\"value\":\"\"}],\"limit\":\"30\",\"no_match_text\":\"\u041d\u0435 \u0432\u044b\u0431\u0440\u0430\u043d\u043e\"};sqs_objects['EditView_parent_name']={\"form\":\"EditView\",\"method\":\"query\",\"modules\":[\"Accounts\"],\"group\":\"or\",\"field_list\":[\"name\",\"id\"],\"populate_list\":[\"EditView_parent_name\",\"parent_id\"],\"conditions\":[{\"name\":\"name\",\"op\":\"like_custom\",\"end\":\"%\",\"value\":\"\"}],\"required_list\":[\"parent_id\"],\"order\":\"name\",\"limit\":\"30\",\"no_match_text\":\"\u041d\u0435 \u0432\u044b\u0431\u0440\u0430\u043d\u043e\"};sqs_objects['EditView_campaign_name']={\"form\":\"EditView\",\"method\":\"query\",\"modules\":[\"Campaigns\"],\"group\":\"or\",\"field_list\":[\"name\",\"id\"],\"populate_list\":[\"campaign_id\",\"campaign_id\"],\"conditions\":[{\"name\":\"name\",\"op\":\"like_custom\",\"end\":\"%\",\"value\":\"\"}],\"required_list\":[\"campaign_id\"],\"order\":\"name\",\"limit\":\"30\",\"no_match_text\":\"\u041d\u0435 \u0432\u044b\u0431\u0440\u0430\u043d\u043e\"};</script>\n" +
        "<input name=\"aow_actions_param["+ln+"][alert][" + alertln[ln] + "]_display\" class=\"sqsEnabled yui-ac-input\" tabindex=\"1\" id=\"aow_actions_param["+ln+"][alert][" + alertln[ln] + "]_display\" size=\"\" value=\"\" title=\"\" autocomplete=\"off\" type=\"text\"><div id=\"EditView_aow_actions_param["+ln+"][alert][" + alertln[ln] + "]_display_results\" class=\"yui-ac-container\"><div class=\"yui-ac-content\" style=\"display: none;\"><div class=\"yui-ac-hd\" style=\"display: none;\"></div><div class=\"yui-ac-bd\"><ul><li style=\"display: none;\"></li><li style=\"display: none;\"></li><li style=\"display: none;\"></li><li style=\"display: none;\"></li><li style=\"display: none;\"></li><li style=\"display: none;\"></li><li style=\"display: none;\"></li><li style=\"display: none;\"></li><li style=\"display: none;\"></li><li style=\"display: none;\"></li></ul></div><div class=\"yui-ac-ft\" style=\"display: none;\"></div></div></div>\n" +
        "<input name=\"aow_actions_param["+ln+"][alert][" + alertln[ln] + "]\" id=\"aow_actions_param["+ln+"][alert][" + alertln[ln] + "]\" value=\"\" type=\"hidden\">\n" +
        "<span class=\"id-ff multiple\">\n" +
        "<button type=\"button\" name=\"btn_aow_actions_param["+ln+"][alert][" + alertln[ln] + "]_display\" id=\"btn_aow_actions_param["+ln+"][alert][" + alertln[ln] + "]_display\" tabindex=\"1\" title=\"Select User\" class=\"button firstChild\" value=\"Select User\" onclick=\"open_popup(\n" +
        "    &quot;Users&quot;, \n" +
        "\t600, \n" +
        "\t400, \n" +
        "\t&quot;&quot;, \n" +
        "\ttrue, \n" +
        "\tfalse, \n" +
        "\t{&quot;call_back_function&quot;:&quot;set_return&quot;,&quot;form_name&quot;:&quot;EditView&quot;,&quot;field_to_name_array&quot;:{&quot;id&quot;:&quot;aow_actions_param["+ln+"][alert][" + alertln[ln] + "]&quot;,&quot;user_name&quot;:&quot;aow_actions_param["+ln+"][alert][" + alertln[ln] + "]_display&quot;}}, \n" +
        "\t&quot;single&quot;, \n" +
        "\ttrue\n" +
        ");\"><img src=\"themes/SuiteP/images/id-ff-select.png?v=kxu6PV15igsPQm9P1HiQuA\"></button><button type=\"button\" name=\"btn_clr_aow_actions_param["+ln+"][alert][" + alertln[ln] + "]_display\" id=\"btn_clr_aow_actions_param["+ln+"][alert][" + alertln[ln] + "]_display\" tabindex=\"1\" title=\"Clear User\" class=\"button lastChild\" onclick=\"SUGAR.clearRelateField(this.form, 'aow_actions_param["+ln+"][alert][" + alertln[ln] + "]_display', 'aow_actions_param["+ln+"][alert][" + alertln[ln] + "]');\" value=\"Clear User\"><img src=\"themes/SuiteP/images/id-ff-clear.png?v=kxu6PV15igsPQm9P1HiQuA\"></button>\n" +
        "</span>\n" +
        "<script type=\"text/javascript\">\n" +
        "SUGAR.util.doWhen(\n" +
        "\t\t\"typeof(sqs_objects) != 'undefined' && typeof(sqs_objects['EditView_aow_actions_param["+ln+"][alert][" + alertln[ln] + "]_display']) != 'undefined'\",\n" +
        "\t\tenableQS\n" +
        ");\n" +
        "</script></span>";

    a.innerHTML += "<span id='alertLine"+ln+"_field"+alertln[ln]+"'><input id='aow_actions_param["+ln+"][alert_name]["+alertln[ln]+"]' type='text' tabindex='116' size='25' name='aow_actions_param["+ln+"][alert_name]["+alertln[ln]+"]' placeholder='Тема..'></span>";

    // var individual_alert = document.getElementById('aow_actions_param['+ln+'][individual_alert]').checked;

    a.innerHTML += "<span id='alertLine"+ln+"_field"+alertln[ln]+"'><input id='aow_actions_param["+ln+"][alert_link]["+alertln[ln]+"]' type='text' tabindex='116' size='25' name='aow_actions_param["+ln+"][alert_link]["+alertln[ln]+"]' placeholder='Ссылка..'></span>";

    a.innerHTML += '<textarea id="aow_actions_param'+ln+'_alert_message'+ alertln[ln] +'" name="aow_actions_param['+ln+'][alert_message]['+alertln[ln]+']" rows="6" cols="20" title="" tabindex="" placeholder=\'Описание..\'></textarea>';



    alertln[ln]++;

    return alertln[ln] -1;

}

function clear_alertLine(ln, cln){

    document.getElementById('alertLine'+ln+'_table').deleteRow(cln.parentNode.parentNode.rowIndex);
}

function alertLine(ln){

    var alert_rows = document.getElementById('alertLine'+ln+'_table').getElementsByTagName('tr');
    var alert_row_length = alert_rows.length;
    var i;
    for (i=0; i < alert_row_length; i++) {
        document.getElementById('alertLine'+ln+'_table').deleteRow(alert_rows[i]);
    }
}

function hideElem(id){
    if(document.getElementById(id)){
        document.getElementById(id).style.display = "none";
        document.getElementById(id).value = "";
    }
}

function showElem(id){
    if(document.getElementById(id)){
        document.getElementById(id).style.display = "";
    }
}

// function targetTypeChanged(ln){
//     var elem = document.getElementById("aow_actions_param_alert_target_type"+ln);
//     if(elem.value === 'Email Address'){
//         showElem("aow_actions_param_alert"+ln);
//         hideElem("aow_actions_param_alert_target"+ln);
//         hideElem("aow_actions_alert_user_span"+ln);
//     }else if(elem.value === 'Specify User'){
//         hideElem("aow_actions_param_alert"+ln);
//         hideElem("aow_actions_param_alert_target"+ln);
//         showElem("aow_actions_alert_user_span"+ln);
//     }else if(elem.value === 'Related Field'){
//         hideElem("aow_actions_param_alert"+ln);
//         showElem("aow_actions_param_alert_target"+ln);
//         hideElem("aow_actions_alert_user_span"+ln);
//     }else if(elem.value === 'Record alert'){
//         hideElem("aow_actions_param_alert"+ln);
//         hideElem("aow_actions_param_alert_target"+ln);
//         hideElem("aow_actions_alert_user_span"+ln);
//     }
// }
