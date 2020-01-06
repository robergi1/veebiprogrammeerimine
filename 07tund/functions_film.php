<?php
  function readAllFilms(){
	  //loeme andmebaasist
	  //loome andmebaasiühenduse (näiteks $conn)
	  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	  //valmistame ette päringu
	  //$stmt = $conn->prepare("SELECT pealkiri, aasta FROM film");
	  $stmt = $conn->prepare("SELECT * FROM film");
	  //seome saadava tulemuse muutujaga
	  $stmt->bind_result($filmTitle, $filmYear, $filmDuration, $filmGenre, $filmStudio, $filmDirector);
	  //käivitame SQL päringu
	  $stmt->execute();
	  $filmInfoHTML = "";
	  while($stmt->fetch()){
		$filmInfoHTML .= "<h3>" .$filmTitle ."</h3>";
		$filmHours = round($filmDuration / 60, 0);
		$filmMinutes = $filmDuration%60;
		$filmDurationDesc = null;
		if ($filmHours > 0){
			if($filmHours == 1){
				$filmDurationDesc .= $filmHours ." tund ja ";
			} else {
				$filmDurationDesc .= $filmHours ." tundi ja ";
			}
			if($filmMinutes == 1){
				$filmDurationDesc .= $filmMinutes ." minut";
			} else {
				$filmDurationDesc .= $filmMinutes ." minutit";
			}
		}
		$filmInfoHTML .= "<p>Žanr: " .$filmGenre .", lavastaja: " .$filmDirector .". Kestus: " .$filmDurationDesc .". Tootnud: " .$filmStudio ." aastal: " .$filmYear .".</p>";
		//echo $filmTitle;
	  }
	  
	  //echo $filmInfoHTML;
	  //sulgeme ühenduse
	  $stmt->close();
	  $conn->close();
	  //väljastan väärtuse
	  return $filmInfoHTML;
  }
  
  function storeFilmInfo($filmTitle, $filmYear, $filmDuration, $filmGenre, $filmStudio, $filmDirector){
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES(?,?,?,?,?,?)");
	echo $conn->error;
	//s - string, i - integer, d - decimal
	$stmt->bind_param("siisss", $filmTitle, $filmYear, $filmDuration, $filmGenre, $filmStudio, $filmDirector);
	$stmt->execute();
	
	$stmt->close();
	$conn->close(); 
  }
  
  function readOldFilms($filmAge){
	  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	  $maxYear = date("Y") - $filmAge;
	  $stmt = $conn->prepare("SELECT pealkiri, aasta FROM film WHERE aasta < ?");
	  $stmt->bind_param("i", $maxYear);
	  $stmt->bind_result($filmTitle, $filmYear);
	  $stmt->execute();
	  $filmInfoHTML = "";
	  while($stmt->fetch()){
		$filmInfoHTML .= "<h3>" .$filmTitle ."</h3>";
		$filmInfoHTML .= "<p>Tootmisaasta: " .$filmYear .".</p>";
	  }
	  
	  $stmt->close();
	  $conn->close();
	  return $filmInfoHTML;
  }