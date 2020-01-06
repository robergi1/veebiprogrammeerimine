<?php
	//GET meetodiga saadetud väärtused
    require("../../../config_vp2019.php");
    //session_start();

	require("classes/Session.class.php");
    SessionManager::SessionStart("vprobert", 0, "/~hansnoo/", "greeny.cs.tlu.ee");

	$rating = $_REQUEST["rating"];
    $photoId = $_REQUEST["photoId"];
	$userid = $_SESSION["userId"];

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("INSERT INTO vpphotoratings (photoid, userid, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $photoId, $userid, $rating);
    $stmt->execute();
    $stmt->close();
    //küsime uue keskmise hinde

    $stmt=$conn->prepare("SELECT AVG(rating)FROM vpphotoratings WHERE photoid=?");
	$stmt->bind_param("i", $photoId);
	$stmt->bind_result($score);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$conn->close();
	//ümardan keskmise hinde kaks kohta pärast koma ja tagastan
	echo round($score, 2);