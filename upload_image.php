<?php
	$target_dir = "uploadedimages/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
	    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	    if($check !== false) {
	        $uploadOk = 1;
	    } else {
	    	http_response_code(400);
	        echo "File is not an image.";
	        $uploadOk = 0;
	    }
	}
	
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
		http_response_code(400);
	    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 1) {
		// if everything is ok, try to upload file
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			$fullpath = 'http://imageupload-tigeruploader.rhcloud.com/uploadedimages/'.basename( $_FILES["fileToUpload"]["name"]);
			echo $fullpath;
		} else {
			http_response_code(400);
			echo "Sorry, there was an error uploading your file.";
		}
	} 
?>

