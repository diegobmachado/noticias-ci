<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_noticias extends CI_Model {

	/**
	 * 
	 */
	function listar($ordem)
	{
		$this->db->select('id, titulo, imagem');
		$this->db->select('DATE_FORMAT(data, "%d/%m/%Y") AS data_br', false);
		$this->db->order_by($ordem[0], $ordem[1]);
		$query = $this->db->get('noticias_ag2');
		return ($query->num_rows() != 0) ? $query->result() : array();
	}
	
	function adicionar($dados)
	{
		$this->db->insert('noticias_ag2', $dados);
		return ($this->db->affected_rows() != 0) ? true : false;
	}
	
	function imagem($id)
	{
		$this->db->select('imagem');
		$this->db->where('id', $id);
		$query = $this->db->get('noticias_ag2');
		return ($query->num_rows() != 0) ? $query->row()->imagem : '';
	}
	
	function remover($id)
	{
		$this->db->delete('noticias_ag2', array('id' => $id));
		return ($this->db->affected_rows() != 0) ? true : false;
	}
	
	function ler($id)
	{
		$this->db->select('titulo, corpo, imagem');
		$this->db->select('DATE_FORMAT(data, "%d/%m/%Y") AS data_br', false);
		$this->db->where('id', $id);
		$query = $this->db->get('noticias_ag2');
		return ($query->num_rows() != 0) ? $query->row() : false();
	}
	
	function editar($dados, $id)
	{
		$this->db->where('id', $id);
		$this->db->update('noticias_ag2', $dados);
		return ($this->db->affected_rows() != 0) ? true : false;
	}
}

/* End of file m_noticias.php */
/* Location: ./application/models/m_noticias.php */