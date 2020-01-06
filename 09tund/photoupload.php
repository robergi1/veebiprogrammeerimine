<?php
  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  require("functions_pic.php");
  $database = "if19_robert_no_1";
  
  //kui pole sisseloginud
  if(!isset($_SESSION["userId"])){
	  //siis jõuga sisselogimise lehele
	  header("Location: myindex.php");
	  exit();
  }
  
  //väljalogimine
  if(isset($_GET["logout"])){
	  session_destroy();
	  header("Location: myindex.php");
	  exit();
  }
  
  $userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];
  
  $notice = null;
  //var_dump($_POST);
  //var_dump($_FILES);
  
    //$target_dir = "uploads/";
	$uploadOk = 1;
	$maxPicW = 600;
	$maxPicH = 400;
	
	// Check if image file is a actual image or fake image
	if(isset($_POST["submitPic"])) {
		$fileName = "vp_";
		$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
		$timeStamp = microtime(1) * 10000;
		$fileName .= $timeStamp ."." .$imageFileType;
		$target_file = $pic_upload_dir_orig . $fileName;
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
		
		// Check if file already exists
		if (file_exists($target_file)) {
			echo "Sorry, file already exists.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 2500000) {
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			//muudan pildi suurust
			//loon "pildiobjekti" - image
			$myTempImage = makeImage($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
			//teeme kindlaks pildi suuruse
			$picW = imagesx($myTempImage);
			$picH = imagesy($myTempImage);
			//kui pilt ületab max väärtuse, siis muudamegi suurust
			if($picW > $maxPicW or $picH > $maxPicH){
				if($picW / $maxPicW > $picH / $maxPicH){
					$picSizeRatio = $picW / $maxPicW;
				} else {
					$picSizeRatio = $picH / $maxPicH;
				}
				$myNewImage = setPicSize($myTempImage, $picSizeRatio);
				//salvestame vähendatud kujutise faili
				$notice = saveImage($myNewImage, $pic_upload_dir_w600 .$fileName, $imageFileType);
				
				imagedestroy($myTempImage);
				imagedestroy($myNewImage);
			}//kui on liiga suur
			
			
			//kopeerime originaalfaili
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
			
			//salvestan info andmebaasi
			$notice .= addPicData($fileName, test_input($_POST["altText"]), $_POST["privacy"]);
		}
	
	}//kas vajutati nuppu
	
    
  require("header.php");
?>
<body>
  <?php
    echo "<h1>" .$userName ." koolitöö leht</h1>";
  ?>
  <p>See leht on loodud koolis õppetöö raames
  ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <p><a href="?logout=1">Logi välja!</a> | Tagasi <a href="home.php">avalehele</a></p>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	  <label>Vali pilt</label><br>
	  <input type="file" name="fileToUpload" id="fileToUpload">
	  <br>
	  <label>Alt tekst: </label><input type="text" name="altText">
	  <br>
	  <label>Privaatsus</label>
	  <br>
	  <input type="radio" name="privacy" value="1"><label>Avalik</label>&nbsp;
	  <input type="radio" name="privacy" value="2"><label>Sisseloginud kasutajatele</label>&nbsp;
	  <input type="radio" name="privacy" value="3" checked><label>Isiklik</label>
      <br>
	  <input name="submitPic" type="submit" value="Lae pilt üles"><span><?php echo $notice; ?></span>
	</form>
	<hr>
	  
</body>
</html>