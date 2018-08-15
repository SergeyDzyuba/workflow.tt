<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
 
require_once('include/MVC/View/views/view.detail.php');
 
class AOW_WorkFlowViewDetail extends ViewDetail 
{
 
 	function AOW_WorkFlowViewDetail()
 	{
 		parent::ViewDetail();
 	}
 
	public function preDisplay()
 	{
 	    parent::preDisplay();
		if ( ( isset($_REQUEST['metafile']) AND !empty($_REQUEST['metafile']) ) || ( !empty($this->bean->process_id) ) )
		{
			$metaFileName = 'detailviewsubprocessdefs';
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
			$metadataFile = 'modules/' . $this->module . '/metadata/detailviewdefs.php';
		}

 		$this->dv = new DetailView2();
 		$this->dv->ss = $this->ss;
 		$this->dv->setup($this->module, $this->bean, $metadataFile, 'include/DetailView/DetailView.tpl');
 	}
}
?>