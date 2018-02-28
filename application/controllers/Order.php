<?php 
	class Order extends MY_Controller
	{
		function __construct()
		{
			parent::__construct();
		}

		// Lấy thông tin khách hàng
		function checkout()
		{
			$carts = $this->cart->contents();
			// Tong số sản phẩm
			$total_items = $this->cart->total_items();

			if ($total_items <= 0) {
				redirect();
			}

			// Lấy tông số tiền thanh toán
			$total_amount = 0;
			foreach ($carts as $row) {
				$total_amount += $row['subtotal'];
			}

			$this->data['total_amount'] = $total_amount;


			// Nếu đã đăng nhập thì lấy thông tin
			$user_id = 0;
			$user = '';
			if ($this->session->userdata('user_id_login')) {
				$user_id = $this->session->userdata('user_id_login');
				$user = $this->user_model->get_info($user_id);
			}

			$this->data['user'] = $user;

			$this->load->library('form_validation');
			$this->load->helper('form');

			// Khi submit
			if ($this->input->post()) {
				$this->form_validation->set_rules('name', "Họ và tên", 'required|min_length[8]');
				$this->form_validation->set_rules('email', "Email", 'required|valid_email');
				$this->form_validation->set_rules('phone', "Số điện thoại", 'required');
				$this->form_validation->set_rules('message', "Ghi chú", 'required');
				$this->form_validation->set_rules('payment', "Cổng thanh toán", 'required');

				if ($this->form_validation->run()) {
					$payment = $this->input->post('payment');

					$data = array(
						'user_name' => $this->input->post('name'),
						'status' => 0,
						'user_id' => $user_id,
						'user_phone' => $this->input->post('phone'),
						'user_email' => $this->input->post('email'),
						'message' => $this->input->post('message'),
						'amount' => $total_amount,
						'payment' => $payment,
						'created' => now()
						);

					// Lưu database
					$this->load->model('transaction_model');
					$this->transaction_model->create($data);
					$transaction_id = $this->db->insert_id();

					// Thêm vào bảng order
					$this->load->model('order_model');
					foreach ($carts as $row) {
						$data = array(
							'transaction_id' => $transaction_id,
							'product_id' => $row['id'],
							'qty' => $row['qty'],
							'amount' => $row['subtotal'],
							'status' => '0'
							);

						$this->order_model->create($data);
					}

					// Xóa toàn bộ
					$this->cart->destroy();

					if ($payment == 'offline') {
						$this->session->set_flashdata('message', 'Đặt hàng thành công!');
						redirect(site_url());
					}
					elseif (in_array($payment, array('nganluong', 'baokim'))) {
						// Thanh toán bảo kim
						$this->session->set_flashdata('message', 'Chưa hỗ trợ bảo kim, ngân lượng!');
						redirect(site_url());
					}

				}
			}

			// Load view
			$this->data['temp'] = 'site/order/checkout';
			$this->load->view('site/layout', $this->data);
		}
	}
 ?>