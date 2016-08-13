<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
	}
        
        public function __construct() {
            parent::__construct();
            @session_start();
        }
        
        public function login() {
            if (isset ($_SESSION['user'])) {
                redirect('user/dashboard');
            }
            $data = array('msg'=>'','status'=>'','data'=>'');
            if($this->input->post('username') && $this->input->post('password')){
                if($this->input->post('username')=='admin' && $this->input->post('password')=='test@123'){
                    $_SESSION['user'] = array('username'=>$this->input->post('username'),'password'=>$this->input->post('password'));
                    redirect('user/dashboard');
                }
            }
            $this->load->view('user/login');
        }
        
        public function dashboard() {
            if (!isset ($_SESSION['user'])) {
                redirect('user/login');
            }
            $this->load->view('user/dashboard');
        }
        
        public function logout() {
            if (!isset ($_SESSION['user'])) {
                redirect('user/login');
            }
            session_destroy();
            $this->load->view('user/logout');
        }
        
        public function register() {
            if (isset ($_SESSION['user'])) {
                redirect('user/dashboard');
            }
            $data = array('msg'=>'','status'=>'','data'=>'');
            if($this->input->post('email') && $this->input->post('password')){
                $this->load->model('users');
                $postData['username'] = $this->input->post('email');
                $postData['password'] = md5($this->input->post('password'));
                $postData['email'] = $this->input->post('email');
                $postData['first_name'] = $this->input->post('first_name');
                $postData['last_name'] = $this->input->post('last_name');
                $postData['mobile'] = $this->input->post('mobile');
                $returnValue = $this->users->CreateOrUpdateUser($postData);
                if(is_array($returnValue)){
                    if($returnValue->email==$postData['email']){
                        $data = array('msg'=>'Email already existed.','status'=>'success','data'=>'');
                    }                    
                }  else {
                    if($returnValue){
                        $data = array('msg'=>'Registration Done Successfully.','status'=>'success','data'=>'');
                    }  else {
                        $data = array('msg'=>'Error in Registration.','status'=>'error','data'=>'');
                    }
                }
            }
            $this->load->view('user/registration',$data);
        }
}
