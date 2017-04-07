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
		
		<!--
		<?php //<script type="text/javascript"> ?>
		
		window.onload= function() {

			Dropzone.autoDiscover = false;
			document.getElementById('dropzonelogs').innerHTML += " hohos";
			
            var myDropzone = new Dropzone('div#dropzone', { url: "<?php echo BASE_URL; ?>main/upload",
				parallelUploads: 10,
				maxFiles: 10,
				init: function() {
					this.on("success", function(file, responseText) {
						console.log('success');
						// Handle the responseText here. For example, add the text to the preview element:
						//file.previewTemplate.appendChild(document.createTextNode(responseText));
					});
				},
				error: function(file, errorMessage) {
					console.log('error');
					document.getElementById('dropzonelogs').innerHTML += " [error]";
					window.alert("Failure!");
				},
				success: function(file, errorMessage) {
					console.log('success');
					document.getElementById('dropzonelogs').innerHTML += " [success]";
				},
				queuecomplete: function() {
					if(errors) alert("There were errors!");
					else alert("We're done!");
				}
			});
			
			/*Dropzone.options.myDropzone = {
			  init: function() {
				this.on("success", function(file, responseText) {
				  // Handle the responseText here. For example, add the text to the preview element:
				  file.previewTemplate.appendChild(document.createTextNode(responseText));
				});
			  }
			};*/
			
			/*
			myDropzone.on("error", function(file, message, xhr) {
				//var header = xhr.status+": "+xhr.statusText;
				document.getElementById('dropzonelogs').innerHTML += " [bad boy]";
				//$(file.previewElement).find('.dz-error-message').text(header);
			});
			myDropzone.on("success", function(file, message, xhr) {
				//var header = xhr.status+": "+xhr.statusText;
				document.getElementById('dropzonelogs').innerHTML += " [good boy]";
				//$(file.previewElement).find('.dz-error-message').text(header);
			});
			*/
			
		}
		<?php //</script> ?>
		//-->
		
		<!--
		<div id="dropzone">
			<form action="<?php echo BASE_URL; ?>main/upload" class="dropzone needsclick dz-clickable" id="demo-upload">
				<div class="dz-message needsclick">
					Drop files here or click to upload.<br>
				</div>
			</form>
		</div>
		//-->

		<div id="dropzonelogs">
		<h4>dropzonelogs</h4>
		</div>
        
    </div>

<?php include('footer.php'); ?>