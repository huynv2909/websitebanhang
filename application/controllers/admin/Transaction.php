<?php 
	class Transaction extends MY_Controller {
		function __construct() {
			parent::__construct();

			// Load model
			$this->load->model('transaction_model');
		}

		function index() {
			$total_rows = $this->transaction_model->get_total();
			$this->data['total_rows'] = $total_rows;
			
			$this->load->library('pagination');
			$config = array();

			$config['total_rows'] = $total_rows;
			$config['base_url'] = admin_url('transaction/index');
			$config['per_page'] = 5;
			$config['uri_segment'] = 4;
			$config['next_link'] = '>>';
			$config['prev_link'] = '<<';
			$this->pagination->initialize($config);

			$segment = $this->uri->segment(4);
			$segment = intval($segment);

			$input_p = array();
			$input_p['limit'] = array($config['per_page'], $segment);
			 
			// Kiem tra neu co dieu kien loc
			$id = $this->input->get('id');
			$id = intval($id);
			if ($id > 0) {
				$input_p['where'] = array('id' => $id);
			}

			// Lấy danh sách đơn hàng
			$list = $this->transaction_model->get_list($input_p);
			$this->data['list'] = $list;

			$message = $this->session->flashdata('message');
			$this->data['message'] = $message;

			$this->data['temp'] = 'admin/transaction/index';
			$this->load->view('admin/main', $this->data);
		}

		function delete()
		{
			$id = $this->uri->rsegment(3);
			
			$this->_del($id);

			$this->session->set_flashdata('message', 'Đã xóa!');
			redirect(admin_url('transaction'));
		}

		function del_all()
		{
			$ids = $this->input->post('ids');
			
			foreach ($ids as $id) {
				$this->_del($id);
			}
		}

		private function _del($id)
		{
			$transaction = $this->transaction_model->get_info($id);

			if (!$transaction)
			{
				$this->session->set_flashdata('message', 'Không tồn tại giao dịch!');
				redirect(admin_url('transaction'));
			}

			$this->transaction_model->delete($id);
		}
	}
?>
