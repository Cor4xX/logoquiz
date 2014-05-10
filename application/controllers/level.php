<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class level extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$user_id = $this->session->userdata('user_id');

		if($user_id != null){
			$this->load->model('users_model');
			$user = $this->users_model->get_by_id($user_id);
			$this->session->set_userdata('user_point', $user[0]->point);	
		}
	}

	public function play($id)
	{
		//Si c'est la première connexion on redirige vers le profil
    	if($this->session->userdata('user_id') == null){
    		redirect('User/temp_log/', 'refresh');
    	}

		$user_id = $this->session->userdata('user_id');
		
    	// Load layout library
		$this->load->library('layout'); 

		$this->load->model('Level_model');
		$this->load->model('quizzes_model');

		$data = array();
		$data['score']    = $this->Level_model->check_score($id);
		$data['logos']    = $this->Level_model->get_logo_by_id_level($id);
		$data['submit']   = $this->Level_model->get_response_by_level($id);
		$data['quizzes']  = $this->quizzes_model->get_by_user($user_id);
		$data['quiz']     = $this->Level_model->get_quiz_by_level($id);
		$data['id_level'] = $id;

		if($data['score'] == null)
		{
			$data['played'] = $this->Level_model->get_played($id);
		}

		$this->load->view('Level/index', $data);     // Render view and layout
	}

	public function check_response()
	{

		$id_reponse = $this->input->post('id_reponse');
		$id_level = $this->input->post('id_level');

		$this->load->model('Level_model');
		$correct = $this->Level_model->check_response($id_level, $id_reponse);

		if(isset($correct[0]->response_correct))
		{
			$correct = $correct[0]->response_correct;
		}

		if($correct == '1' && !empty($correct))
		{
			$this->Level_model->insert_score($id_level);
			echo "win";
		}
		elseif($correct == 'false' && !empty($correct))
		{
			echo "locked";
		}
		else
		{
			echo "loose";
		}
		
	}

	public function check_joker()
	{
		$id_joker = $this->input->post('num');
		$id_level = $this->input->post('id_level');

		$this->load->model('Level_model');
		$played = $this->Level_model->check_joker($id_level, $id_joker);

		if($id_joker == 1){
			if($played->joker == 0){
				echo "success1";
			}
			else{
				echo "error";
			}
		}
		elseif($id_joker == 2){
			if($played->joker == 0){
				echo "success2";
				$this->update_joker();
			}
			else{
				echo "error";
			}
		}
	}

	public function update_joker()
	{
		$id_joker = $this->input->post('num');
		$id_level = $this->input->post('id_level');

		$this->load->model('Level_model');
		$played = $this->Level_model->check_joker($id_level, $id_joker);
		$this->Level_model->update_joker($played->id, $id_joker);
	}

}
?>