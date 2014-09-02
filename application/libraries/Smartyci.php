<?php
if ( ! defined('BASEPATH') )
	exit('No direct script access allowed');
	
require_once('application/third_party/Smarty/libs/Smarty.class.php');

class Smartyci extends Smarty
{ 
	public function __construct()
	{ 
		parent::__construct();
		$config =& get_config();
		
		$this->template_dir = APPPATH. "views/";
		$this->compile_dir = APPPATH. "cache/";
	}

	public function view($resource_name, $data = array(),  $cache_id = null)
	{
		foreach($data as $item => $key){
			$this->assign($item, $key);
		}
		
		if(strpos($resource_name, ".") === false){
			$resource_name = ".tpl";
		}
		
		return parent::display($resource_name, $cache_id);
	}
}