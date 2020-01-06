<?php
  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  require("newuser.php");
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
    
  if(isset($_POST["submitPassword"])){
	if(!empty(test_input($_POST["Password"]))){
		$notice = storeMessage(test_input($_POST["Password"]));
	} else {
		$notice = "Uut salasõna ei saadud luua!";
	}
  }
  
  //$messageHTML = readAllMessages();
  $messageHTML = readMyMessages();
  
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
  
  
</body>
</html>





