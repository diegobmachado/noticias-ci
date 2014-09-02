<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Noticias extends CI_Controller {

	/**
	 * 
	 */
	public function index($ordem = '')
	{
		$ordenacoes = array('titulo-asc', 'titulo-desc', 'data-asc', 'data-desc');

		$ordem = (in_array($ordem, $ordenacoes)) ? explode('-', $ordem) : array('data', 'desc');
		$this->smartyci->assign('ordem', $ordem[1]);

		$noticias = $this->m_noticias->listar($ordem);
		$this->smartyci->assign('noticias', $noticias);
		
		if($this->session->flashdata('erro')){
			$this->smartyci->assign('erro', $this->session->flashdata('erro'));
		}
		
		if($this->session->flashdata('msg')){
			$this->smartyci->assign('msg', $this->session->flashdata('msg'));
		}
		
		$this->smartyci->display('header.html');
		$this->smartyci->display('listar.html');
		$this->smartyci->display('footer.html');
	}

	public function adicionar()
	{

		if($this->session->flashdata('erro')){
			$this->smartyci->assign('erro', $this->session->flashdata('erro'));
		}

		$this->smartyci->display('header.html');
		$this->smartyci->display('adicionar.html');
		$this->smartyci->display('footer.html');
	}

	public function adicionar_noticia()
	{
		if($this->input->post() !== false){
			$dados = $this->input->post(null, 'true');

			if(count($_FILES) > 0 && !empty($_FILES['imagem']['name'])){
				$up = $this->upload();
				if($up['succeed']){
					$dados['imagem'] = $up['file']['file_name'];
				} else {
					$this->session->set_flashdata('erro', $up['error']);
					redirecionar('noticias/adicionar/');
				}
			}
			
			$dados = $this->m_noticias->adicionar($dados);
			if($dados != false){
				$this->session->set_flashdata('msg', 'Registro adicionado com sucesso.');
				redirecionar('noticias/');
			}else{
				$this->session->set_flashdata('erro', 'Erro com banco de dados.');
				redirecionar('noticias/adicionar/');
			}
		}else{
			redirecionar();
		}
	}
	
	protected function upload()
	{
		$path = 'uploads/imagens/';
		
		if(!is_dir(FCPATH.$path)){
			mkdir(FCPATH.$path);
		}
		
		$config = array();
		$config['allowed_types'] = 'gif|jpg|png';
		$config['encrypt_name'] = true;
		$config['upload_path'] = $path;
		$config['max_size']	= '6144';
		// 6 Mb | double full hd width
		$config['max_width']  = '3840';
		$config['max_height']  = '3840';
		
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload('imagem')){
			return array('succeed' => false, 'file' => array(), 'error' => $this->upload->display_errors());
			
		}else{
			return array('succeed' => true, 'file' => $this->upload->data(), 'error' => '');
		}
	}
	
	protected function excluir_imagem($img)
	{
		$path = FCPATH.'uploads/imagens/';
		if(is_file($path.$img)){
			return unlink($path.$img);
		
		}else{
			return false;
		}
	}
	
	public function remover($id)
	{
		if(filter_var($id, FILTER_VALIDATE_INT) !== false){
			$img = $this->m_noticias->imagem($id);
			if($img != ''){
				$this->excluir_imagem($img);
			}
			
			$dados = $this->m_noticias->remover($id);
			if($dados != false){
				$this->session->set_flashdata('msg', 'Registro removido com sucesso.');
				redirecionar('noticias/');
			}else{
				$this->session->set_flashdata('erro', 'Erro com banco de dados.');
				redirecionar('noticias/');
			}
		
		}else{
			redirecionar();
		}
	}
	
	public function editar($id)
	{
		if(filter_var($id, FILTER_VALIDATE_INT) !== false){
			if($this->session->flashdata('erro')){
				$this->smartyci->assign('erro', $this->session->flashdata('erro'));
			}
			
			$dados = $this->m_noticias->ler($id);
			if($dados != false){
				$this->smartyci->assign('id', $id);
				$this->smartyci->assign('dados', $dados);
				$this->smartyci->display('header.html');
				$this->smartyci->display('editar.html');
				$this->smartyci->display('footer.html');
			
			}else{
				redirecionar('noticias/');
			}
		
		}else{
			redirecionar();
		}
	}
	
	public function editar_noticia()
	{
		$dados = $this->input->post(null, true);
		
		if($this->input->post() !== false && filter_var($dados['id'], FILTER_VALIDATE_INT) !== false){
			$id = $dados['id'];
			unset($dados['id']);
			
			if($this->input->post('rem_imagem')){
				if(!$this->excluir_imagem($dados['rem_imagem'])){
					$this->session->set_flashdata('erro', 'Erro ao tentar excluir arquivo.');
					redirecionar('noticias/editar/'.$id);
				}
				$dados['imagem'] = '';
				unset($dados['rem_imagem']);
			}
			
			if(count($_FILES) > 0 && !empty($_FILES['imagem']['name'])){
				$up = $this->upload();
				if($up['succeed']){
					$dados['imagem'] = $up['file']['file_name'];
				} else {
					$this->session->set_flashdata('erro', $up['error']);
					redirecionar('noticias/editar/'.$id);
				}
			}
			
			$dados = $this->m_noticias->editar($dados, $id);
			if($dados != false){
				$this->session->set_flashdata('msg', 'As modificações foram realizadas com sucesso.');
				redirecionar('noticias/');
			}else{
				$this->session->set_flashdata('erro', 'Erro com banco de dados.');
				redirecionar('noticias/adicionar/');
			}
		
		}else{
			redirecionar();
		}
	}
	
	public function ler($id)
	{
		if(filter_var($id, FILTER_VALIDATE_INT) !== false){
			if($this->session->flashdata('erro')){
				$this->smartyci->assign('erro', $this->session->flashdata('erro'));
			}
			
			$dados = $this->m_noticias->ler($id);
			if($dados != false){
				$this->smartyci->assign('dados', $dados);
				$this->smartyci->display('header.html');
				$this->smartyci->display('ler.html');
				$this->smartyci->display('footer.html');
			
			}else{
				redirecionar('noticias/');
			}
		
		}else{
			redirecionar();
		}
	}
}

/* End of file noticias.php */
/* Location: ./application/controllers/noticias.php */