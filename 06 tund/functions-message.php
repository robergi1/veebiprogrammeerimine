<?php
function storeMessage($myMessage){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO vpmsg (userid, message) VALUES(?,?)");
	echo $conn -> error;
	$stmt -> bind_param("is", $_SESSION["userId"], $myMessage);
	if($stmt->execute()){
		$notice = "Sõnum salvestati!";
	} else {
	    $notice = "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;
    }
	$stmt -> close();
	$conn -> close();
	return $notice;
}

function old_readAllMessages(){
	$messagesHTML = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//$stmt = $conn -> prepare("SELECT message, created FROM vpmsg1");
	$stmt = $conn -> prepare("SELECT message, created FROM vpmsg WHERE deleted IS NULL");
	echo $conn -> error;
	$stmt -> bind_result($messageFromDb, $createdFromDb);
	$stmt -> execute();
	while($stmt -> fetch()){
		$messagesHTML .= "<li>" .$messageFromDb ." Lisatud: " .$createdFromDb ."</li> \n";
	}
	if(!empty($messagesHTML)){
		$messagesHTML = "<ul> \n" .$messagesHTML ."</ul> \n";
	} else {
		$messagesHTML = "<p>Sõnumeid pole!</p> \n";
	}
	
	$stmt -> close();
	$conn -> close();
	return $messagesHTML;
}

function readMyMessages(){
	$messagesHTML = "";
	$limit = 5;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//$stmt = $conn -> prepare("SELECT message, created FROM vpmsg1");
	//$stmt = $conn -> prepare("SELECT message, created FROM vpmsg1 WHERE deleted IS NULL");
	$stmt = $conn -> prepare("SELECT message, created FROM vpmsg1 WHERE userid = ? AND deleted IS NULL ORDER BY created DESC LIMIT ?");
	echo $conn -> error;
	$stmt -> bind_param("ii", $_SESSION["userId"], $limit);
	$stmt -> bind_result($messageFromDb, $createdFromDb);
	$stmt -> execute();
	while($stmt -> fetch()){
		$messagesHTML .= "<li>" .$messageFromDb ." Lisatud: " .$createdFromDb ."</li> \n";
	}
	if(!empty($messagesHTML)){
		$messagesHTML = "<ul> \n" .$messagesHTML ."</ul> \n";
	} else {
		$messagesHTML = "<p>Sõnumeid pole!</p> \n";
	}
	
	$stmt -> close();
	$conn -> close();
	return $messagesHTML;
}

function readAllMessages(){
	$messagesHTML = "";
	$limit = 10;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//$stmt = $conn -> prepare("SELECT message, created FROM vpmsg1");
	//$stmt = $conn -> prepare("SELECT message, created FROM vpmsg1 WHERE deleted IS NULL");
	$stmt = $conn -> prepare("SELECT vpusers.firstname, vpusers.lastname, vpmsg.message, vpmsg.created FROM vpusers JOIN vpmsg on vpusers1.id = vpmsg.userid WHERE deleted IS NULL ORDER BY vpmsg.created DESC LIMIT ?");
	echo $conn -> error;
	$stmt -> bind_param("i", $limit);
	$stmt -> bind_result($firstnameFromDb, $lastnameFromDb, $messageFromDb, $createdFromDb);
	$stmt -> execute();
	while($stmt -> fetch()){
		$messagesHTML .= "<li><b>" .$firstnameFromDb ." " .$lastnameFromDb ."</b>: " .$messageFromDb ." Lisatud: " .$createdFromDb ."</li> \n";
	}
	if(!empty($messagesHTML)){
		$messagesHTML = "<ul> \n" .$messagesHTML ."</ul> \n";
	} else {
		$messagesHTML = "<p>Sõnumeid pole!</p> \n";
	}
	
	$stmt -> close();
	$conn -> close();
	return $messagesHTML;
}