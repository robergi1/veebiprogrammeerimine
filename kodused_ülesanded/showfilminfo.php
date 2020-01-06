<?php
  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  require("functions_film.php");
  $database = "if19_robert_no_1";


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
  $filmInfoHTML = null;
  //var_dump($_POST);
  
  unset($_SESSION["filmPersonAdded"]);
  unset($_SESSION["filmAdded"]);
  unset($_SESSION["filmProfessionAdded"]);

    if(isset($_POST["filmDuration"])) {
        $minduration = $_POST["filmDuration"];

    }

  if(isset($_POST["getFilmInfo"])){
      if(isset($_POST["filmDuration"])) {
          $minduration = $_POST["filmDuration"];
          $filmInfoHTML = showFullDataByPerson($minduration);
      }

  }//
  #$filmInfoHTML = showFullDataByPerson($minduration);
  
  require("header.php");
?>

  <?php
    echo "<h1>" .$userName ." koolitöö leht</h1>";
  ?>
  <p>See leht on loodud koolis õppetöö raames
  ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <p><a href="?logout=1">Logi välja!</a> | Tagasi <a href="home.php">avalehele</a></p>
  <h2>Eesti filmid ja filmitegelased</h2>
  <p>Lisa uut <a href="addfilminfo.php">infot</a>!</p>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" style="display:inline;">
	  <input name="filmDuration" type="number" value="<?php echo $minduration;?>">

	  <input name="getFilmInfo" type="submit" value="Näita filme">
  </form>

	
	<hr>
	<?php
		echo $filmInfoHTML;
	?>
  
</body>
</html>





