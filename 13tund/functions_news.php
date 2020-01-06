<?php
	function saveNews($newsTitle, $news, $expiredate){
		$response = 0;
		//echo "SALVESTATAKSE UUDIST!";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpuudis (userid, title, content, expire) VALUES (?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("isss", $_SESSION["userId"], $newsTitle, $news, $expiredate);
		if($stmt->execute()){
			$response = 1;
		} else {
			$response = 0;
			echo $stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $response;
	}

	function latestNews($limit){
		$newsHTML = null;
		$today = date("Y-m-d");
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT title, content, added FROM vpuudis WHERE expire >=? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("si", $today, $limit);
		$stmt->bind_result($titleFromDb, $contentFromDb, $addedFromDb);
		$stmt->execute();
		while ($stmt->fetch()){
			$newsHTML .= "<div> \n";
			$newsHTML .= "\t <h3>" .$titleFromDb ."</h3> \n";
			$addedTime = new DateTime($addedFromDb);
			//$newsHTML .= "\t <p>(Lisatud: " .$addedFromDb .")</p> \n";
			$newsHTML .= "\t <p>(Lisatud: " .$addedTime->format("d.m.Y H:i:s") .")</p> \n";
			$newsHTML .= "\t <div>" .htmlspecialchars_decode($contentFromDb) ."</div> \n";
			$newsHTML .= "</div> \n";
		}
		if($newsHTML == null){
			$newsHTML = "<p>Kahjuks uudiseid pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $newsHTML;
	}