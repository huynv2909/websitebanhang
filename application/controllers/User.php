<?php 
	class User extends MY_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->model('user_model');
		}

		// hiển thị thông tin thành viên
		function index()
		{
			if (!$this->session->userdata('user_id_login')) {
				redirect();
			}

			$user_id = $this->session->userdata('user_id_login');
			$user = $this->user_model->get_info($user_id);

			if (!$user) {
				redirect();
			}
			$this->data['user'] = $user;

			// Load view
			$this->data['temp'] = 'site/user/index';
			$this->load->view('site/layout', $this->data);
		}

		function edit()
		{
			if (!$this->session->userdata('user_id_login')) {
				redirect(site_url('user/login'));
			}

			$user_id = $this->session->userdata('user_id_login');
			$user = $this->user_model->get_info($user_id);

			if (!$user) {
				redirect();
			}
			$this->data['user'] = $user;

			$this->load->library('form_validation');
			$this->load->helper('form');

			// Khi submit
			if ($this->input->post()) {
				$this->form_validation->set_rules('name', "Họ và tên", 'required|min_length[8]');

				$password = $this->input->post('password');
				if ($password) {
					$this->form_validation->set_rules('password', "Mật khẩu", 'required|min_length[6]');
					$this->form_validation->set_rules('re_password', "Nhập lại Mật khẩu", 'matches[password]');
				}

				$this->form_validation->set_rules('phone', "Số điện thoại", 'required');
				$this->form_validation->set_rules('address', "Địa chỉ", 'required');

				if ($this->form_validation->run()) {

					$data = array(
						'name' => $this->input->post('name'),
						'phone' => $this->input->post('phone'),
						'address' => $this->input->post('address')
						);

					if ($password) {
						$data['password'] = md5($password);
					}

					if ($this->user_model->update($user->id, $data)) {
						$this->session->set_flashdata('message', 'Cập nhật thành công!');
					}
					else {
						$this->session->set_flashdata('message', 'Cập nhật không thành công!');
					}

					redirect(site_url('user'));
				}
			}

			// Load view
			$this->data['temp'] = 'site/user/edit';
			$this->load->view('site/layout', $this->data);
		}

		function register()
		{
			if ($this->session->userdata('user_id_login')) {
				redirect(site_url('user'));
			}
			$this->load->library('form_validation');
			$this->load->helper('form');

			// Khi submit
			if ($this->input->post()) {
				$this->form_validation->set_rules('name', "Họ và tên", 'required|min_length[8]');
				$this->form_validation->set_rules('email', "Email", 'required|valid_email|callback_check_email');
				$this->form_validation->set_rules('password', "Mật khẩu", 'required|min_length[6]');
				$this->form_validation->set_rules('re_password', "Nhập lại Mật khẩu", 'matches[password]');
				$this->form_validation->set_rules('phone', "Số điện thoại", 'required');
				$this->form_validation->set_rules('address', "Địa chỉ", 'required');

				if ($this->form_validation->run()) {
					$password = $this->input->post('password');

					$data = array(
						'name' => $this->input->post('name'),
						'email' => $this->input->post('email'),
						'phone' => $this->input->post('phone'),
						'address' => $this->input->post('address'),
						'password' => md5($password),
						'created' => now()
						);

					if ($this->user_model->create($data)) {
						$this->session->set_flashdata('message', 'Đăng ký thành công!');
					}
					else {
						$this->session->set_flashdata('message', 'Đăng ký không thành công!');
					}

					redirect(site_url('home'));
				}
			}
			// Load view
			$this->data['temp'] = 'site/user/register';
			$this->load->view('site/layout', $this->data);
		}

		// Kiem tra email da ton tai chua
		function check_email()
		{
			$email = $this->input->post('email');

			$where = array('email' => $email);

			if ($this->user_model->check_exists($where)) {
				$this->form_validation->set_message(__FUNCTION__, 'Email đã tồn tại');
				return false;
			}

			return true;
		}

		// Kiểm tra đăng nhập
		function login()
		{
			if ($this->session->userdata('user_id_login')) {
				redirect(site_url('user'));
			}

			$this->load->library('form_validation');
			$this->load->helper('form');
			if ($this->input->post()) {
				$this->form_validation->set_rules('email', "Email", 'required|valid_email');
				$this->form_validation->set_rules('password', "Mật khẩu", 'required|min_length[6]');
				$this->form_validation->set_rules('login', 'Login', 'callback_check_login');

				if ($this->form_validation->run()) {
					$user = $this->_get_user_info();
					$this->session->set_userdata('user_id_login', $user->id);
					$this->session->set_flashdata('message', 'Đăng nhập thành công!');
					redirect();
				}
			}
			// Load view
			$this->data['temp'] = 'site/user/login';
			$this->load->view('site/layout', $this->data);
		}

		function check_login() {
			$user = $this->_get_user_info();

			if ($user) {
				return true;
			}

			$this->form_validation->set_message(__FUNCTION__, 'Đăng nhập không thành công!');
			return false;
		}

		// lấy thông tin thành viên
		private function _get_user_info()
		{
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$password = md5($password);

			$where = array('email' => $email, 'password' => $password);

			$user = $this->user_model->get_info_rule($where);
			return $user;
		}

		function logout() {
			if ($this->session->userdata('user_id_login')) {
				$this->session->unset_userdata('user_id_login');
			}

			$this->session->set_flashdata('message', 'Đã đang xuất!');
			redirect();
		}
	}
 ?>