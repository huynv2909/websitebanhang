<?php 
	 class Home extends MY_Controller {
	 	function index() {
	 		// Lấy slide
	 		$this->load->model('slides_model');
	 		$slide_list = $this->slides_model->get_list();
	 		$this->data['slide_list'] = $slide_list;

	 		// Lấy sản phẩm mới
	 		$this->load->model('product_model');
	 		$input = array();
	 		$input['limit'] = array(3, 0);
	 		$product_newest = $this->product_model->get_list($input);
	 		$this->data['product_newest'] = $product_newest;

	 		// Sản phẩm mua nhiều
	 		$input['order'] = array('buyed', 'DESC');
	 		$product_buy = $this->product_model->get_list($input);
	 		$this->data['product_buy'] = $product_buy;
	 		$message = $this->session->flashdata('message');

			$this->data['message'] = $message;

	 		$this->data['temp'] = 'site' . DIRECTORY_SEPARATOR . 'home' . DIRECTORY_SEPARATOR . 'index';
	 		$this->load->view('site' . DIRECTORY_SEPARATOR . 'layout', $this->data);
	 	}
	 }
 ?>