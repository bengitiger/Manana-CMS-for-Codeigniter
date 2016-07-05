<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct () {
		parent::__construct();
		
		if (isset($this->member->data['id']) === FALSE) {
			// login 유저가 아니라면 로그인 화면으로..
			set_cookie('noti',lang('member_login_required'),0);
			set_cookie('noti_type','danger',0);
			redirect('/member/login/');
		} else if ($this->member->check_admin() === FALSE) {
			// admin 권한이 없다면, 팅겨냄..
			set_cookie('noti',lang('system_auth_danger'),0);
			set_cookie('noti_type','danger',0);
			redirect('/');
		} else {
			// set admin model
			$this->load->model(array('admin_model'=>'admin'));
		}
	}
	
	/**
	 * index
	 * 
	 * /admin/ 으로 접속하면 DashBoard로 보낸다.
	 */
	public function index () {
		redirect('/admin/dashboard/');
	}
	
	/**
	 * dashboard
	 * 
	 * admin 페이지 기본 화면..
	 */
	public function dashboard () {
		$data = array();
		$data['model_check'] = array();
		
		foreach ($this as $key => $row) {
			if (method_exists($this->$key,'install')) {
				if ($this->$key->install(FALSE)) {
					$data['model_check'][] = $key;
				}
			}
		}
		
		$this->load->view('admin/dashboard',$data);
	}
	
	/**
	 * site
	 * 
	 * site 관리 화면
	 */
	public function site () {
		$data = array();
		
		$data['data'] = $this->model->read_site_url(base_url('/'));
		$data['editor_list'] = $this->editor->read_list();
		
		$this->load->view('admin/site',$data);
	}
	
	/**
	 * updateSiteForm
	 * 
	 * site 관리 업데이트
	 */
	public function updateSiteForm () {
		$id = 0;
		$result = $data = array();
		
		$id = $this->input->post('site_id');
		$data = delete_prefix($this->model->post_data('site_','site_id'),'site_');
		
		$result = $this->model->update_data($data,$id);
		
		if ($result['status']) {
			// success
			set_cookie('noti',$result['message'],0);
			set_cookie('noti_type','success',0);
			echo js('parent.document.location.href = "'.base_url('/admin/site/').'";');
		} else {
			// error
			echo notify($result['message'],'danger',TRUE);
		}
	}
	
	/**
	 * install
	 * 
	 * 모듈 갱신
	 * 
	 * @param	string		$model		model name
	 */
	public function install ($model) {
		if ($model != 'model') {
			// model load
			$this->load->model(array($model.'_model'=>$model));
		}
		
		// model install
		$result = $this->$model->install(TRUE);
		
		if ($result) {
			// success
			set_cookie('noti',lang('admin_module_install_success'),0);
			set_cookie('noti_type','success',0);
			echo js('parent.document.location.href = "'.base_url('/admin/dashboard/').'";');
		} else {
			// error
			echo notify(lang('admin_module_install_error'),'danger',TRUE);
		}
	}
}