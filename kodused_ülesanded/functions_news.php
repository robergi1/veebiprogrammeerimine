<?php

function insertNews($userid, $title, $content, $expire){
    $notice = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("INSERT INTO news (userid, title, content, expire) VALUES(?,?,?,?)");
    echo $conn->error;

    #$userid = 4;
    #$title = "PEalkiri";
    #$content = "Content";
    #$expire = "2019-12-11";
    $stmt->bind_param("isss", $userid, $title, $content, $expire);

    if($stmt->execute()){
        $notice = "Kasutaja salvestamine õnnestus!";
    } else {
        $notice = "Kasutaja salvestamisel tekkis tehniline tõrge: " .$stmt->error;
    }

    $stmt->close();
    $conn->close();
    return $notice;
}