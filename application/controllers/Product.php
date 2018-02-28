<?php 
	class Product extends MY_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->model('product_model');
		}

		// hiển thị danh sách sản phẩm theo thư mục
		function catalog()
		{
			$id = intval($this->uri->rsegment(3));

			$this->load->model('catalog_model');
			$catalog = $this->catalog_model->get_info($id);

			if (!$catalog) {
				redirect(base_url('catalog/product'));
			}
			$this->data['catalog'] = $catalog;
			$input = array();
			// Kiểm tra danh mục con hay cha
			if ($catalog->parent_id == 0) {
				$input_catalog = array();
				$input_catalog['where'] = array('parent_id' => $id);
				$catalog_subs = $this->catalog_model->get_list($input_catalog);
				
				if (!empty($catalog_subs)) {
					$catalog_subs_id = array();
					foreach ($catalog_subs as $sub) {
						$catalog_subs_id[] = $sub->id;
					}
					$this->db->where_in('catalog_id', $catalog_subs_id);
				}
				else {
					$input['where'] = array('catalog_id' => $id);
				}
			}
			else {
				$input['where'] = array('catalog_id' => $id);
			}

			// Lấy ra danh sách sản phẩm của danh mục
			$this->load->library('pagination');
			$config = array();
			$total_rows = $this->product_model->get_total($input);

			$config['total_rows'] = $total_rows;
			$config['base_url'] = base_url('product/catalog/' . $id);
			$config['per_page'] = 6;
			$config['uri_segment'] = 4;
			$config['next_link'] = '>>';
			$config['prev_link'] = '<<';

			$this->pagination->initialize($config);

			$segment = $this->uri->segment(4);
			$segment = intval($segment);
			$input['limit'] = array($config['per_page'], $segment);

			$list = $this->product_model->get_list($input);


			$this->data['total_rows'] = $total_rows;
			$this->data['list'] = $list;
			$this->data['temp'] = 'site/product/catalog';
			$this->load->view('site/layout', $this->data);
		}

		function view()
		{
			// Lấy id
			$id = $this->uri->rsegment(3);
			$id = intval($id);
			$product = $this->product_model->get_info($id);

			if (!$product) redirect();

			$this->data['product'] = $product;

			// Lấy danh sách các hình ảnh kèm theo
			$image_list = @json_decode($product->image_list);
			$this->data['image_list'] = $image_list;

			// Cập nhật lại lượt xem
			$data = array();
			$data['view'] = $product->view + 1;
			$this->product_model->update($product->id, $data);

			// Lấy thông tin của danh mục sản phẩm
			$catalog = $this->catalog_model->get_info($product->catalog_id);
			$this->data['catalog'] = $catalog;


			$this->data['temp'] = 'site/product/view';
			$this->load->view('site/layout', $this->data);
		}

		// Tìm kiếm theo tên sản phẩm
		function search()
		{
			if ($this->uri->rsegment('3') == 1) {
				// autocomplete
				$key = $this->input->get('term');
			}
			else 
			{
				$key = $this->input->get('key-search');
			}
			$this->data['key'] = trim($key);

			$input = array();
			$input['like'] = array('name', $key);

			$list = $this->product_model->get_list($input);

			$this->data['list'] = $list;

			if ($this->uri->rsegment('3') == 1) {
				// Xu ly autocomplete
				$result = array();
				foreach ($list as $row) {
					$item = array();
					$item['id'] = $row->id;
					$item['label'] = $row->name;
					$item['value'] = $row->name;
					$result[] = $item;
				}

				die(json_encode($result));
			}
			else {
				// Load view
				$this->data['temp'] = 'site/product/search';
				$this->load->view('site/layout', $this->data);
			}
		}

		function search_price()
		{
			$price_from = intval($this->input->get('price_from'));
			$price_to = intval($this->input->get('price_to'));
			$this->data['price_from'] = $price_from;
			$this->data['price_to'] = $price_to;

			// Lọc theo giá
			$input = array();
			$input['where'] = array('price >= ' => $price_from, 'price <= ' => $price_to);
			$list = $this->product_model->get_list($input);
			$this->data['list'] = $list;

			// Load view
			$this->data['temp'] = 'site/product/search_price';
			$this->load->view('site/layout', $this->data);
		}
	}
 ?>