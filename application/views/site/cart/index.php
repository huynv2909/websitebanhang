<style type="text/css">
</style>
<div class="box-center"><!-- The box-center product-->
 	<div class="tittle-box-center">
	    <h2>
	    <?php if ($total_items > 0): ?>
	    	Thông tin giỏ hàng<?php echo ' ( có ' . $total_items . ' sản phẩm)' ?>
	    <?php else: ?>
	    	Không có sản phẩm nào
	    <?php endif ?>
	    </h2>
  	</div>
  	<div class="box-content-center product"><!-- The box-content-center -->
  		<?php if ($total_items > 0): ?>
	  		<form action="<?php echo base_url('cart/update'); ?>" method="post">
				<table cellpadding="20" border="1" cellspacing="1">
					<thead>
						<th>Sản phẩm</th>
						<th>Giá bán</th>
						<th>Số lượng</th>
						<th>Tổng số</th>
						<th>Xóa</th>
					</thead>
					<tbody>
					<?php $total_amount = 0; ?>
					<?php foreach ($carts as $row): ?>
						<?php $total_amount += $row['subtotal']; ?>
						<tr>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo number_format($row['price']); ?></td>
							<td><input type="text" name="qty_<?php echo $row['id']; ?>" value="<?php echo $row['qty']; ?>" placeholder="Số lượng" size="10"></td>
							<td><?php echo number_format($row['subtotal']); ?></td>
							<td><a href="<?php echo base_url('cart/del/' . $row['id']); ?>" title="Xóa">Xóa</a></td>
						</tr>
					<?php endforeach ?>
						<tr>
							<td colspan="5"></td>
						</tr>

						<tr>
							<td colspan="5">Tổng số tiền thanh toán: <strong style="color: red;"><?php echo number_format($total_amount); ?></strong>
							- <a href="<?php echo base_url('cart/del'); ?>" title="Xóa toàn bộ">Xóa toàn bộ</a>
							</td>
						</tr>
						<tr>
							<td colspan="5">
								<button type="submit">Cập nhật</button>
								<a href="<?php echo site_url('order/checkout') ?>" title="Mua hàng" class="button">Mua hàng</a>
							</td>
						</tr>
					</tbody>
				</table>
	  		</form>
  		<?php endif ?>
  	</div>
</div>