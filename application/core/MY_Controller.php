<?php 
	class MY_Controller extends CI_Controller {

		public $data = array();
		function __construct() {
			parent::__construct();
			$this->load->library('cart');

			$controller = $this->uri->segment(1);

			switch ($controller) {
				case 'admin':
					$this->load->helper('admin');
					$this->_check_login();
					break;
				
				default:
					// Lấy dữ liệu trang ngoài
					// Lấy danh sách danh mục sản phẩm
					$this->load->model('catalog_model');
					$input = array();
					$input['where'] = array('parent_id' => 0);
					$catalog_list = $this->catalog_model->get_list($input);

					foreach ($catalog_list as $row) {
						$input['where'] = array('parent_id' => $row->id);
						$subs = $this->catalog_model->get_list($input);
						$row->subs = $subs;
					}

					// Lấy danh sách bài viết mới
					$this->load->model('news_model');
					$input = array();
					$input['input'] = array(5, 0);
					$news_list = $this->news_model->get_list($input);

					$this->data['news_list'] = $news_list;

					$this->data['catalog_list'] = $catalog_list;

					$this->data['total_items'] = $this->cart->total_items();

					// Kiểm tra thành viên đã đăng nhập chưa
					$user_id_login = $this->session->userdata('user_id_login');
					$this->data['user_id_login'] = $user_id_login;

					if ($user_id_login) {
						$this->load->model('user_model');
						$user_info = $this->user_model->get_info($user_id_login);
						$this->data['user_info'] = $user_info;
					}

					break;
			}
		}

		private function _check_login() {
			$controller = $this->uri->rsegment('1');
			$controller = strtolower($controller);

			$login = $this->session->userdata('login');


			if (!$login && $controller != 'login') {
				redirect(admin_url('login'));
			}

			if ($login && $controller == 'login') {
				redirect(admin_url('home'));
			}
		}
	}
 ?>