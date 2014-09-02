<?php
if ( ! function_exists('redirecionar'))
{
	function redirecionar($location = '')
	{
		if(!headers_sent()){
			header('location: ' . base_url() . urldecode($location), true, 303);
			exit();
		
		}else{
			echo '<meta http-equiv="refresh" content="0; url=' . base_url() . urldecode($location) . '"/>';
			exit();
		}
	}
}

if ( ! function_exists('a_delete_file'))
{
	function a_delete_file($type, $path, $file)
	{
		$path = FCPATH.'uploads/'.$type.'/'.$path.'/';
		$r = unlink($path.$file);
		if($type == 'imagens' || $type == 'logos'){
			$file = str_ireplace('.', '_thumb.', $file);
			unlink($path.$file);
		}
		return $r;
	}
}

/* End of file funcoes_helper.php */
/* Location: ./application/helpers/funcoes_helper.php */