<div class="box-center"><!-- The box-center product-->
 	<div class="tittle-box-center">
	    <h2>Thông tin cá nhân</h2>
  	</div>
  	<div class="box-content-center product"><!-- The box-content-center -->
  		<table border="1" cellpadding="10">
  			<tr>
  				<td>Họ tên</td>
  				<td><?php echo $user->name; ?></td>
  			</tr>
  			<tr>
  				<td>Email</td>
  				<td><?php echo $user->email; ?></td>
  			</tr>
  			<tr>
  				<td>Số điện thoại</td>
  				<td><?php echo $user->phone; ?></td>
  			</tr>
  			<tr>
  				<td>Địa chỉ</td>
  				<td><?php echo $user->address; ?></td>
  			</tr>
  		</table>
  		<a href="<?php echo site_url('user/edit'); ?>" class="button" title="Chỉnh sửa">Chỉnh sửa</a>
  	</div>
</div>