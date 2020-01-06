<?php
  require("../../../config_vp2019.php");
  //require("functions_main.php");
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
  
  $page = 1;
  $limit = 5;
  $picCount = countPics(2);
  if(!isset($_GET["page"]) or $_GET["page"] < 1){
	  $page = 1;
  } elseif (round(($_GET["page"] - 1) * $limit) >= $picCount){
	  $page = round($picCount / $limit) - 1;
  } else {
	  $page = round($_GET["page"]);
  }
  
  $galleryHTML = showPics(2, $page, $limit);
  
  $toScript = "\t" .'<link rel="stylesheet" type="text/css" href="style/modal.css">' ."\n";
  $toScript .= "\t" .'<script type="text/javascript" src="javascript/gallery.js" defer></script>' ."\n";
  
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
  <hr>
  <h2>Pildigalerii</h2>
  
  <div id="myModal" class="modal">
	<span id="close" class="close">&times;</span>
	<img id="modalImg" class="modal-content" alt="pilt">
	<div id="caption" class="caption"></div>
	<div id="rating" class="modalcaption">
		<label><input type="radio" id="rate1" name="rating" value"1">1</label>
		<label><input type="radio" id="rate2" name="rating" value"2">2</label>
		<label><input type="radio" id="rate3" name="rating" value"3">3</label>
		<label><input type="radio" id="rate4" name="rating" value"4">4</label>
		<label><input type="radio" id="rate5" name="rating" value"5">5</label>
		<input type="button" value="Salvesta hinnang" id="storeRating">
	</div>
  </div>
  
  <p>
  <?php
	if($page > 1){
		echo '<a href="?page=' .($page - 1) .'">Eelmine leht</a> | ';
	} else {
		echo "<span>Eelmine leht</span> | ";
	}
	if($page * $limit < $picCount){
		echo '<a href="?page=' .($page + 1) .'">Järgmine leht</a>';
	} else {
		echo "<span>Järgmine leht</span>";
	}
  ?>
  
  <!--<a href="?page=1">Eelmine leht</a><a href="?page=2">Järgmine leht</a>-->
  </p>
  <div id="gallery">
  <?php
	echo $galleryHTML;
  ?>
  </div>
	  
</body>
</html>