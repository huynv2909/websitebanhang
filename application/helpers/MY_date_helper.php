<?php 
	// Lấy ngày từ dạng int
	function get_date($time, $fulltime = true)
	{
		$format = '%d-%m-%Y';
		if ($fulltime)
		{
			$format .= ' %H:%i:%s';
		}
		$date = mdate($format, $time);
		return $date;

	}

 ?>