<?php include('header.php'); ?>
	
    <div id="content">
        
        <h1>Welcome to PIP</h1>
        <script src="<?php echo BASE_URL; ?>static/js/dropzone.min.js"></script>
		
		<script type="text/javascript">
		Dropzone.options.myDropzone = {
		  init: function() {
			this.on("success", function(file, responseText) {
			  tmp= document.createElement('div');
			  tmp.className = "dropzone-msg-div";
			  tmp.innerHTML= responseText;
			  file.previewTemplate.appendChild(tmp);
			});
			this.on("error", function(file, responseText) {
			  tmp= document.createElement('div');
			  tmp.className = "dropzone-msg-div";
			  tmp.innerHTML= responseText;
			  file.previewTemplate.appendChild(tmp);
			});
		  }
		};
		</script>
		
		<style>
			.dropzone-msg-div {
				border: 1px solid black;
				text-align: center;
				width: 120px;
				word-wrap:break-word;
			}
			.dropzone-msg-success {
				color:green;
			}
			.dropzone-msg-error {
				color:red;
			}
			.dropzone-msg-warning {
				color:orange;
			}
		</style>
		
		<form action="<?php echo BASE_URL; ?>main/upload" id="my-dropzone" class="dropzone"></form>
		
    </div>

<?php include('footer.php'); ?>