<?php
  require("../../../config_vp2019.php");
  require("functions_film.php");
  //echo $serverHost;
  $userName = "robert noor";
  $database = "if19_robert_no_1";
  
  $filmInfoHTML = readAllFilms();
  $filmAge = 50;
  $oldFilmInfoHTML = readOldFilms($filmAge);
  
  require("header.php");
  echo "<h1>" .$userName .", veebiprogrammeerimine 2019</h1>";
?>
  <p>See veebileht on valminud õppetöö käigus ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
  <hr>
  <h2>Eesti filmid</h2>
  <p>Meie andmebaasis leiduvad järgmised filmid:</p>
  <hr>
  <?php
    echo $filmInfoHTML;
	echo "<hr>";
	echo "<h2>Filmid, mis on vanemad, kui " .$filmAge ." aastat.</h2>";
	echo $oldFilmInfoHTML;
  ?>

</body>
</html>