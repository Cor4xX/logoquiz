<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Manage_model extends CI_Model {
	
	public function get_logos() {
		return $this->db->select ( '*' )
						->from ( 't_logo' )
						->order_by ( 'logo_name', 'asc' )
					->get ()->result ();
	}

	public function get_levels() {
		return $this->db->select ( '*' )
						->from ( 't_level' )
						->order_by ( 'level_id', 'asc' )
					->get ()->result ();
	}

	public function create_response($level_id, $good_answer, $bad_answer_1, $bad_answer_2, $bad_answer_3, $bad_answer_4, $bad_answer_5){

		$this->db->trans_start();

		$this->db->set ( 'response_id_level', $level_id);
		$this->db->set ( 'response_id_logo', $good_answer);
		$this->db->set ( 'response_correct', 1);
		$this->db->insert ( 't_response' );

		$this->db->set ( 'response_id_level', $level_id);
		$this->db->set ( 'response_id_logo', $bad_answer_1);
		$this->db->set ( 'response_correct', 0);
		$this->db->insert ( 't_response' );

		$this->db->set ( 'response_id_level', $level_id);
		$this->db->set ( 'response_id_logo', $bad_answer_2);
		$this->db->set ( 'response_correct', 0);
		$this->db->insert ( 't_response' );

		$this->db->set ( 'response_id_level', $level_id);
		$this->db->set ( 'response_id_logo', $bad_answer_3);
		$this->db->set ( 'response_correct', 0);
		$this->db->insert ( 't_response' );

		$this->db->set ( 'response_id_level', $level_id);
		$this->db->set ( 'response_id_logo', $bad_answer_4);
		$this->db->set ( 'response_correct', 0);
		$this->db->insert ( 't_response' );

		$this->db->set ( 'response_id_level', $level_id);
		$this->db->set ( 'response_id_logo', $bad_answer_5);
		$this->db->set ( 'response_correct', 0);
		$this->db->insert ( 't_response' );

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
		   return false;
		}
		else{
			return true;
		}
	}

}
?>