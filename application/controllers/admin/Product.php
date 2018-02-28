<?php 
	class Product extends MY_Controller {
		function __construct() {
			parent::__construct();

			// Load model
			$this->load->model('product_model');
		}

		function index() {
			$total_rows = $this->product_model->get_total();
			$this->data['total_rows'] = $total_rows;
			
			$this->load->library('pagination');
			$config = array();

			$config['total_rows'] = $total_rows;
			$config['base_url'] = admin_url('product/index');
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

			$name = $this->input->get('name');
			if ($name) {
				$input_p['like'] = array('name' => $name);
			}

			$catalog_id = $this->input->get('catalog');
			$catalog_id = intval($catalog_id);
			if ($catalog_id > 0) {
				$input_p['where'] = array('catalog_id' => $catalog_id);
			}

			$list = $this->product_model->get_list($input_p);

			$this->data['list'] = $list;

			// List catalog
			$this->load->model('catalog_model');
			$input = array();
			$input['where'] = array('parent_id' => 0);
			$catalogs = $this->catalog_model->get_list();

			foreach ($catalogs as $item) {
				$input['where'] = array('parent_id' => $item->id);
				$subs = $this->catalog_model->get_list($input);
				$item->subs = $subs;
			}


			$this->data['catalogs'] = $catalogs;

			$message = $this->session->flashdata('message');
			$this->data['message'] = $message;

			$this->data['temp'] = 'admin/product/index';
			$this->load->view('admin/main', $this->data);
		}

		function add()
		{
			$this->load->model('product_model');
			// List catalog
			$this->load->model('catalog_model');
			$input = array();
			$input['where'] = array('parent_id' => 0);
			$catalogs = $this->catalog_model->get_list();

			foreach ($catalogs as $item) {
				$input['where'] = array('parent_id' => $item->id);
				$subs = $this->catalog_model->get_list($input);
				$item->subs = $subs;
			}

			$this->data['catalogs'] = $catalogs;

			// validate
			$this->load->library('form_validation');
			$this->load->helper('form');

			// Khi submit
			if ($this->input->post()) {
				$this->form_validation->set_rules('name', "Tên danh mục", 'required');
				$this->form_validation->set_rules('catalog', "Thể loại", 'required');
				$this->form_validation->set_rules('price', "Giá", 'required');

				if ($this->form_validation->run()) {
					// Them vào csdl
					$name = $this->input->post('name');
					$catalog_id = $this->input->post('catalog');
					$price = $this->input->post('price');
					$price = str_replace(',', '', $price);

					// Lay ten file anh minh hoa dc upload
					$this->load->library('upload_library');
					$upload_path = './upload/product';
					$upload_data = $this->upload_library->upload($upload_path, 'image');

					$image_link = '';
					if ($upload_data['file_name']) 
					{
						$image_link = $upload_data['file_name'];
					}

					$image_list = array();
					$image_list = $this->upload_library->upload_file($upload_path, 'image_list');
					$image_list = json_encode($image_list);

					$data = array(
						'name' => $name,
						'catalog_id' => $catalog_id,
						'price' => $price,
						'image_link' => $image_link,
						'image_list' => $image_list,
						'discount' => str_replace(',', '', $this->input->post('discount')),
						'warranty' => $this->input->post('warranty'),
						'gifts' => $this->input->post('gifts'),
						'site_title' => $this->input->post('site_title'),
						'meta_key' => $this->input->post('meta_key'),
						'meta_desc' => $this->input->post('meta_desc'),
						'content' => $this->input->post('content'),
						'created' => now()
						);

					if ($this->product_model->create($data)) {
						$this->session->set_flashdata('message', 'Thêm mới dữ liệu thành công!');
					}
					else {
						$this->session->set_flashdata('message', 'Thêm mới dữ liệu không thành công!');
					}

					redirect(admin_url('product'));
				}
			}

			// Load view			
			$this->data['temp'] = 'admin/product/add';
			$this->load->view('admin/main', $this->data);
		}

		function edit()
		{
			$this->load->model('product_model');

			$id = $this->uri->rsegment('3');
			$product = $this->product_model->get_info($id);

			if (!$product) {
				$this->session->set_flashdata('message', 'Không tồn tại sản phẩm!');
				redirect(admin_url('product'));
			}
			$this->data['product'] = $product;

			// List catalog
			$this->load->model('catalog_model');
			$input = array();
			$input['where'] = array('parent_id' => 0);
			$catalogs = $this->catalog_model->get_list();

			foreach ($catalogs as $item) {
				$input['where'] = array('parent_id' => $item->id);
				$subs = $this->catalog_model->get_list($input);
				$item->subs = $subs;
			}

			$this->data['catalogs'] = $catalogs;

			// validate
			$this->load->library('form_validation');
			$this->load->helper('form');

			// Khi submit
			if ($this->input->post()) {
				$this->form_validation->set_rules('name', "Tên danh mục", 'required');
				$this->form_validation->set_rules('catalog', "Thể loại", 'required');
				$this->form_validation->set_rules('price', "Giá", 'required');

				if ($this->form_validation->run()) {
					// Them vào csdl
					$name = $this->input->post('name');
					$catalog_id = $this->input->post('catalog');
					$price = $this->input->post('price');
					$price = str_replace(',', '', $price);

					// Lay ten file anh minh hoa dc upload
					$this->load->library('upload_library');
					$upload_path = './upload/product';
					$upload_data = $this->upload_library->upload($upload_path, 'image');

					$image_link = '';
					if (!empty($upload_data))
					{
						$image_link = $upload_data['file_name'];
					}

					$image_list = array();
					$image_list = $this->upload_library->upload_file($upload_path, 'image_list');
					$image_list_json = json_encode($image_list);

					$data = array(
						'name' => $name,
						'catalog_id' => $catalog_id,
						'price' => $price,
						'discount' => str_replace(',', '', $this->input->post('discount')),
						'warranty' => $this->input->post('warranty'),
						'gifts' => $this->input->post('gifts'),
						'site_title' => $this->input->post('site_title'),
						'meta_key' => $this->input->post('meta_key'),
						'meta_desc' => $this->input->post('meta_desc'),
						'content' => $this->input->post('content')
						);

					if ($image_link != '') {
						$data['image_link'] = $image_link;
					}

					if (!empty($image_list)) {
						$data['image_list'] = $image_list_json;
					}

					if ($this->product_model->update($product->id, $data)) {
						$this->session->set_flashdata('message', 'Cập nhật dữ liệu thành công!');
					}
					else {
						$this->session->set_flashdata('message', 'Cập nhật dữ liệu không thành công!');
					}

					redirect(admin_url('product'));
				}
			}

			// Load view			
			$this->data['temp'] = 'admin/product/edit';
			$this->load->view('admin/main', $this->data);
		}

		function delete()
		{
			$id = $this->uri->rsegment(3);
			
			$this->_del($id);

			$this->session->set_flashdata('message', 'Đã xóa!');
			redirect(admin_url('product'));
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
			$product = $this->product_model->get_info($id);

			if (!$product)
			{
				$this->session->set_flashdata('message', 'Không tồn tại sản phẩm!');
				redirect(admin_url('product'));
			}

			$this->product_model->delete($id);

			// Xóa ảnh kèm theo
			$image_link = './upload/product/' . $product->image_link;
			if (file_exists($image_link)) {
				unlink($image_link);
			}

			$image_list = json_decode($product->image_list);
			if (is_array($image_list))
			{
				foreach ($image_list as $img)
				{
					$image_link_sub = './upload/product/' . $img;
					if (file_exists($image_link_sub))
					{
						unlink($image_link_sub);
					}
				}
			}
		}
	}	
 ?>