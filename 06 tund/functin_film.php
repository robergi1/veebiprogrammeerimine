<?php
function readAllFilms(){
	  //loome andmebaasiühenduse
	  //$conn = new mysqli($serverHost, $serverUsername, $serverPassword, $dataBase);
	  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["dataBase"]);
	  //valmistame ette SQL päringu
	  $stmt = $conn->prepare("SELECT pealkiri, aasta FROM film");
	  echo $conn->error;
	  //seome saadava tulemuse muutujaga
	  $stmt->bind_result($filmTitle, $filmYear);
	  //täidame käsu ehk sooritame päringu
	  $stmt->execute();
	  echo $stmt->error;
	  $filmInfoHTML = null;
	  //võtan tulemuse (pinu ehk stack)
	  while($stmt->fetch()){
		 // echo $filmTitle;
		 $filmInfoHTML .= "<h3>" .$filmTitle ."</h3>";
		 $filmInfoHTML .= "<p>" .$filmYear ."</p>";
	  }
	//sulgeme ühendused
	$stmt->close();
	$conn->close();
	return $filmInfoHTML;
  }
  
  function storageFilmInfo($filmTitle, $filmYear, $filmDuration, $filmGenre, $filmStudio, $filmDirector){
    
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["dataBase"]);
	$stmt = $conn->prepare("INSERT INTO film(pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES(?,?,?,?,?,?)");
	echo $conn->error;
	//seon saadetava info muutujatega
	//andmetüübid: s - string, i - integer, d - decimal
	$stmt->bind_param("siisss", $filmTitle, $filmYear, $filmDuration, $filmGenre, $filmStudio, $filmDirector);
	$stmt->execute();
	
	$stmt->close();
	$conn->close();
  }