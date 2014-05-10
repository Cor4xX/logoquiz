<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Quizzes_model extends CI_Model {
	private $table      = "t_quiz";

	public $id          = "";
	public $name        = "";
	public $picture     = "";
	public $limit_point = "";
	
	public function get_by_id($id) {
		return $this->db->select ( $this->table.'.*' )
						->from ( $this->table )
						->where ( 'quiz_id', $id )
					->get ()->result ();
	}

	public function listing() {
		return $this->db->select ( $this->table.'.*' )
						->from ( $this->table )
					->get ()->result ();
	}

	public function get_by_user($user_id) {

		return $this->db->query ('
					SELECT quiz.*, COUNT(level_id) as count_level 
					FROM t_quiz as quiz
					LEFT OUTER JOIN t_level as level ON level.level_id_quiz = quiz.quiz_id
					GROUP BY quiz_id
				')
			->result ();
	}

	public function get_quiz_details($quiz_id){
		$user_id = $this->session->userdata('user_id');
		return $this->db->query ('
					SELECT SUM(score_point) as sum_score, COUNT(score_point) as count_level_validate, score_id_user  
					FROM t_score as score
					LEFT JOIN t_level as level ON level.level_id = score.score_id_level
					LEFT JOIN t_quiz as quiz ON quiz.quiz_id = level.level_id_quiz
					WHERE quiz_id = '.$quiz_id.'
					AND 
					(score.score_id_user = '.$user_id.' OR score.score_id_user IS NULL)
				')
			->result ();
	}

	public function get_levels($quiz_id) {
		$user_id = $this->session->userdata('user_id');

		return $this->db->query ('
					SELECT level_id, logo_name, logo_picture
					FROM t_level AS level 
					LEFT JOIN t_response AS response ON response.response_id_level = level.level_id
					RIGHT JOIN t_logo AS logo ON logo.logo_id = response.response_id_logo
					WHERE level.level_id_quiz = '.$quiz_id.'
					GROUP BY level_id
					ORDER BY level_order ASC
				')
			->result ();
	}

	public function get_level_details($level_id){
		$user_id = $this->session->userdata('user_id');
		$query = $this->db->query ('
					SELECT COUNT(DISTINCT(submit_id)) as count_submit
					FROM t_level AS level 
					LEFT JOIN t_played AS played ON played.played_id_level = level.level_id
					LEFT JOIN t_submit AS submit ON submit.submit_id_played = played.played_id
					WHERE level.level_id = '.$level_id.'
					AND played.played_id_user = '.$user_id.'
					AND submit.submit_datecreate >= ( NOW() - INTERVAL 1 DAY )
					GROUP BY level_id
				');
		if($query != false){
			return $query->result ();
		}	
		else{
			return null;
		}
	}

	public function get_level_score($level_id){
		$user_id = $this->session->userdata('user_id');
		$query = $this->db->query ('
					SELECT score_id_user
					FROM t_score AS score 
					WHERE score_id_level = '.$level_id.'
					AND (score_id_user IS NULL 
					OR score_id_user = '.$user_id.')
				');
		if($query != false){
			return $query->result ();
		}	
		else{
			return null;
		}
	}
}
?>