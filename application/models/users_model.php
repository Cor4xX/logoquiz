<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Users_model extends CI_Model {
	private $table      = "t_user";

	public $id          = "";
	public $name        = "";
	public $firstname   = "";
	public $mail        = "";
	public $birthday    = "";
	public $facebook_id = "";
	public $datecreate  = "";
	public $lastlog     = "";
	
	public function get_by_id($id) {
		return $this->db->select ( $this->table.'.*' )
						->select ( 'COALESCE((SELECT SUM(score_point) FROM t_score as s WHERE s.score_id_user = '.$id.'), 0) as point', FALSE )
						->from ( $this->table )
						->where ( 'user_id', $id )
					->get ()->result ();
	}

	public function get_by_facebook_id($facebook_id) {
		return $this->db->select ( $this->table.'.*' )
						->from ( $this->table )
						->where ( 'user_facebook_id', $facebook_id )
					->get ()->result ();
	}

	public function get_ranking_by_id($id){
		return $this->db->query('
			SELECT id, name, score, FIND_IN_SET( score, (
			SELECT GROUP_CONCAT( score
			ORDER BY score DESC ) 
			FROM scores )
			) AS rank
			FROM scores')
		->result();

	}

	public function listing($limit = "") {
		if($limit != ""){
			return $this->db->select ( $this->table.'.*' )
						->select ( '(SELECT SUM(score_point) FROM t_score as s WHERE s.score_id_user = '.$this->table.'.user_id) as point', FALSE )
						->from ( $this->table )
						->order_by('point', 'DESC')
						->limit($limit)
					->get ()->result ();	
		}
		else{
			return $this->db->select ( $this->table.'.*' )
						->select ( '(SELECT SUM(score_point) FROM t_score as s WHERE s.score_id_user = '.$this->table.'.user_id) as point', FALSE )
						->from ( $this->table )
						->order_by('point', 'DESC')
					->get ()->result ();	
		}
	}

	public function insert($user) {
		$this->db->set ( 'user_name', $user->name);
		$this->db->set ( 'user_firstname', $user->firstname);
		$this->db->set ( 'user_mail', $user->mail);
		$this->db->set ( 'user_birthday', $user->birthday);
		$this->db->set ( 'user_facebook_id', $user->facebook_id);
		$this->db->set ( 'user_datecreate', $user->datecreate);
		$this->db->set ( 'user_lastlog', $user->lastlog);
		return $this->db->insert ( $this->table );
	}

	public function update($user) {
		if(isset($user->name)){
			$this->db->set('user_name', $user->name);
		}
		if(isset($user->firstname)){
			$this->db->set('user_firstname', $user->firstname);
		}
		if(isset($user->mail)){
			$this->db->set('user_mail', $user->mail);
		}
		if(isset($user->birthday)){
			$this->db->set('user_birthday', $user->birthday);
		}
		if(isset($user->lastlog)){
			$this->db->set('user_lastlog', $user->lastlog);
		}
		$this->db->where('user_id', $user->id);
		return $this->db->update('t_user'); 
	}
}
?>