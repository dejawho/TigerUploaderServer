<?php

function folderSize ($dir)
{
    $size = 0;

    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folderSize($each);
    }

    return $size;
}

$sizeCacheFile = "sizeCache";
$imagesSize = 0;
if (file_exists($sizeCacheFile)){
	$fileStream = fopen($sizeCacheFile, 'r');
	$imagesSize = fread($fileStream,filesize($sizeCacheFile));
	fclose($myfile);
} else {
	$imagesSize = folderSize('uploadedimages');
	$fileStream = fopen($sizeCacheFile, 'w');
	fwrite($fileStream, $imagesSize);
	fclose($fileStream);
}
echo $imagesSize;
			
?>