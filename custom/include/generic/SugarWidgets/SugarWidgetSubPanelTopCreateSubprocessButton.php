<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class SugarWidgetSubPanelTopCreateSubprocessButton extends SugarWidgetSubPanelTopButton
{
    public function getWidgetId()
    {
        return parent::getWidgetId();
    }

	function display($defines)
	{
		global $app_strings, $currentModule, $db;

		$title = $app_strings['LBL_NEW_BUTTON_TITLE'];
		//$accesskey = $app_strings['LBL_NEW_BUTTON_KEY'];
		$value = $app_strings['LBL_NEW_BUTTON_LABEL'];
		$this->module = 'AOW_WorkFlow';
		if( ACLController::moduleSupportsACL($defines['module'])  && !ACLController::checkAccess($defines['module'], 'edit', true)){
			$button = "<input title='$title'class='button' type='button' name='button' value='  $value  ' disabled/>\n";
			return $button;
		}

		// echo '<pre>';
		// print_r($defines['focus']);
		// echo '</pre>';

		$additionalFormFields = array();
		if(isset($defines['focus']->id)){

			$sql = "SELECT MAX(subprocess_sequence_number) AS max_sequence_number FROM aow_workflow WHERE deleted = 0 AND process_id = '{$defines['focus']->id}' ";
			$result = $db->query($sql);
			$row = $db->fetchByAssoc($result);
			if( !empty($row) ){
				$additionalFormFields['subprocess_sequence_number'] = $row['max_sequence_number']+1;
			}else{
				$additionalFormFields['subprocess_sequence_number'] = 1;
			}

			$additionalFormFields['process_id'] = $defines['focus']->id;
		}
		if(isset($defines['focus']->name)){
			$additionalFormFields['process_name'] = $defines['focus']->name;						  		
		}

		$button = $this->_get_form($defines, $additionalFormFields);
		$button .= "<input type='hidden' name='metafile' value='editviewsubprocessdefs'/>\n";
		$button .= "<input title='$title' class='button' type='submit' name='{$this->getWidgetId()}' id='{$this->getWidgetId()}' value='  $value  '/>\n";
		$button .= "</form>";
		return $button;
	}
}
?>