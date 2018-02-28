<?php 
	class News extends MY_Controller {
		function __construct() {
			parent::__construct();

			// Load model
			$this->load->model('news_model');
		}

		function index() {
			$this->load->library('pagination');
			$config = array();
			$total_rows = $this->news_model->get_total();

			$config['total_rows'] = $total_rows;
			$config['base_url'] = admin_url('news/index');
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

			$title = $this->input->get('title');
			if ($title) {
				$input_p['like'] = array('title' => $title);
			}

			$list = $this->news_model->get_list($input_p);
			$this->data['list'] = $list;
			$this->data['total_rows'] = $total_rows;

			$message = $this->session->flashdata('message');
			$this->data['message'] = $message;

			$this->data['temp'] = 'admin/news/index';
			$this->load->view('admin/main', $this->data);
		}

		function add()
		{
			$this->load->model('news_model');

			// validate
			$this->load->library('form_validation');
			$this->load->helper('form');

			// Khi submit
			if ($this->input->post()) {
				$this->form_validation->set_rules('title', "Tiêu đề bài viết", 'required');
				$this->form_validation->set_rules('content', "Nội dung", 'required');

				if ($this->form_validation->run()) {

					// Lay ten file anh minh hoa dc upload
					$this->load->library('upload_library');
					$upload_path = './upload/news';
					$upload_data = $this->upload_library->upload($upload_path, 'image');

					$image_link = '';
					if ($upload_data['file_name']) 
					{
						$image_link = $upload_data['file_name'];
					}

					$data = array(
						'title' => $this->input->post('title'),
						'image_link' => $image_link,
						'meta_desc' => $this->input->post('meta_desc'),
						'meta_key' => $this->input->post('meta_key'),
						'content' => $this->input->post('content'),
						'created' => now()
						);

					if ($this->news_model->create($data)) {
						$this->session->set_flashdata('message', 'Thêm mới dữ liệu thành công!');
					}
					else {
						$this->session->set_flashdata('message', 'Thêm mới dữ liệu không thành công!');
					}

					redirect(admin_url('news'));
				}
			}

			// Load view			
			$this->data['temp'] = 'admin/news/add';
			$this->load->view('admin/main', $this->data);
		}

		function edit()
		{
			$this->load->model('news_model');

			$id = $this->uri->rsegment('3');
			$news = $this->news_model->get_info($id);

			if (!$news) {
				$this->session->set_flashdata('message', 'Không tồn tại sản phẩm!');
				redirect(admin_url('news'));
			}
			$this->data['news'] = $news;

			// validate
			$this->load->library('form_validation');
			$this->load->helper('form');

			// Khi submit
			if ($this->input->post()) {
				$this->form_validation->set_rules('title', "Tiêu đề bài viết", 'required');
				$this->form_validation->set_rules('content', "Nội dung bài viết", 'required');

				if ($this->form_validation->run()) {

					// Lay ten file anh minh hoa dc upload
					$this->load->library('upload_library');
					$upload_path = './upload/news';
					$upload_data = $this->upload_library->upload($upload_path, 'image');

					$image_link = '';
					if (!empty($upload_data))
					{
						$image_link = $upload_data['file_name'];
					}

					$data = array(
						'title' => $this->input->post('title'),
						'meta_desc' => $this->input->post('meta_desc'),
						'meta_key' => $this->input->post('meta_key'),
						'content' => $this->input->post('content'),
						'created' => now()
						);

					if ($image_link != '') {
						$data['image_link'] = $image_link;
					}

					if ($this->news_model->update($news->id, $data)) {
						$this->session->set_flashdata('message', 'Cập nhật dữ liệu thành công!');
					}
					else {
						$this->session->set_flashdata('message', 'Cập nhật dữ liệu không thành công!');
					}

					redirect(admin_url('news'));
				}
			}

			// Load view			
			$this->data['temp'] = 'admin/news/edit';
			$this->load->view('admin/main', $this->data);
		}

		function delete()
		{
			$id = $this->uri->rsegment(3);
			
			$this->_del($id);

			$this->session->set_flashdata('message', 'Đã xóa bài viết!');
			redirect(admin_url('news'));
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
			$news = $this->news_model->get_info($id);

			if (!$news)
			{
				$this->session->set_flashdata('message', 'Không tồn tại bài viết!');
				redirect(admin_url('news'));
			}

			$this->news_model->delete($id);

			// Xóa ảnh kèm theo
			$image_link = './upload/news/' . $news->image_link;
			if (file_exists($image_link)) {
				unlink($image_link);
			}
		}
	}	
 ?>