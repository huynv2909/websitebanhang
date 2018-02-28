<div class="box-center"><!-- The box-center product-->
 	<div class="tittle-box-center">
	    <h2>Thông tin đơn hàng</h2>
  	</div>
  	<div class="box-content-center product"><!-- The box-content-center -->
      <form enctype="multipart/form-data" action="<?php echo site_url('order/checkout'); ?>" method="post" class="t-form form_action">
          <div class="form-row">
            <label class="form-label" for="param_name">Tổng số tiền thanh toán:</label>
            <div class="form-item">
              <strong style="color: red;"><?php echo $total_amount; ?></strong>
            </div>
            <div class="clear"></div>
          </div>
                  <div class="form-row">
            <label class="form-label" for="param_email">Email:<span class="req">*</span></label>
            <div class="form-item">
              <input type="text" value="<?php echo isset($user->email) ? $user->email : ''; ?>" name="email" id="email" class="input">
              <div class="clear"></div>
              <div id="email_error" class="error"><?php echo form_error('email'); ?></div>
            </div>
            <div class="clear"></div>
          </div>
          <div class="form-row">
            <label class="form-label" for="param_name">Họ và tên:<span class="req">*</span></label>
            <div class="form-item">
              <input type="text" value="<?php echo isset($user->name) ? $user->name : ''; ?>" name="name" id="name" class="input">
              <div class="clear"></div>
              <div id="name_error" class="error"><?php echo form_error('name'); ?></div>
            </div>
            <div class="clear"></div>
          </div>
          <div class="form-row">
            <label class="form-label" for="param_phone">Số điện thoại:<span class="req">*</span></label>
            <div class="form-item">
              <input type="text" value="<?php echo isset($user->phone) ? $user->phone : ''; ?>" name="phone" id="phone" class="input">
              <div class="clear"></div>
              <div id="phone_error" class="error"><?php echo form_error('phone'); ?></div>
            </div>
            <div class="clear"></div>
          </div>
          
          <div class="form-row">
            <label class="form-label" for="param_address">Ghi chú:<span class="req">*</span></label>
            <div class="form-item">
              <textarea name="message" id="message" class="input"><?php echo set_value('message'); ?></textarea>
              <p>Nhập địa chỉ và thời gian nhận hàng</p>
              <div class="clear"></div>
              <div id="message_error" class="error"><?php echo form_error('message'); ?></div>
            </div>
            <div class="clear"></div>
          </div>

          <div class="form-row">
            <label class="form-label" for="param_address">Phương thức thanh toán:<span class="req">*</span></label>
            <div class="form-item">
              <select name="payment">
                <option value="">Chọn cổng thanh toán</option>
                <option value="nganluong">Ngân lượng</option>
                <option value="offline">Thanh toán khi giao hàng</option>
                <option value="baokim">Bảo kim</option>
              </select>
              <div class="clear"></div>
              <div id="payment_error" class="error"><?php echo form_error('payment'); ?></div>
            </div>
            <div class="clear"></div>
          </div>
          
          <div class="form-row">
            <label class="form-label">&nbsp;</label>
            <div class="form-item">
                    <input type="submit" name="submit" value="Thanh toán" class="button">
            </div>
           </div>
            </form>
  	</div><!-- End box-content-center -->
</div>	<!-- End box-center product-->	