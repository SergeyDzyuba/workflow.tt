<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
 
require_once('include/MVC/View/views/view.edit.php');
 
class AOW_WorkFlowViewEdit extends ViewEdit 
{
 
 	function AOW_WorkFlowViewEdit()
 	{
 		parent::ViewEdit();
 	}
 
	public function preDisplay()
 	{

		if ( ( isset($_REQUEST['metafile']) AND !empty($_REQUEST['metafile']) ) || ( !empty($this->bean->process_id) ) )
		{
			$metaFileName = 'editviewsubprocessdefs';
		}

		if(file_exists('custom/modules/' . $this->module . '/metadata/' . $metaFileName . '.php'))
		{
			$metadataFile = 'custom/modules/' . $this->module . '/metadata/' . $metaFileName . '.php';
 		}
		elseif (file_exists('modules/' . $this->module . '/metadata/' . $metaFileName . '.php'))
		{
			$metadataFile = 'modules/' . $this->module . '/metadata/' . $metaFileName . '.php';
		}
		else
		{
			$metadataFile = 'modules/' . $this->module . '/metadata/editviewdefs.php';
		}

 		$this->ev = new EditView();
 		$this->ev->ss = $this->ss;
 		$this->ev->setup($this->module, $this->bean, $metadataFile, 'include/EditView/EditView.tpl');
 	}
}
?>