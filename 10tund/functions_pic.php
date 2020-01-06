<?php
function addPicData($fileName, $altText, $privacy){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO vpphotos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
	echo $conn->error;
	$stmt->bind_param("issi", $_SESSION["userId"], $fileName, $altText, $privacy);
	if($stmt->execute()){
		$notice = " Pildi andmed salvestati andmebaasi!";
	} else {
		$notice = " Pildi andmete salvestamine ebaönnestus tehnilistel põhjustel! " .$stmt->error;
	}
	$stmt->close();
	$conn->close();
	return $notice;
}

function showPics($privacy, $page, $limit){
	$picHTML = null;
	$skip = ($page - 1) * $limit;
	
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy<=? AND deleted IS NULL");
	$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy<=? AND deleted IS NULL ORDER BY id DESC LIMIT ?,?");
	echo $conn->error;
	$stmt->bind_param("iii", $privacy, $skip, $limit);
	$stmt->bind_result($fileNameFromDb, $altTextFromDb);
	$stmt->execute();
	while($stmt->fetch()){
		//<img src="kataloog/pildifail" alt="tekst">
		$picHTML .= '<img src="' .$GLOBALS["pic_upload_dir_thumb"] .$fileNameFromDb .'" alt="';
		if(empty($altTextFromDb)){
			$picHTML .= "Illustreeriv foto";
		} else {
			$picHTML .= $altTextFromDb;
		}
		$picHTML .= '">' ."\n";
	}
	if($picHTML == null){
		$picHTML = "<p>Kahjuks pilte ei leitud!</p>";
	}
	
	$stmt->close();
	$conn->close();
	return $picHTML;
}

function countPics($privacy){
	$picCount;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT COUNT(id) FROM vpphotos WHERE privacy<=? AND deleted IS NULL");
	echo $conn->error;
	$stmt->bind_param("i", $privacy);
	$stmt->bind_result($countFromDb);
	$stmt->execute();
	$stmt->fetch();
	$picCount = $countFromDb;
	$stmt->close();
	$conn->close();
	return $picCount;
}