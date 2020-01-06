<?php
  require("../../../config_vp2019.php");
  require("functin_film.php");
  //echo $serverHost;
  $userName = "Robert";
  $dataBase = "if19_robert_no_1";
  
  
  
  $filmInfoHTML = readAllFilms();
  
    require("header.php");
	echo "<h1>" .$userName .", veebiprogrammeerimine 2019</h1>";
  ?>
  <hr>
  <h2>Eesti filmid</h2>
  <p>Meie andmebaasis leiduvad järgnevad filmid</p>
  <hr>
  <?php
		echo $filmInfoHTML;
  ?>
 
</body>
</html>