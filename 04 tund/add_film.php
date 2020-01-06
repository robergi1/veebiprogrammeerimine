<?php
  require("../../../config_vp2019.php");
  require("functin_film.php");
  //echo $serverHost;
  $userName = "Robert";
  $dataBase = "if19_robert_no_1";
  
  //var_dump($_POST);
  if(isset($_POST["submitFilm"])){
  //echo "keegi submittis";
  if(!empty($_POST["filmTitle"])){
	storeFilmInfo($_POST["filmTitle"], $_POST["filmYear"], $_POST["filmDuration"], $_POST["filmGenre"], $_POST["filmStudio"], $_POST["filmDirector"]);  
    }  
  }
    
  //$filmInfoHTML = readAllFilms();
  
      require("header.php");
	
  ?>
  
  <hr>
  <h2>Eesti filmi info lisamine</h2>
  <p>Meie andmebaasi uue filmi lisamine</p>
  <hr>
  <form method="POST">
	<label>Filmi pealkiri: </label>
	<input type="text" name="filmTitle">
	<br>
	<label>Filmi tootmis aasta: </label>
	<input type="number" min="1912" max="2019" value="2019" name="filmYear">
	<br>
	<label>Filmi kestus (min): </label>
	<input type="number" min="1" max="300" value="80" name="filmDuration">
	<br>
	<label>Filmi zanr: </label>
	<input type="text" name="filmGenre">
	<br>
	<label>Filmi tootja: </label>
	<input type="text" name="filmStudio">
	<br>
	<label>Filmi lavastaja: </label>
	<input type="text" name="filmDirector">
	<br>
	<input type="submit" value="Talleta filmi info" name="submitFilm">
  </form>

</body>
</html>




