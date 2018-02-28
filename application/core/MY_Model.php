<?php 
	if (!defined("BASEPATH")) die('Bad request!');

	class MY_Model extends CI_Model {
		var $table = '';

		var $key = 'id';

		var $order = '';

		var $select = '';


		// Them row moi
		function create($data) {
			if ($this->db->insert($this->table, $data)) {
				return true;
			}
			else {
				return false;
			}
		}

		function update_rule($where, $data) {
			if (!$where) {
				return false;
			}
			else {
				$this->db->where($where);
				if ($this->db->update($this->table, $data)) {
					return true;
				}
				return false;
			}
		}

		function update($id, $data) {
			if (!$id) {
				return false;
			}
			$where = array();
			$where['id'] = $id;
			return $this->update_rule($where, $data);

		}

		function delete($id) {
			if (!$id) {
				return false;
			}
			if (is_numeric($id)) {
				$where = array('id' => $id);
			}
			else {
				$where = "id IN (" . $id . ")";
			}

			return $this->del_rule($where);
		}

		function del_rule($where) {
			if (!$where) {
				return false;
			}

			$this->db->where($where);
			if ($this->db->delete($this->table)) {
				return true;
			}
			return false;
		}

		function get_info($id, $field = '') {
			if (!$id) {
				return false;
			}
			$where = array();
			$where['id'] = $id;
			return $this->get_info_rule($where, $field);
		}

		function get_info_rule($where = array(), $field = '') {
			if ($field) {
				$this->db->select($field);
			}

			$this->db->where($where);
			$query = $this->db->get($this->table);

			if ($query->num_rows()) {
				return $query->row();
			}

			return false;
		}

		function get_list($input = array()) {
			$this->get_list_set_input($input);
			$query = $this->db->get($this->table);
			return $query->result();
		}

		protected function get_list_set_input($input) {
			if (isset($input['select'])) {
				$this->db->select($input['select']);
			}

			if (isset($input['where']) && $input['where']) {
				$this->db->where($input['where']);
			}

			if (isset($input['like']) && $input['like']) {
				$this->db->like($input['like'][0], $input['like'][1]);
			}

			if (isset($input['order'][0]) && isset($input['order'][1])) {
				$this->db->order_by($input['order'][0], $input['order'][1]);
			}
			else {
				$this->db->order_by('id', 'desc');
			}

			if (isset($input['limit'][0]) && isset($input['limit'][1])) {
				$this->db->limit($input['limit'][0], $input['limit'][1]);
			}
		}

		function get_total($input = array()) {
			$this->get_list_set_input($input);
			$query = $this->db->get($this->table);
			return $query->num_rows();
		}

		function check_exists($where = array())
		{
		    $this->db->where($where);
		    //thuc hien cau truy van lay du lieu
		    $query = $this->db->get($this->table);
		     
		    if($query->num_rows() > 0){
		        return TRUE;
		    }else{
		        return FALSE;
		    }
		}
	}
 ?>