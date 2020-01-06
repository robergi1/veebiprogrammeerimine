<?php
function storeMessage($message){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn -> prepare("INSERT INTO vpmsg (userid, message) VALUES(?,?)");
	echo $conn -> error;
	$stmt -> bind_param("is", $_SESSION["userId"], $message);
	if($stmt -> execute()){
		$notice = "Sõnum salvestati!";
	} else {
		$notice = "Sõnmi salvestamisel tekkis tehniline tõrge: " .$stmt -> error;
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}

function readMyMessages(){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn -> prepare("SELECT message, created FROM vpmsg2 WHERE userid = ? AND deleted IS NULL ORDER BY created DESC");
	echo $conn -> error;
	$stmt -> bind_param("i", $_SESSION["userId"]);
	$stmt -> bind_result($messageFromDb, $createdFromDb);
	$stmt -> execute();
	while($stmt -> fetch()){
		$notice .= "<li>" .$messageFromDb ." (Lisatud: " .$createdFromDb .")</li> \n";
	}
	if(!empty($notice)){
		$notice = "<ul> \n" .$notice ."</ul> \n";
	} else {
		$notice = "<p>Kahjuks sõnumeid ei ole!</p> \n";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}

function readAllMessages(){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn -> prepare("SELECT message, created FROM vpmsg WHERE deleted IS NULL ORDER BY created DESC");
	echo $conn -> error;
	$stmt -> bind_result($messageFromDb, $createdFromDb);
	$stmt -> execute();
	while($stmt -> fetch()){
		$notice .= "<li>" .$messageFromDb ." (Lisatud: " .$createdFromDb .")</li> \n";
	}
	if(!empty($notice)){
		$notice = "<ul> \n" .$notice ."</ul> \n";
	} else {
		$notice = "<p>Kahjuks sõnumeid ei ole!</p> \n";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}






