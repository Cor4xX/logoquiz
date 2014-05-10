<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$user_id = $this->session->userdata('user_id');

		if($user_id != null){
			$this->load->model('users_model');
			$user = $this->users_model->get_by_id($user_id);
			$this->session->set_userdata('user_point', $user[0]->point);	
		}
	}

	public function log(){
		if($this->session->userdata('user_id') != null){
			redirect('/', 'refresh');
			return false;
		}
		else{
			try
			{
			    $provider = "Facebook";

			    $this->load->library('hybridauth');

			    if ($this->hybridauth->providerEnabled($provider))
			    {
			        $service = $this->hybridauth->authenticate($provider);
			        if ($service->isUserConnected())
			        {
			            $user_profile = $service->getUserProfile();

			            $data['user_profile'] = $user_profile;

			            $this->load->model('users_model');
			            $avatar = 'https://graph.facebook.com/'.$data['user_profile']->identifier.'/picture?type=large';
						$result = $this->users_model->get_by_facebook_id($data['user_profile']->identifier);

						//Si on trouve un utilisateur en base on met à jour ses informations personnelles depuis sa dernière connexion
						if(!empty($result)){
							$id = $result[0]->user_id;
							$this->update($id, $data['user_profile']);
						}

						//Sinon on créait un nouvel utilisateur
						else{
							$id = $this->create($data['user_profile']);
						}

						// On créait la session utilisateur
						$sess_array = array(
							'user_id'    => $id,
							'first_name' => $data['user_profile']->firstName.$id,
							'user_mail'  => $data['user_profile']->email,
							'logged_in'  => true
						);
						$this->session->set_userdata($sess_array);
						redirect('/', 'refresh');
			        }
			        else // Cannot authenticate user
			        {
			          show_error('Cannot authenticate user');
			        }
			    }
			    else // This service is not enabled.
			    {
			        log_message('error', 'controllers.HAuth.login: This provider is not enabled ('.$provider.')');
			        show_404($_SERVER['REQUEST_URI']);
			    }
			}
			catch(Exception $e)
			{
			  $error = 'Unexpected error';
			  switch($e->getCode())
			  {
			    case 0 : $error = 'Unspecified error.'; break;
			    case 1 : $error = 'Hybriauth configuration error.'; break;
			    case 2 : $error = 'Provider not properly configured.'; break;
			    case 3 : $error = 'Unknown or disabled provider.'; break;
			    case 4 : $error = 'Missing provider application credentials.'; break;
			    case 5 : log_message('debug', 'controllers.HAuth.login: Authentification failed. The user has canceled the authentication or the provider refused the connection.');
			             //redirect();
			             if (isset($service))
			             {
			              log_message('debug', 'controllers.HAuth.login: logging out from service.');
			              $service->logout();
			             }
			             show_error('User has cancelled the authentication or the provider refused the connection.');
			             break;
			    case 6 : $error = 'User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.';
			             break;
			    case 7 : $error = 'User not connected to the provider.';
			             break;
			  }

			  if (isset($service))
			  {
			    $service->logout();
			  }

			  log_message('error', 'controllers.HAuth.login: '.$error);
			  show_error('Error authenticating user.');
			}
		}
	}

	public function logout(){
		$this->session->unset_userdata('user_id');
		redirect('/', 'refresh');
	}

	public function create($user_data){
		$this->load->model('users_model');
		$user = new users_model();

		$user->name        = $user_data->lastName;
		$user->firstname   = $user_data->firstName;
		$user->mail        = $user_data->email;
		$user->birthday    = $user_data->birthYear.'/'.$user_data->birthMonth.'/'.$user_data->birthDay;
		$user->facebook_id = $user_data->identifier;
		$user->datecreate  = date('Y-m-d H:i:s');
		$user->lastlog     = date('Y-m-d H:i:s');

		$this->users_model->insert($user);

		return $this->db->insert_id();
	}

	public function update($id, $user_data){
		$this->load->model('users_model');
		$user = new users_model();

		$user->name        = $user_data->lastName;
		$user->firstname   = $user_data->firstName;
		$user->mail        = $user_data->email;
		$user->birthday    = $user_data->birthYear.'/'.$user_data->birthMonth.'/'.$user_data->birthDay;
		$user->facebook_id = $user_data->identifier;
		$user->datecreate  = date('Y-m-d H:i:s');
		$user->lastlog     = date('Y-m-d H:i:s');

		$this->users_model->update($user);
	}

	public function nav($item){
		$data = array();

	    if($item=="home"){
			$data ['page'] = $items;
		}else if($item=="leaderboard"){
			$data ['page'] = $items;
		}else if($item=="invite"){
			$data ['page'] = $items;
		}

	    $this->load->view('Nav/'.$item, $data);
	}

	public function endpoint(){
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            log_message('debug', 'controllers.auth_fb.endpoint: the request method is GET, copying REQUEST array into GET array.');
            $_GET = $_REQUEST;
        }
        require_once APPPATH.'third_party/hybridauth/index.php';

    }
}
