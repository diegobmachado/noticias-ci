<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * 
	 */
	public function index()
	{
		$this->smartyci->assign('cover', true);

		$this->smartyci->display('header.html');
		$this->smartyci->display('welcome_message.html');
		$this->smartyci->display('footer.html');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */