<?php

function change_uudis($vana, $uus){
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT content FROM vpuudis WHERE id=?");
    echo $conn->error;
    $stmt->bind_param("i", $_SESSION["userId"]);
    $stmt->bind_result($idFromDb);
    $stmt->execute();
    if($stmt->fetch()){
	$stmt->close();
		$stmt = $conn->prepare("UPDATE vpuudis SET content = ? WHERE userid=?");
		echo $conn->error;
		$stmt->bind_param("si", $content, $_SESSION["userId"]);
		if($stmt->execute()){
			$_SESSION["content"] = $content;
			$notice = "uudis edukalt uuendatud!";
		} else {
			$notice = "Profiili salvestamisel tekkis tõrge! " .$stmt->error;
		}
	}
	$stmt->close();
	$conn->close();
	return $notice;
    }