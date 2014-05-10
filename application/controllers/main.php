<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$user_id = $this->session->userdata('user_id');

		if($user_id != null){
			$this->load->model('users_model');
			$user = $this->users_model->get_by_id($user_id);
			$this->session->set_userdata('user_point', $user[0]->point);	
		}
	}

	public function index(){
		//Si c'est la première connexion on redirige vers le profil
    	if($this->session->userdata('user_id') == null){
    		redirect('user/log/', 'refresh');
    	}
    	// Load layout library
		$this->load->library('layout'); 

		// Set page title
		$this->layout->title('WikiDrunk Logo Quiz');

		// Set meta
		// --- Type = name or property [for FB]
		$descr = 'Welcome to wikidrunk website : videos, pictures, recipes, games and quizzes for everybody !';

		$this->layout->meta(
			array(
				array('type' => 'name', 'key' => 'description', 'content' => $descr)
			)
		); 

		$this->layout->keywords(array('home', 'wikidrunk', 'funny', 'drunk', 'pictures', 'videos', 'games')); 

		$data = array();
		$this->layout->view('Layout/index', $data);     // Render view and layout
	}

	public function nav($item){
		$data    = array();
		$user_id = $this->session->userdata('user_id');

		$this->load->model('quizzes_model');
		$this->load->model('users_model');
	    if($item=="home"){
			$data ['page']    = 'Home';
			$data ['quizzes'] = $this->quizzes_model->get_by_user($user_id);
			foreach ($data['quizzes'] as $quiz) {
				$quiz->details = $this->quizzes_model->get_quiz_details($quiz->quiz_id);
			}
		}else if($item=="leaderboard"){
			$data ['page']    = $item;
			$data ['users']   = $this->users_model->listing(20);
			$data ['quizzes'] = $this->quizzes_model->get_by_user($user_id);
		}else if($item=="invite"){
			$data ['page'] = $item;
			$data ['quizzes'] = $this->quizzes_model->get_by_user($user_id);
		}

	    $this->load->view('Nav/'.$item, $data);
	}
}