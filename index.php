<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Parse XML File</title>
</head>
<body>
    <div class="container">
        <div id="search_container">
			<div>
				<input type="text" placeholder="Search" name="search" id="search_input">
				<input type="file" accept="text/xml" id="xml_file" name="xml_file" required>
				<input type="button" value="Read" id="read_button">
        	</div>
			<div id="loader" style="display:none;"></div>
        </div>
		<!-- <div id="breadcrumb_container">
			<div id="breadcrumb">directory</div>
		</div> -->
        <div id="xml_result"></div>
    </div>
    <script>
		document.getElementById("read_button").addEventListener("click", function() {
			var fileInput = document.getElementById('xml_file');
			var file = fileInput.files[0];
			var reader = new FileReader();

			reader.onloadstart = function() {
                document.getElementById("loader").innerHTML = "Loading...";
                document.getElementById("loader").style.display = "block";
				const start = performance.now();
			};
			
			reader.onload = function(event) {
				var xmlContent = event.target.result;
				var formData = new FormData();
				formData.append('xml_content', xmlContent);

				const start = performance.now();
				
				var xhr = new XMLHttpRequest();
				xhr.open("POST", "functions/parse_xml.php?search=" + encodeURIComponent(document.getElementById("search_input").value), true);
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						document.getElementById("xml_result").innerHTML = xhr.responseText;
       					const end = performance.now();
						const timeInSeconds = (end - start) / 1000;
                		document.getElementById("loader").innerHTML = `Time taken: ${timeInSeconds.toFixed(3)} seconds`;
					}
				};
				xhr.send(formData);
			};
			
			reader.readAsText(file);
		});

    </script>
</body>
</html>
