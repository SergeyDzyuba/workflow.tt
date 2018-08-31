<?php
/**
 * Advanced OpenWorkflow, Automating SugarCRM.
 * @package Advanced OpenWorkflow for SugarCRM
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


require_once('modules/AOW_Actions/actions/actionBase.php');

class actionAddAlert extends actionBase
{

    private $emailableModules = array();

    function __construct($id = '')
    {
        parent::__construct($id);
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    function actionAddAlert($id = '')
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct($id);
    }


    function loadJS()
    {
        return array('modules/AOW_Actions/actions/actionAddAlert.js');
    }

    function edit_display($line, SugarBean $bean = null, $params = array())
    {
        global $app_list_strings;

        $html = '<input type="hidden" name="aow_alert_type_list" id="aow_alert_type_list" value="' . get_select_options_with_id($app_list_strings['aow_alert_type_list'], '') . '">
				  <input type="hidden" name="aow_alert_to_list" id="aow_alert_to_list" value="' . get_select_options_with_id($app_list_strings['aow_alert_to_list'], '') . '">';

//        $checked = '';
//        if(isset($params['individual_alert']) && $params['individual_alert']) $checked = 'CHECKED';


        $html .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' data-workflow-action='send-email'>";//data-workflow-action='send-email'
        $html .= "<tr>";
//        $html .= '<td id="relate_label" scope="row" valign="top"><label>' . translate("LBL_INDIVIDUAL_ALERTS",
//                "AOW_Actions") . ':</label>';
//        $html .= '</td>';
//        $html .= "<td valign='top'>";
//        $html .= "<input type='hidden' name='aow_actions_param[".$line."][individual_alert]' value='0' >";
//        $html .= "<input type='checkbox' id='aow_actions_param[".$line."][individual_alert]' name='aow_actions_param[".$line."][individual_alert]' value='1' $checked></td>";
//        $html .= '</td>';
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= '<td id="name_label" scope="row" valign="top"><label>' . translate("LBL_ALERT",
                "AOW_Actions") . ':<span class="required">*</span></label></td>';
        $html .= '<td valign="top" scope="row">';

        $html .= '<button type="button" onclick="add_alertLine(' . $line . ')"><img src="' . SugarThemeRegistry::current()->getImageURL('id-ff-add.png') . '"></button>';
        $html .= '<table id="alertLine' . $line . '_table" width="100%" class="email-line"></table>';
        $html .= '</td>';
        $html .= "</tr>";
        $html .= "</table>";

        $html .= "<script id ='aow_script" . $line . "'>";

        //backward compatible для имейлов было: КОГДА ОДНА СТРОКА
//        if (isset($params['alert_to_type']) && !is_array($params['alert_to_type'])) {
//            $alert = $params['alert'];
////            switch ($params['alert_target_type']) {
////                case 'Specify User':
////                    $alert = $params['email_user_id'];
////                    break;
////            }
////            $alert = $params['alert_target_type'];
//            $html .= "load_alertline('" . $line . "','to','" . $params['alert_target_type'] . "','" . $alert . "');";
//        }

        if (isset($params['alert_to_type']) && !is_array($params['alert_to_type'])) {
            $alert = '';
            switch ($params['alert_target_type']) {
                case 'Specify User':
                    $alert = $params['email_user_id'];
                    break;
            }
            $html .= "load_alertline('" . $line . "','" . $params['alert_target_type'] . "','" . $params['alert_to_type'] . "','" . $alert . "');";
        }

        //end backward compatible
        // для имейла: если передан массив юзеров, кому будет отправлено мыло

        if (isset($params['alert_to_type'])) {
            foreach ($params['alert_target_type'] as $key => $field) {
                if (is_array($params['alert'][$key])) $params['alert'][$key] = json_encode($params['alert'][$key]);
                $html .= "load_alertline('" . $line . "','" . $params['alert_to_type'][$key] . "','" . $params['alert_target_type'][$key] . "','" . $params['alert'][$key]
                    . "','" . $params['alert_name'][$key] . "','" . $params['alert_link'][$key] . "','" . $params['alert_message'][$key] . "');";
            }
        }
        $html .= "</script>";

        return $html;

    }

    private function getalertsFromParams(SugarBean $bean, $params)
    {

        $alerts = array();
        //backward compatible
        if (isset($params['alert_to_type']) && !is_array($params['alert_to_type'])) {
            $alert = '';
            switch ($params['alert_to_type']) {
                case 'Email Address':
                    $params['alert'] = array($params['alert']);
                    break;
                case 'Specify User':
                    $params['alert'] = array($params['alert_user_id']);
                    break;
                case 'Related Field':
                    $params['alert'] = array($params['alert_target']);
                    break;
            }
            $params['alert_to_type'] = array($params['alert_to_type']);
            $params['alert_to_type'] = array('to');
        }
        //end backward compatible
        if (isset($params['alert_to_type'])) {
            foreach ($params['alert_to_type'] as $key => $field) {
                switch ($field) {
                    case 'Email Address':
                        if (trim($params['alert'][$key]) != '')
                            $alerts[$params['alert_to_type'][$key]][] = $params['alert'][$key];
                        break;
                    case 'Specify User':
                        $user = new User();
                        $user->retrieve($params['alert'][$key]);
                        $user_alert = $user->alertAddress->getPrimaryAddress($user);
                        if (trim($user_alert) != '') {
                            $alerts[$params['alert_to_type'][$key]][] = $user_alert;
                            $alerts['template_override'][$user_alert] = array('Users' => $user->id);
                        }

                        break;
                    case 'Users':
                        $users = array();
                        switch ($params['alert'][$key][0]) {
                            Case 'security_group':
                                if (file_exists('modules/SecurityGroups/SecurityGroup.php')) {
                                    require_once('modules/SecurityGroups/SecurityGroup.php');
                                    $security_group = new SecurityGroup();
                                    $security_group->retrieve($params['alert'][$key][1]);
                                    $users = $security_group->get_linked_beans('users', 'User');
                                    $r_users = array();
                                    if ($params['alert'][$key][2] != '') {
                                        require_once('modules/ACLRoles/ACLRole.php');
                                        $role = new ACLRole();
                                        $role->retrieve($params['alert'][$key][2]);
                                        $role_users = $role->get_linked_beans('users', 'User');
                                        foreach ($role_users as $role_user) {
                                            $r_users[$role_user->id] = $role_user->name;
                                        }
                                    }
                                    foreach ($users as $user_id => $user) {
                                        if ($params['alert'][$key][2] != '' && !isset($r_users[$user->id])) {
                                            unset($users[$user_id]);
                                        }
                                    }
                                    break;
                                }
                            //No Security Group module found - fall through.
                            Case 'role':
                                require_once('modules/ACLRoles/ACLRole.php');
                                $role = new ACLRole();
                                $role->retrieve($params['alert'][$key][2]);
                                $users = $role->get_linked_beans('users', 'User');
                                break;
                            Case 'all':
                            default:
                                global $db;
                                $sql = "SELECT id from users WHERE status='Active' AND portal_only=0 ";
                                $result = $db->query($sql);
                                while ($row = $db->fetchByAssoc($result)) {
                                    $user = new User();
                                    $user->retrieve($row['id']);
                                    $users[$user->id] = $user;
                                }
                                break;
                        }
                        foreach ($users as $user) {
                            $user_alert = $user->alertAddress->getPrimaryAddress($user);
                            if (trim($user_alert) != '') {
                                $alerts[$params['alert_to_type'][$key]][] = $user_alert;
                                $alerts['template_override'][$user_alert] = array('Users' => $user->id);
                            }
                        }
                        break;
                    case 'Related Field':
                        $alertTarget = $params['alert'][$key];
                        $relatedFields = array_merge($bean->get_related_fields(), $bean->get_linked_fields());
                        $field = $relatedFields[$alertTarget];
                        if ($field['type'] == 'relate') {
                            $linkedBeans = array();
                            $idName = $field['id_name'];
                            $id = $bean->$idName;
                            $linkedBeans[] = BeanFactory::getBean($field['module'], $id);
                        } else if ($field['type'] == 'link') {
                            $relField = $field['name'];
                            if (isset($field['module']) && $field['module'] != '') {
                                $rel_module = $field['module'];
                            } else if ($bean->load_relationship($relField)) {
                                $rel_module = $bean->$relField->getRelatedModuleName();
                            }
                            $linkedBeans = $bean->get_linked_beans($relField, $rel_module);
                        } else {
                            $linkedBeans = $bean->get_linked_beans($field['link'], $field['module']);
                        }
                        if ($linkedBeans) {
                            foreach ($linkedBeans as $linkedBean) {
                                if (!empty($linkedBean)) {
                                    $rel_alert = $linkedBean->alertAddress->getPrimaryAddress($linkedBean);
                                    if (trim($rel_alert) != '') {
                                        $alerts[$params['alert_to_type'][$key]][] = $rel_alert;
                                        $alerts['template_override'][$rel_alert] = array($linkedBean->module_dir => $linkedBean->id);
                                    }
                                }
                            }
                        }
                        break;
                    case 'Record Email':
                        $recordEmail = $bean->alertAddress->getPrimaryAddress($bean);
                        if ($recordEmail == '' && isset($bean->alert1)) $recordEmail = $bean->alert1;
                        if (trim($recordEmail) != '')
                            $alerts[$params['alert_to_type'][$key]][] = $recordEmail;
                        break;
                }
            }
        }
        return $alerts;
    }

    public function run_action(SugarBean $bean, $params = array(), $in_save = false)
    {
//        $record = BeanFactory::newBean('Alerts');
        if (isset($params['alert_target_type']) && !empty($params['alert_target_type'])) {
            foreach ($params['alert_target_type'] as $key => $alert_type) {
                if (!is_array($params['alert'][$key])) {
                    $record = BeanFactory::newBean('Alerts');
                    $record->name = $params['alert_name'][$key];
                    $record->description = $params['alert_message'][$key];
                    $record->url_redirect = $params['alert_link'][$key];
//                $record->target_module = 'Account';
                    $record->assigned_user_id = $params['alert'][$key];
                    $record->type = $params['alert_target_type'][$key];
                    $record->is_read = 0;
                    $record->save();
                }
                else {
                    switch ($params['alert'][$key][0]){
                        case 'all':
                            $users = get_user_array(false);
                            $GLOBALS['log']->logLevel($users);
                            foreach ($users as $u_id => $u_name){
                                $record = BeanFactory::newBean('Alerts');
                                $record->name = $params['alert_name'][$key];
                                $record->description = $params['alert_message'][$key];
                                $record->url_redirect = $params['alert_link'][$key];
//                $record->target_module = 'Account';
                                $record->assigned_user_id = $u_id;
                                $record->type = $params['alert_target_type'][$key];
                                $record->is_read = 0;
                                $record->save();
                            }
                            break;
                        case 'security_group':
                        case 'role':
                            foreach ($params['alert'][$key] as $k => $v){
                                if ($k === 0 || empty($v)){
                                    continue;
                                }
                                $record = BeanFactory::newBean('Alerts');
                                $record->name = $params['alert_name'][$key];
                                $record->description = $params['alert_message'][$key];
                                $record->url_redirect = $params['alert_link'][$key];
//                $record->target_module = 'Account';
                                $record->assigned_user_id = $v;
                                $record->type = $params['alert_target_type'][$key];
                                $record->is_read = 0;
                                $record->save();
                            }
                            break;
                    }
                }
            }
        }

        return true;

    }

    function parse_template(SugarBean $bean, &$template, $object_override = array())
    {
        global $sugar_config;

        require_once('modules/AOW_Actions/actions/templateParser.php');

        $object_arr[$bean->module_dir] = $bean->id;

        foreach ($bean->field_defs as $bean_arr) {
            if ($bean_arr['type'] == 'relate') {
                if (isset($bean_arr['module']) && $bean_arr['module'] != '' && isset($bean_arr['id_name']) && $bean_arr['id_name'] != '' && $bean_arr['module'] != 'EmailAddress') {
                    $idName = $bean_arr['id_name'];
                    if (isset($bean->field_defs[$idName]) && $bean->field_defs[$idName]['source'] != 'non-db') {
                        if (!isset($object_arr[$bean_arr['module']])) $object_arr[$bean_arr['module']] = $bean->$idName;
                    }
                }
            } else if ($bean_arr['type'] == 'link') {
                if (!isset($bean_arr['module']) || $bean_arr['module'] == '') $bean_arr['module'] = getRelatedModule($bean->module_dir, $bean_arr['name']);
                if (isset($bean_arr['module']) && $bean_arr['module'] != '' && !isset($object_arr[$bean_arr['module']]) && $bean_arr['module'] != 'EmailAddress') {
                    $linkedBeans = $bean->get_linked_beans($bean_arr['name'], $bean_arr['module'], array(), 0, 1);
                    if ($linkedBeans) {
                        $linkedBean = $linkedBeans[0];
                        if (!isset($object_arr[$linkedBean->module_dir])) $object_arr[$linkedBean->module_dir] = $linkedBean->id;
                    }
                }
            }
        }

        $object_arr['Users'] = is_a($bean, 'User') ? $bean->id : $bean->assigned_user_id;

        $object_arr = array_merge($object_arr, $object_override);

        $parsedSiteUrl = parse_url($sugar_config['site_url']);
        $host = $parsedSiteUrl['host'];
        if (!isset($parsedSiteUrl['port'])) {
            $parsedSiteUrl['port'] = 80;
        }

        $port = ($parsedSiteUrl['port'] != 80) ? ":" . $parsedSiteUrl['port'] : '';
        $path = !empty($parsedSiteUrl['path']) ? $parsedSiteUrl['path'] : "";
        $cleanUrl = "{$parsedSiteUrl['scheme']}://{$host}{$port}{$path}";

        $url = $cleanUrl . "/index.php?module={$bean->module_dir}&action=DetailView&record={$bean->id}";

        $template->subject = str_replace("\$contact_user", "\$user", $template->subject);
        $template->body_html = str_replace("\$contact_user", "\$user", $template->body_html);
        $template->body = str_replace("\$contact_user", "\$user", $template->body);
        $template->subject = aowTemplateParser::parse_template($template->subject, $object_arr);
        $template->body_html = aowTemplateParser::parse_template($template->body_html, $object_arr);
        $template->body_html = str_replace("\$url", $url, $template->body_html);
        $template->body_html = str_replace("\$sugarurl", $cleanUrl, $template->body_html);
        $template->body = aowTemplateParser::parse_template($template->body, $object_arr);
        $template->body = str_replace("\$url", $url, $template->body);
        $template->body = str_replace("\$sugarurl", $cleanUrl, $template->body);
    }

    function getAttachments(EmailTemplate $template)
    {

        $attachments = array();
        if ($template->id != '') {
            $note_bean = new Note();
            $notes = $note_bean->get_full_list('', "parent_type = 'Emails' AND parent_id = '" . $template->id . "'");

            if ($notes != null) {
                foreach ($notes as $note) {
                    $attachments[] = $note;
                }
            }
        }
        return $attachments;
    }

//    function sendEmail($alertTo, $alertSubject, $alertBody, $altalertBody, SugarBean $relatedBean = null, $emailCc = array(), $emailBcc = array(), $attachments = array())
//    {
//        require_once('modules/Emails/Email.php');
//        require_once('include/SugarPHPMailer.php');
//
//        $emailObj = new Email();
//        $defaults = $emailObj->getSystemDefaultEmail();
//        $mail = new SugarPHPMailer();
//        $mail->setMailerForSystem();
//        $mail->From = $defaults['email'];
//        $mail->FromName = $defaults['name'];
//        $mail->ClearAllRecipients();
//        $mail->ClearReplyTos();
//        $mail->Subject=from_html($emailSubject);
//        $mail->Body=$emailBody;
//        $mail->AltBody = $altemailBody;
//        $mail->handleAttachments($attachments);
//        $mail->prepForOutbound();
//
//        if(empty($emailTo)) return false;
//        foreach($emailTo as $to){
//            $mail->AddAddress($to);
//        }
//        if(!empty($emailCc)){
//            foreach($emailCc as $email){
//                $mail->AddCC($email);
//            }
//        }
//        if(!empty($emailBcc)){
//            foreach($emailBcc as $email){
//                $mail->AddBCC($email);
//            }
//        }
//
//        //now create email
//        if (@$mail->Send()) {
//            $emailObj->to_addrs= implode(',',$emailTo);
//            $emailObj->cc_addrs= implode(',',$emailCc);
//            $emailObj->bcc_addrs= implode(',',$emailBcc);
//            $emailObj->type= 'out';
//            $emailObj->deleted = '0';
//            $emailObj->name = $mail->Subject;
//            $emailObj->description = $mail->AltBody;
//            $emailObj->description_html = $mail->Body;
//            $emailObj->from_addr = $mail->From;
//            if ( $relatedBean instanceOf SugarBean && !empty($relatedBean->id) ) {
//                $emailObj->parent_type = $relatedBean->module_dir;
//                $emailObj->parent_id = $relatedBean->id;
//            }
//            $emailObj->date_sent = TimeDate::getInstance()->nowDb();
//            $emailObj->modified_user_id = '1';
//            $emailObj->created_by = '1';
//            $emailObj->status = 'sent';
//            $emailObj->save();
//
//            // Fix for issue 1561 - Email Attachments Sent By Workflow Do Not Show In Related Activity.
//            foreach($attachments as $attachment) {
//                $note = new Note();
//                $note->id = create_guid();
//                $note->date_entered = $attachment->date_entered;
//                $note->date_modified = $attachment->date_modified;
//                $note->modified_user_id = $attachment->modified_user_id;
//                $note->assigned_user_id = $attachment->assigned_user_id;
//                $note->new_with_id = true;
//                $note->parent_id = $emailObj->id;
//                $note->parent_type = $attachment->parent_type;
//                $note->name = $attachment->name;;
//                $note->filename = $attachment->filename;
//                $note->file_mime_type = $attachment->file_mime_type;
//                $fileLocation = "upload://{$attachment->id}";
//                $dest = "upload://{$note->id}";
//                if(!copy($fileLocation, $dest)) {
//                    $GLOBALS['log']->debug("EMAIL 2.0: could not copy attachment file to $fileLocation => $dest");
//                }
//                $note->save();
//            }
//            return true;
//        }
//        return false;
//    }

}
