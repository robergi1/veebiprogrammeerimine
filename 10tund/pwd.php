<?php
function storePassword($password){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn -> prepare("INSERT INTO vpusers (userid, password) VALUES(?,?)");
	echo $conn -> error;
	$stmt -> bind_param("is", $_SESSION["userId"], $password);
	if($stmt -> execute()){
		$notice = "salasõna on vahetatud!";
	} else {
		$notice = "salasõna vahetamisel tekkis tõrge: " .$stmt -> error;
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}

function newPassword(){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn -> prepare("SELECT password, created FROM vpusers WHERE userid = ? AND deleted IS NULL ORDER BY created DESC");
	echo $conn -> error;
	$stmt -> bind_param("i", $_SESSION["userId"]);
	$stmt -> bind_result($passwordFromDb, $createdFromDb);
	$stmt -> execute();
	while($stmt -> fetch()){
		$notice .= "<li>" .$passwordFromDb ." (Lisatud: " .$createdFromDb .")</li> \n";
	}

