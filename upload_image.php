<?php
	$target_dir = "uploadedimages/";
	$sizeCacheFile = "sizeCache";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$responseCode = 0;
	$responseMessage = "";
	//Error code 0 all ok and the message will contain the link
	//Error code 1 the same is already on the server a link to it will be returned in the message
	//Error code 10 the file is not an image
	//Error code 20 the extension is unsupported
	//Error code 30 there is a different existing file with the same name that can't be deleted
	//Error code 40 something went wrong during the upload
	
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
	    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	    if($check !== false) {
	        $uploadOk = 1;
	    } else {
	    	$responseCode = 10;
	        $responseMessage = "File is not an image";
	        $uploadOk = 0;
	    }
	}
	
	//Skit the other verification if there was already an error
	if ($uploadOk == 1){
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			$responseCode = 20;
		    $responseMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		    $uploadOk = 0;
		}
	}
	
	// delete file if exists
	if (($uploadOk == 1) and (file_exists($target_file))) {
		$oldFileSize = filesize($target_file);
		$newFileSize = filesize($_FILES["fileToUpload"]["tmp_name"]);
		if ($oldFileSize != $newFileSize){
			if (!unlink ($target_file)){
				$responseCode = 30;
				$responseMessage = "A different file with the same name was already existing and can not be deleted";
				$uploadOk = 0;
			}
		} else {
			$uploadOk = 0;
			$responseCode = 1;
			$responseMessage = 'http://imageupload-tigeruploader.rhcloud.com/uploadedimages/'.basename( $_FILES["fileToUpload"]["name"]);
		}
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 1) {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			//Since there were an upload delete the old size cache file if existing
			if (file_exists($sizeCacheFile)){
				unlink ($sizeCacheFile);
			}
			$responseCode = 0;
			$responseMessage = 'http://imageupload-tigeruploader.rhcloud.com/uploadedimages/'.basename( $_FILES["fileToUpload"]["name"]);
		} else {
			$responseCode = 40;
			$responseMessage = "Sorry, there was an error uploading your file.";
		}
	} 
	
	$response = array('ResponseCode' => $responseCode, 'ResponseMessage' => $responseMessage);
	echo json_encode($response);
?>

