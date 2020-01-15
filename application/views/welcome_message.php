<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
	<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
	<style type="text/css">

		::selection { background-color: #E13300; color: white; }
		::-moz-selection { background-color: #E13300; color: white; }

		body {
			background-color: #fff;
			margin: 40px;
			font: 13px/20px normal Helvetica, Arial, sans-serif;
			color: #4F5155;
		}

		a {
			color: #003399;
			background-color: transparent;
			font-weight: normal;
		}

		h1 {
			color: #444;
			background-color: transparent;
			border-bottom: 1px solid #D0D0D0;
			font-size: 19px;
			font-weight: normal;
			margin: 0 0 14px 0;
			padding: 14px 15px 10px 15px;
		}

		code {
			font-family: Consolas, Monaco, Courier New, Courier, monospace;
			font-size: 12px;
			background-color: #f9f9f9;
			border: 1px solid #D0D0D0;
			color: #002166;
			display: block;
			margin: 14px 0 14px 0;
			padding: 12px 10px 12px 10px;
		}

		#body {
			margin: 0 15px 0 15px;
		}

		p.footer {
			text-align: right;
			font-size: 11px;
			border-top: 1px solid #D0D0D0;
			line-height: 32px;
			padding: 0 10px 0 10px;
			margin: 20px 0 0 0;
		}

		#container {
			margin: 10px;
			border: 1px solid #D0D0D0;
			box-shadow: 0 0 8px #D0D0D0;
		}
		
			.btn-file {
			position: relative;
			overflow: hidden;
		}
		.btn-file input[type=file] {
			position: absolute;
			top: 0;
			right: 0;
			min-width: 100%;
			min-height: 100%;
			font-size: 100px;
			text-align: right;
			filter: alpha(opacity=0);
			opacity: 0;
			outline: none;
			background: white;
			cursor: inherit;
			display: block;
		}
	</style>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
</head>
<body>

<div id="container">
	<h1>Welcome to Eduonix!</h1>

	<div id="body">
		<?php echo form_open_multipart('welcome/submit', array('name'=>'manageForm', 'id'=>'manageForm'));?>
		<?php if(!empty($upload_status) && $upload_status['upload'] == 'success' ){?>
		  <div class="form-group">
			File Uploaded successfully on Path : <a href="<?php echo $upload_status['path']?>" target="_blank" rel="noopener noreferrer">Click Here</a>
		  </div>
		<?php }?>
		<div class="form-group">
			<?php if(isset($error) && !empty($error)){
				foreach($error AS $errorV){
					echo $errorV;
				}
			}
			?>
		</div>
		  <div class="form-group">
			<label>Resize Width</label>
			<input type="text" class="form-control" id="rw" name="rw" placeholder="Enter Width here" required max="1000" maxlength="4">
		  </div>
		  <div class="form-group">
			<label>Resize Height</label>
			<input type="text" class="form-control" id="rh" name="rh" placeholder="Enter Height here" required max="1000" maxlength="4">
		  </div>
		  <div class="form-group">
		  <label class="radio-inline"><input type="radio" name="imageUploadType" value="ImageText">Enter Image path</label>
			<label class="radio-inline"><input type="radio" name="imageUploadType" value="imageUp" checked >Upload Image</label>
		  </div>
		  <div class="form-group" id="imageUpT">
			<label for="exampleFormControlSelect1">Upload File </label>
			<input type="file" name="userfile" size="20" id="userfile" accept="image/x-png,image/gif, image/jpeg"/>
		  </div>
		  <div class="form-group" id="ImageTextT">
			<label>Image Path </label>
			<input type="text" class="form-control" id="iup" name="iup" placeholder="Enter Path here" required>
		  </div>
		  <div class="form-group">
			<input type="button" value="Resize" class="btn btn-primary mb-2" id="btn_submit"/>
		  </div>
		<?php echo form_close();?>
	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
<script>
$(function() {
    $("input[name='imageUploadType']").click(function(){
		if($("input[name='imageUploadType']:checked").val() == 'imageUp'){
			$("div#imageUpT").show();
			$("div#ImageTextT").hide();
			$("#userfile").focus();
		}else{
			$("div#imageUpT").hide();
			$("div#ImageTextT").show();
			$("#iup").focus();
		}
	});
	$("input[name='imageUploadType']").trigger('click');
	$("#btn_submit").click(function(){
		if($('#rw').val() == ''){
			$('#rw').focus();
			return false;
		}else if($('#rh') == ''){
			$('#rh').focus();
			return false;
		}else if($("input[name='imageUploadType']:checked").val() == 'imageUp' && $("#userfile").val() == ''){
			alert("Please upload Image file.");
			return false;
		}else if($("input[name='imageUploadType']:checked").val() == 'ImageText' && $("#iup").val() == ''){
			alert("Please enter Image file path.");
			$("#iup").focus();
			return false;
		}else{
			$("#manageForm").submit();
		}
	});
});
</script>

</body>
</html>