<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class manage extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
	}

	/*public function index(){
		$this->load->model('manage_model');
		$data['logos'] = $this->manage_model->get_logos();
		$data['levels'] = $this->manage_model->get_levels();
		$this->load->library('layout'); 
		$this->layout->view('Manage/index', $data);     // Render view and layout
	}

	public function create_response(){
		$this->load->model('manage_model');

		$level_id     = $_POST['level_id'];
		$good_answer  = $_POST['good_answer'];
		$bad_answer_1 = $_POST['bad_answer_1'];
		$bad_answer_2 = $_POST['bad_answer_2'];
		$bad_answer_3 = $_POST['bad_answer_3'];
		$bad_answer_4 = $_POST['bad_answer_4'];
		$bad_answer_5 = $_POST['bad_answer_5'];

		if($this->manage_model->create_response($level_id, $good_answer, $bad_answer_1, $bad_answer_2, $bad_answer_3, $bad_answer_4, $bad_answer_5)){
			redirect('manage', 'refresh');
		}
		else{
			echo "error!!";
			return false;
		}
	}*/
}