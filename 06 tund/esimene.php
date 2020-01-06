  <?php
  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  $dataBase = "if19_robert_no_1";
  
  $notice = "";
  $email = "";
  $emailError = "";
  $passwordError = "";
  
  $photoDir = "../photos/";
  $photoTypesAllowed = ["image/jpeg", "image/png"];
  $fullTimeNow = date("d.m.Y H:i:s");
  $hourNow = date("H");
  $partOfDay = "hägune aeg";
  
  if($hourNow < 8){
	$partOfDay = "hommik";
  }
  if($hourNow >= 8 and $hourNow < 16){
		$partOfDay = "sobiv aeg akadeemiliseks aktiivsuseks";
	}
	if($hourNow >= 16 and $hourNow < 22){
		$partOfDay = "vaba aeg";
	}
	if($hourNow > 22){
		$partOfDay = "uneaeg";
	}
	
	//info semestrti kulgemise kohta
	//info semestrti kulgemise kohta
	$semesterStart = new DateTime("2019-9-2");
	$semesterEnd = new DateTime("2019-12-13");
	$semesterDuration = $semesterStart -> diff($semesterEnd);
	$today = new DateTime("now");
	$fromSemesterStart = $semesterStart -> diff($today);
	$semesterInfoHTML = "<p>Info semestri kohta pole kättesaadav.</p>";
	if ($fromSemesterStart -> format("%r%a") > 0 and $fromSemesterStart -> format("%r%a") <= $semesterDuration -> format("%r%a")){
		$semesterInfoHTML = "<p>Semester on täies hoos: ";
		$semesterInfoHTML .= '<meter min="0" ';
		$semesterInfoHTML .= 'max="' .$semesterDuration -> format("%r%a") .'" ';
		$semesterInfoHTML .= 'value="' .$fromSemesterStart -> format("%r%a") .'">';
		$semesterInfoHTML .= round($fromSemesterStart -> format("%r%a") / $semesterDuration -> format("%r%a") * 100, 1) ."%";
		$semesterInfoHTML .= "</meter></p>";
	}
	
	//juhusliku foto kasutamine
	$photoList = [];//array ehk massiiv
	
	$allFiles = array_slice(scandir($photoDir), 2);
	//kontrollin, kas on pildid
	foreach ($allFiles as $file){
		$fileInfo = getimagesize($photoDir .$file);

		if (in_array($fileInfo["mime"], $photoTypesAllowed) == true){
			array_push($photoList, $file);
		}
	}
	

	$photoCount = count($photoList);
	$randomImgHTML = "";
	if ($photoCount > 0){
	  $photoNum = mt_rand(0, $photoCount - 1);
	  $randomImgHTML = '<img src="' .$photoDir .$photoList[$photoNum] .'" alt="Juhuslik foto">';
	} else {
		$randomImgHTML = "<p>Kahjuks pilte pole!</p>";
	}
	
	//sisselogimine
	  if(isset($_POST["login"])){
		if (isset($_POST["email"]) and !empty($_POST["email"])){
		  $email = test_input($_POST["email"]);
		} else {
		  $emailError = "Palun sisesta kasutajatunnusena e-posti aadress!";
		}
	  
		if (!isset($_POST["password"]) or strlen($_POST["password"]) < 8){
		  $passwordError = "Palun sisesta parool, vähemalt 8 märki!";
		}
	  
		if(empty($emailError) and empty($passwordError)){
		   $notice = signIn($email, $_POST["password"]);
		} else {
			$notice = "Ei saa sisse logida!";
		}
	  }//kas POST login
?>
<!DOCTYPE html>
<html lang="et">
  <head>
    <meta charset="utf-8">
	<title>Veebiprogrammeerimine, 2019, Robert</title>
  </head>
  <body>
    <h1>Veebiprogrammeerimine</h1>
    <p>See veebileht on valminud õppetöö käigus ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
    
<?php
  echo $semesterInfoHTML;
  echo "<p>See on minu esimene PHP!</p>";
  echo "<p>Lehe avamise hetkel oli " .$fullTimeNow .", " .$partOfDay .".</p>";
?>
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>E-mail (kasutajatunnus):</label><br>
	  <input type="email" name="email" value="<?php echo $email; ?>">&nbsp;<span><?php echo $emailError; ?></span><br>
	  
	  <label>Salasõna:</label><br>
	  <input name="password" type="password">&nbsp;<span><?php echo $passwordError; ?></span><br>
	  
	  <input name="login" type="submit" value="Logi sisse">&nbsp;<span><?php echo $notice; ?>
	</form>
  <br>
  <h2>Kui pole kasutajakontot</h2>
  <p>Loo <a href="newuser.php">kasutajakonto</a>!</p>
  <hr>
  <?php
    echo $randomImgHTML;
  ?>

</body>
</html>