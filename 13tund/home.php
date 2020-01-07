<?php
  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_news.php");
  require("functions_pic.php");
  //require("functions_user.php");
  $database = "if19_robert_no_1";
  
  require("classes/Session.class.php");
  SessionManager::SessionStart("vprobert", 0, "/~hansnoo/", "greeny.cs.tlu.ee");
  
  //kontrollime, kas on sisse loginud
  if(!isset($_SESSION["userId"])){
	header("Location: myindex.php");
	exit();
  }
  
  //väljalogimine
  if(isset($_GET["logout"])){
	  //sessioon kinni
	  session_unset();
	  session_destroy();
	  header("Location: myindex.php");
	  exit();
  }
  
  //küpsised ehk cookie's
  //nimi, väärtus, aegumistähtaeg, kataloogi rada, domeen, kas https, kas http ühendus
  setcookie("vpusername", $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"], time() + (86400 * 31), "/~hansnoo/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);
  
  //kustutamiseks seada küpsis minivikku
  //time() -1000
  
  //echo "küpsiste arv: " .count($_COOKIE);
  if(isset($_COOKIE["vpusername"])){
	  //echo " / leiti küpsis: " .$_COOKIE["vpusername"];
  } else{
	  //echo "ei mingeid küpsiseid: ";
  }
  
  $photoDir = "../photos/";
  $photoTypesAllowed = ["image/jpeg", "image/png"];
  $newsHTML = latestNews(1);
  
  $userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];
  
  $photoList = [];
	
	$allFiles = array_slice(scandir($photoDir), 2);
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
	$latestPublicPictureHTML = latestPicture(1);
  
  require("header.php");
  
?>
<body>
<?php
  echo "<h1>" .$userName .", veebiprogrammeerimine 2019</h1>";
  ?>
  <p>See veebileht on valminud õppetöö käigus ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
  <hr>
  <p>Olete sisseloginud! Logi <a href="?logout=1">välja</a>!</p>
  <ul>
	<li><a href="addnews.php">Uudise lisamine</a></li>
	<li><a href="change_news.php">Uudise muutmine</a></li>
  </ul>
  <hr size="3">
  <?php
	echo $newsHTML, $latestPublicPictureHTML;
  ?>
  <hr size="3">

</body>
</html>

