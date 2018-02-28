<?php 
	class Slides extends MY_Controller {
		function __construct() {
			parent::__construct();

			// Load model
			$this->load->model('slides_model');
		}

		function index() {
			$this->load->library('pagination');
			$config = array();
			$total_rows = $this->slides_model->get_total();

			$input = array();

			$list = $this->slides_model->get_list($input);
			$this->data['list'] = $list;
			$this->data['total_rows'] = $total_rows;

			$message = $this->session->flashdata('message');
			$this->data['message'] = $message;

			$this->data['temp'] = 'admin/slides/index';
			$this->load->view('admin/main', $this->data);
		}

		function add()
		{
			$this->load->model('slides_model');

			// validate
			$this->load->library('form_validation');
			$this->load->helper('form');

			// Khi submit
			if ($this->input->post()) {
				$this->form_validation->set_rules('name', "Tiêu đề slide", 'required');

				if ($this->form_validation->run()) {

					// Lay ten file anh minh hoa dc upload
					$this->load->library('upload_library');
					$upload_path = './upload/slide';
					$upload_data = $this->upload_library->upload($upload_path, 'image');

					$image_link = '';
					if ($upload_data['file_name']) 
					{
						$image_link = $upload_data['file_name'];
					}

					$data = array(
						'name' => $this->input->post('name'),
						'image_link' => $image_link,
						'link' => $this->input->post('link'),
						'info' => $this->input->post('info'),
						'sort_order' => $this->input->post('sort_order')
						);

					if ($this->slides_model->create($data)) {
						$this->session->set_flashdata('message', 'Thêm mới dữ liệu thành công!');
					}
					else {
						$this->session->set_flashdata('message', 'Thêm mới dữ liệu không thành công!');
					}

					redirect(admin_url('slides'));
				}
			}

			// Load view			
			$this->data['temp'] = 'admin/slides/add';
			$this->load->view('admin/main', $this->data);
		}

		function edit()
		{
			$this->load->model('slides_model');

			$id = $this->uri->rsegment('3');
			$slides = $this->slides_model->get_info($id);

			if (!$slides) {
				$this->session->set_flashdata('message', 'Không tồn tại sản phẩm!');
				redirect(admin_url('slides'));
			}
			$this->data['slides'] = $slides;

			// validate
			$this->load->library('form_validation');
			$this->load->helper('form');

			// Khi submit
			if ($this->input->post()) {
				$this->form_validation->set_rules('name', "Tiêu đề slide", 'required');

				if ($this->form_validation->run()) {

					// Lay ten file anh minh hoa dc upload
					$this->load->library('upload_library');
					$upload_path = './upload/slide';
					$upload_data = $this->upload_library->upload($upload_path, 'image');

					$image_link = '';
					if (!empty($upload_data))
					{
						$image_link = $upload_data['file_name'];
					}

					$data = array(
						'name' => $this->input->post('name'),
						'link' => $this->input->post('link'),
						'info' => $this->input->post('info'),
						'sort_order' => $this->input->post('sort_order')
						);

					if ($image_link != '') {
						$data['image_link'] = $image_link;
					}

					if ($this->slides_model->update($slides->id, $data)) {
						$this->session->set_flashdata('message', 'Cập nhật dữ liệu thành công!');
					}
					else {
						$this->session->set_flashdata('message', 'Cập nhật dữ liệu không thành công!');
					}

					redirect(admin_url('slides'));
				}
			}

			// Load view			
			$this->data['temp'] = 'admin/slides/edit';
			$this->load->view('admin/main', $this->data);
		}

		function delete()
		{
			$id = $this->uri->rsegment(3);
			
			$this->_del($id);

			$this->session->set_flashdata('message', 'Đã xóa slide!');
			redirect(admin_url('slides'));
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
			$slides = $this->slides_model->get_info($id);

			if (!$slides)
			{
				$this->session->set_flashdata('message', 'Không tồn tại slide!');
				redirect(admin_url('slides'));
			}

			$this->slides_model->delete($id);

			// Xóa ảnh kèm theo
			$image_link = './upload/slide/' . $slides->image_link;
			if (file_exists($image_link)) {
				unlink($image_link);
			}
		}
	}	
 ?>