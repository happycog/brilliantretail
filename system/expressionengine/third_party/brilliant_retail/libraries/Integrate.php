<?php
class Integrate extends Brilliant_retail_core{
	
	public $service_path;
	
	function __construct()
	{
		parent::__construct();
		$this->service_path = PATH_THIRD.'brilliant_retail/core/service/';	
	}
	
	function __call($name,$args)
	{
		$parts 	= explode('_',$name);
		$class 	= strtolower($parts[0]);
		$method = strtolower($parts[1]); 
		$file 	= $this->service_path.'service'.$class.'.php';
		if(file_exists($file)){
			include_once($file);
			$service = new $class();
			return $service->$method();
		}else{
			$error  = lang('error_tag_module_processing').'<br /><br />';
			$error .= '{exp:brilliant_retail:'.strtolower($name).'}';              
			$error .= str_replace('%x','BrilliantRetail', str_replace('%y',$name, lang('error_fix_module_processing')));
			$this->EE->output->fatal_error($error);  		
		}
	}
}