<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('site' . DIRECTORY_SEPARATOR . 'head.php') ?>
</head>
<body>
	<a href="#" id="back_to_top">
		<img src="<?php echo public_url(); ?>site/images/top.png" />
	</a>

	<div class="wraper">
		<div class='header'>
	     	<?php $this->load->view('site/header') ?>
		</div>

		<div class="container">
			<div class="left">
				<?php $this->load->view('site/left', $this->data); ?>
			</div>

			<div class="content">
				<?php if (isset($message)) { ?>
					<h3 style="color: red;"><?php echo $message; ?></h3>
				<?php } ?>
				<?php $this->load->view($temp, $this->data); ?>
			</div>

			<div class="right">
				<?php $this->load->view('site/right', $this->data); ?>
			</div>
			<div class="clear"></div>
		</div>

		<center>
			<img src="<?php echo public_url(); ?>site/images/bank.png" /> 
	  	</center>

	  	<div class="footer">
	  		<?php $this->load->view('site/footer') ?>
	  	</div>
	</div>
</body>
</html>