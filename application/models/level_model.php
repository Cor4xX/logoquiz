<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Level_model extends CI_Model {
	private $table      = "t_level";

	public $id          = "";
	public $name        = "";
	public $picture     = "";
	public $indice		= "";

	private $point = 10;
	
	public function get_logo_by_id_level($id)
	{
		return $this->db->query ('
					SELECT rep.*, logo.*
					FROM   t_response as rep
					       INNER JOIN t_logo as logo
					             ON rep.response_id_logo = logo.logo_id
					WHERE  rep.response_id_level = '.$id)
			->result();
	}

	public function get_quiz_by_level($id)
	{
		return $this->db->query ('
					SELECT quiz.*
					FROM   t_level as lvl
					       INNER JOIN t_quiz as quiz
					             ON lvl.level_id_quiz = quiz.quiz_id
					WHERE  lvl.level_id = '.$id)
			->result();
	}


	public function check_response($id_level, $id_response)
	{

		$user_id = $this->session->userdata('user_id');

		$count_submit = $this->db->query ('
					SELECT COUNT(submit_id) as count
					FROM   t_played
					LEFT JOIN t_submit ON t_submit.submit_id_played = t_played.played_id
					WHERE  played_id_user = '.$user_id.' AND played_id_level = '.$id_level.' AND submit_datecreate >= ( NOW() - INTERVAL 1 DAY )')
			->result();


		//Si il y a 2 enregistrements dans submit alors il n'a pas le droit de joué
		if($count_submit[0]->count == '2')
		{
			return "false";
		}

		//Si il y a 1 enregistrement dans submit alors il a le droit de joué
		//Création d'un nouvel enregistrement submit
		elseif($count_submit[0]->count == '1')
		{
			$played = $this->db->query ('
					SELECT played_id
					FROM   t_played
					WHERE  played_id_user = '.$user_id.' AND played_id_level = '.$id_level.' AND played_dateplayed >= ( NOW() - INTERVAL 1 DAY )')
			->result();

			$this->db->query ("
				INSERT INTO t_submit (submit_id_played, submit_id_response, submit_datecreate) VALUES (".$played[0]->played_id.", ".$id_response.", '".date('Y-m-d H:i:s')."')");

			$good = $this->db->query ('
					SELECT response_correct
					FROM   t_response
					WHERE  response_id_level = '.$id_level.' AND response_id_logo = '.$id_response)
			->result();

			if($good[0]->response_correct == '1'){
				return $good[0]->response_correct;
			}
			else{
				return "false";
			}
		}
		
		//Si il y a 0 enregistrement dans submit alors il a le droit de joué
		//Création d'un nouvel enregistrement played et submit
		elseif($count_submit[0]->count == '0')
		{
			$playedId = $this->db->query ('
			SELECT played_id
			FROM   t_played
			WHERE  played_id_user = '.$user_id.' AND played_id_level = '.$id_level.' AND played_dateplayed >= ( NOW() - INTERVAL 1 DAY )')->result();

			$this->db->query("INSERT INTO t_submit (submit_id_played, submit_id_response, submit_datecreate) VALUES (".$playedId[0]->played_id.", ".$id_response.", '".date('Y-m-d H:i:s')."')");

			return $this->db->query ('
					SELECT response_correct
					FROM   t_response
					WHERE  response_id_level = '.$id_level.' AND response_id_logo = '.$id_response)
			->result();
		}
	}

	public function get_response_by_level($id){
		$user_id = $this->session->userdata('user_id');

		return $submit = $this->db->query ('
					SELECT submit . * 
					FROM t_submit AS submit
					LEFT JOIN t_played AS p ON p.played_id = submit_id_played
					LEFT JOIN t_level AS l ON l.level_id = p.played_id_level
					WHERE p.played_id_user = '.$user_id.'
					AND l.level_id = '.$id)
			->result();
	}

	public function check_joker($id_level, $num_joker)
	{
		$user_id = $this->session->userdata('user_id');

		$joker = $this->db->query ('
					SELECT played_joker'.$num_joker.' as joker, played_id as id
					FROM   t_played
					WHERE  played_id_user = '.$user_id.' AND played_id_level = '.$id_level.' AND played_dateplayed >= ( NOW() - INTERVAL 1 DAY )')
			->result();

		return $joker[0];
	}

	public function update_joker($played_id, $num_joker){
		$updateData = array(
		    'played_joker'.$num_joker => 1
		);

		$this->db->where('played_id', $played_id);
		$this->db->update('t_played', $updateData);
	}


	public function get_played($id_level)
	{
		$id_user = $this->session->userdata('user_id');

		$played = $this->db->query ('
			SELECT *
			FROM   t_played
			WHERE  played_id_user = '.$id_user.' AND played_id_level = '.$id_level.' AND played_dateplayed >= ( NOW() - INTERVAL 1 DAY )')
		->result();

		if($played == null)
		{
			$this->db->query ("
			INSERT INTO t_played (played_id_user, played_id_level, played_joker1, played_joker2, played_dateplayed) VALUES (".$id_user.", ".$id_level.", '', '', '".date('Y-m-d H:i:s')."')");
			
			$played = $this->db->query ('
				SELECT *
				FROM   t_played
				WHERE  played_id_user = '.$id_user.' AND played_id_level = '.$id_level.' AND played_dateplayed >= ( NOW() - INTERVAL 1 DAY )')
			->result();
		}

		return $played;
	}

	public function insert_score($id_level)
	{
		$score = $this->db->query ('
			SELECT *
			FROM   t_score
			WHERE  score_id_user = '.$this->session->userdata('user_id').' AND score_id_level = '.$id_level)
		->result();

		if($score == null)
		{
			$score = $this->db->query ("
				INSERT INTO t_score (score_id_user, score_id_level, score_point, score_datecreate) VALUES (".$this->session->userdata('user_id').", ".$id_level.", ".$this->point.", '".date('Y-m-d H:i:s')."')");

			return true;
		}
	}

	public function check_score($id_level)
	{
		$user_id = $this->session->userdata('user_id');

		$score = $this->db->query ('
			SELECT *
			FROM   t_score
			WHERE  score_id_user = '.$user_id.' AND score_id_level = '.$id_level)
		->result();

		return $score;
	}

}
?>