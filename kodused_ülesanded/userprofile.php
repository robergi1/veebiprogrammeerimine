<?php
  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");


  require("classes/Session.class.php");
  SessionManager::SessionStart("vprobert", 0, "/~hansnoo/", "greeny.cs.tlu.ee");

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
  $myDescription = null;

    if(isset($_POST["submitPassword"])){
        $notice = change_password($_POST["oldpassword"],$_POST["newpassword"]);
    }


  if(isset($_POST["submitProfile"])){
	$notice = storeUserProfile(test_input($_POST["description"]), $_POST["bgcolor"], $_POST["txtcolor"]);
	if(!empty($_POST["description"])){
	  $myDescription = test_input($_POST["description"]);
	}
	
  } else {
	$myProfileDesc = showMyDesc();
	if($myProfileDesc != ""){
	  $myDescription = $myProfileDesc;
    }
  }
  
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
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Minu kirjeldus</label><br>
	  <textarea rows="10" cols="80" name="description" placeholder="Lisa siia oma tutvustus ..."><?php echo $myDescription; ?></textarea>
	  <br>
	  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $_SESSION["bgColor"]; ?>"><br>
	  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $_SESSION["txtColor"]; ?>"><br>
      <label>Vana parool: </label><input name="oldpassword" type="password" value=""><br>
      <label>Uus parool: </label><input name="newpassword" type="password" value=""><br>
      <input name="submitProfile" type="submit" value="Salvesta profiil"><span><?php echo $notice; ?></span><br>
      <input name="submitPassword" type="submit" value="Vaheta parool"><span><?php echo $notice; ?></span>
  </form>
  
</body>
</html>





