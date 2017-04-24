<?php include('header.php'); ?>

<div id="content">

	<h1>YAML documents uploaded to the service</h1>

	<script type="text/javascript">
        var serviceResponse = '<?php echo $serviceRes->srvMessage; ?>';

        window.onload = function () {
            var yamlListDiv = document.getElementById("yml_lst");
            try {
                parsedRes = JSON.parse(serviceResponse);
                //document.getElementById("yml_lst").innerHTML= "<p>"+serviceResponse+"</p>";
                for (var i = 0; i < parsedRes.length; i++) {
					var nodes= parsedRes[i].top_level_nodes;
					var nodesStr= "";
					for (var p in nodes) {
						nodesStr += p + " ("+ nodes[p] +" sub-nodes)</br>";
					}
					
                    var div = document.createElement("div");
                    div.className = "yml_lst_item";
                    div.innerHTML = "" +
							"<div class='yml_lst_item_h'>" +
                            "<span class='handle'>" + parsedRes[i].handle + "</span>" +
							"<span class='download'><a href='displayYaml/"+parsedRes[i].handle+"'>Download YAML</a></span>" +
							"<span class='download'><a href='displayYaml/"+parsedRes[i].handle.replace(".yml", ".json")+"'>Download JSON</a></span>" +
                            "</div>" + 
							"<div class='yml_lst_item_s'>" + 
							//"<pre>" + JSON.stringify(parsedRes[i].top_level_nodes) + "</pre>"
							//"<pre>" + nodesStr + "</pre>"
							nodesStr +
							"</div>";
                    yamlListDiv.appendChild(div);
                }
            } catch (e) {
                yamlListDiv.innerHTML = "<p>" + serviceResponse + "</p>";
            }
        }
	</script>
	
	<style>
		.yml_lst_item {
			width:100%;
			border:1px solid black;
			margin-bottom: 10px;
		}
		.yml_lst_item_h {
			background: #B7D6E7;
			font-weight: bold;
			border-bottom: 1px solid black;
			padding: 5px;
		}
		.download {
			float: right;
			margin-right: 5px;
		}
		.yml_lst_item_s {
			padding: 10px;
		}
	</style>

	<div id="yml_lst"></div>


</div>

<?php include('footer.php'); ?>