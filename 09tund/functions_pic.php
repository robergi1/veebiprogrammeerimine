<?php
function makeImage($picFile, $imageFileType){
	if($imageFileType == "jpg" or $imageFileType == "jpeg"){
		$myTempImage = imagecreatefromjpeg($picFile);
	}
	if($imageFileType == "png"){
		$myTempImage = imagecreatefrompng($picFile);
	}
	if($imageFileType == "gif"){
		$myTempImage = imagecreatefromgif($picFile);
	}
	return $myTempImage;
}

function setPicSize($myTempImage, $picSizeRatio){
	$picW = imagesx($myTempImage);
	$picH = imagesy($myTempImage);
	$picNewW = round($picW / $picSizeRatio, 0);
	$picNewH = round($picH / $picSizeRatio, 0);
	$newImage = imagecreatetruecolor($picNewW, $picNewH);
	imagecopyresampled($newImage, $myTempImage, 0, 0, 0, 0, $picNewW, $picNewH, $picW, $picH);
	return $newImage;
}

function saveImage($myNewImage, $targetFile, $imageFileType){
	$notice = null;
	if($imageFileType == "jpg" or $imageFileType == "jpeg"){
		if(imagejpeg($myNewImage, $targetFile, 90)){
			$notice = "Vähendatud pilt edukalt salvestatud!";
		} else {
			$notice = "Vähendatud pildi salvestamine ebaõnnestus!";
		}
	}
	if($imageFileType == "png"){
		if(imagepng($myNewImage, $targetFile, 6)){
			$notice = "Vähendatud pilt edukalt salvestatud!";
		} else {
			$notice = "Vähendatud pildi salvestamine ebaõnnestus!";
		}
	}
	if($imageFileType == "gif"){
		if(imagegif($myNewImage, $targetFile)){
			$notice = "Vähendatud pilt edukalt salvestatud!";
		} else {
			$notice = "Vähendatud pildi salvestamine ebaõnnestus!";
		}
	}
	return $notice;
}

function addPicData($fileName, $altText, $privacy){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO vpphotos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
	echo $conn->error;
	$stmt->bind_param("issi", $_SESSION["userId"], $fileName, $altText, $privacy);
	if($stmt->execute()){
		$notice = " Pildi andmed salvestati andmebaasi!";
	} else {
		$notice = " Pildi andmete salvestamine ebaönnestus tehnilistel põhjustel! " .$stmt->error;
	}
	$stmt->close();
	$conn->close();
	return $notice;
}