<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class quiz extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$user_id = $this->session->userdata('user_id');

		if($user_id != null){
			$this->load->model('users_model');
			$user = $this->users_model->get_by_id($user_id);
			$this->session->set_userdata('user_point', $user[0]->point);	
		}
	}
	
	public function play($id, $name = ""){
		$user_id = $this->session->userdata('user_id');
		$this->load->model('quizzes_model');
		$data ['quizzes'] = $this->quizzes_model->get_by_user($user_id);
		$data['quiz'] = $this->quizzes_model->get_by_id($id);
		if($data['quiz'] != null && $data['quiz'][0]->quiz_limit_point <= $this->session->userdata('user_point')){
			$data['levels'] = $this->quizzes_model->get_levels($id);
			
			foreach ($data['levels'] as $level) {
				$level->details = $this->quizzes_model->get_level_details($level->level_id);
				$level->score   = $this->quizzes_model->get_level_score($level->level_id);
			}
	    	$this->load->view('Quiz/play', $data);
		}else{
			show_404();
		}

	}
}