<?php
  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_news.php");
  require("funct.php");
  require("classes/Picupload.class.php");
  $database = "if19_robert_no_1";
  
  require("classes/Session.class.php");
  SessionManager::SessionStart("vprobert", 0, "/~hansnoo/", "greeny.cs.tlu.ee");
  
  //kui pole sisseloginud
  if(!isset($_SESSION["userId"])){
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
   
    if(isset($_POST["submituudis"])){
        $notice = change_uudis($_POST["uus"],$_POST["vana"]);
    }
 

  
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
      <label>Vana uudis: </label><input name="vana" value=""><br>
      <label>Uus uudis: </label><input name="uus" value=""><br>
      <input name="submituudis" type="submit" value="uuenda uudist"><span><?php echo $notice; ?></span>
  </form>
  
</body>
</html>