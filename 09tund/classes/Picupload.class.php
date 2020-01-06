<?php
	class Picupload {
		private $tmpName;
		private $imageFileType;
		private $myTempImage;
		private $myNewImage;
		
		function __construct($fileToUpload, $imageFileType){
			$this->tmpName = $fileToUpload;
			$this->imageFileType = $imageFileType;
			$this->myTempImage = $this->makeImage($this->tmpName, $this->imageFileType);
		}
		
		function __destruct(){
			imagedestroy($this->myNewImage);
		}
		
		private function makeImage($picFile, $imageFileType){
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
		
		public function resizeImage($maxPicW, $maxPicH){
			//teeme kindlaks pildi suuruse
			$picW = imagesx($this->myTempImage);
			$picH = imagesy($this->myTempImage);
			//kui pilt ületab max väärtuse, siis muudamegi suurust
			if($picW > $maxPicW or $picH > $maxPicH){
				if($picW / $maxPicW > $picH / $maxPicH){
					$picSizeRatio = $picW / $maxPicW;
				} else {
					$picSizeRatio = $picH / $maxPicH;
				}
				$this->myNewImage = $this->setPicSize($this->myTempImage, $picSizeRatio);
			}
		}
		
		private function setPicSize($myTempImage, $picSizeRatio){
			$picW = imagesx($myTempImage);
			$picH = imagesy($myTempImage);
			$picNewW = round($picW / $picSizeRatio, 0);
			$picNewH = round($picH / $picSizeRatio, 0);
			$newImage = imagecreatetruecolor($picNewW, $picNewH);
			imagecopyresampled($newImage, $myTempImage, 0, 0, 0, 0, $picNewW, $picNewH, $picW, $picH);
			return $newImage;
		}
		
		public function addWatermark(waterMarkFile){
			$waterMark = this->makeImage($waterMarkFile, "png");
			$waterW = imagesx($waterMark);
			$waterH = imagesy($waterMark);
			$waterX = imagesx($this->myNewImage) - $waterW - 10;
			$waterY = imagesy($this->myNewImage) - $waterH - 10;
			imagecopy($this->myNewImage, $waterMark, $waterX, $waterY, 0, 0, $waterW, $waterH);
		}
		
		public function saveImage($targetFile){
			$notice = null;
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				if(imagejpeg($this->myNewImage, $targetFile, 90)){
					$notice = "Vähendatud pilt edukalt salvestatud!";
				} else {
					$notice = "Vähendatud pildi salvestamine ebaõnnestus!";
				}
			}
			if($this->imageFileType == "png"){
				if(imagepng($myNewImage, $targetFile, 6)){
					$notice = "Vähendatud pilt edukalt salvestatud!";
				} else {
					$notice = "Vähendatud pildi salvestamine ebaõnnestus!";
				}
			}
			if($this->imageFileType == "gif"){
				if(imagegif($myNewImage, $targetFile)){
					$notice = "Vähendatud pilt edukalt salvestatud!";
				} else {
					$notice = "Vähendatud pildi salvestamine ebaõnnestus!";
				}
			}
			imagedestroy($this->myNewImage);
			return $notice;
		}
		
	}//class lõppeb