<?php

//sessiooni kasutamise algatamine
//session_start();
//var_dump($_SESSION);




function signUp($name, $surname, $email, $gender, $birthDate, $password){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
	echo $conn->error;
	
	//tekitame parooli räsi (hash) ehk krüpteerime
	$options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
	$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	
	$stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);
	
	if($stmt->execute()){
		$notice = "Kasutaja salvestamine õnnestus!";
	} else {
		$notice = "Kasutaja salvestamisel tekkis tehniline tõrge: " .$stmt->error;
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}

  function signIn($email, $password){
	$notice = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT password FROM vpusers WHERE email=?");
	echo $conn->error;
	$stmt->bind_param("s", $email);
	$stmt->bind_result($passwordFromDb);
	if($stmt->execute()){
		//kui päring õnnestus
	  if($stmt->fetch()){
		//kasutaja on olemas
		if(password_verify($password, $passwordFromDb)){
		  //kui salasõna klapib
		  $stmt->close();
		  $stmt = $conn->prepare("SELECT id, firstname, lastname FROM vpusers WHERE email=?");
		  echo $conn->error;
		  $stmt->bind_param("s", $email);
		  $stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);
		  $stmt->execute();
		  $stmt->fetch();
		  $notice = "Sisse logis " .$firstnameFromDb ." " .$lastnameFromDb ."!";
		  
		  //salvestame kasutaja kohta loetud info sessioonimuutujatesse
		  $_SESSION["userId"] = $idFromDb;
		  $_SESSION["userFirstname"] = $firstnameFromDb;
		  $_SESSION["userLastname"] = $lastnameFromDb;
		  
		  //loeme kasutajaprofiili
		  $stmt->close();
		  $stmt = $conn->prepare("SELECT bgcolor, txtcolor FROM vpuserprofiles WHERE userid=?");
		  echo $conn->error;
		  $stmt->bind_param("i", $_SESSION["userId"]);
		  $stmt->bind_result($bgColorFromDb, $txtColorFromDb);
		  $stmt->execute();
		  if($stmt->fetch()){
			$_SESSION["bgColor"] = $bgColorFromDb;
	        $_SESSION["txtColor"] = $txtColorFromDb;
		  } else {
		    $_SESSION["bgColor"] = "#FFFFFF";
	        $_SESSION["txtColor"] = "#000000";
		  }
		  
		  //enne sisselogitutele mõeldud lehtedele jõudmist sulgeme andmebaasi ühendused
		  $stmt->close();
	      $conn->close();
		  //liigume soovitud lehele
		  header("Location: home.php");
		  //et siin rohkem midagi ei tehtaks
          exit();		  
		  
		} else {
		  $notice = "Vale salasõna!";
		}//kas password_verify
	  } else {
		$notice = "Sellist kasutajat (" .$email .") ei leitud!";
		//kui sellise e-mailiga ei saanud vastet (fetch ei andnud midagi), siis pole sellist kasutajat
	  }//kas fetch õnnestus
	} else {
	  $notice = "Sisselogimisel tekkis tehniline viga!" .$stmt->error;
	  //veateade, kui execute ei õnnestunud
	}//kas execute õnnestus
	
	$stmt->close();
	$conn->close();
	return $notice;
  }//sisselogimine lõppeb
  
  //kasutajaprofiili salvestamine
  function storeuserprofile($description, $bgColor, $txtColor){
	$notice = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT id FROM vpuserprofiles WHERE userid=?");
	echo $conn->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($idFromDb);
	$stmt->execute();
	if($stmt->fetch()){
		//profiil juba olemas, uuendame
		$stmt->close();
		$stmt = $conn->prepare("UPDATE vpuserprofiles SET description = ?, bgcolor = ?, txtcolor = ? WHERE userid=?");
		echo $conn->error;
		$stmt->bind_param("sssi", $description, $bgColor, $txtColor, $_SESSION["userId"]);
		if($stmt->execute()){
			$_SESSION["bgColor"] = $bgColor;
			$_SESSION["txtColor"] = $txtColor;
			$notice = "Profiil edukalt uuendatud!";
		} else {
			$notice = "Profiili uuendamisel tekkis tõrge! " .$stmt->error;
		}
		//$notice = "Profiil olemas, ei salvestanud midagi!";
	} else {
		//profiili pole, salvestame
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
		echo $conn->error;
		$stmt->bind_param("isss", $_SESSION["userId"], $description, $bgColor, $txtColor);
		if($stmt->execute()){
			$_SESSION["bgColor"] = $bgColor;
			$_SESSION["txtColor"] = $txtColor;
			$notice = "Profiil edukalt salvestatud!";
		} else {
			$notice = "Profiili salvestamisel tekkis tõrge! " .$stmt->error;
		}
	}
	$stmt->close();
	$conn->close();
	return $notice;
  }


function change_password($oldpassword, $newpassword){
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT password FROM vpusers WHERE id=?");
    echo $conn->error;
    $stmt->bind_param("i", $_SESSION["userId"]);
    $stmt->bind_result($passwordFromDb);
    $stmt->execute();


    $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
    $newHash = password_hash($newpassword, PASSWORD_BCRYPT, $options);

    if($stmt->fetch()){
        #echo $pwdhash."<br>";
        #echo $passwordFromDb;

        if(strlen($newpassword) < 8){
            $notice = "Liiga lühike salasõna (sisestasite ainult " .strlen($newpassword) ." märki).";
        }else{
            if(password_verify($oldpassword, $passwordFromDb)){
                echo "kattuvad";

                $stmt->close();
                $stmt = $conn->prepare("UPDATE vpusers SET password = ? WHERE id=?");
                echo $conn->error;
                $stmt->bind_param("si", $newHash,  $_SESSION["userId"]);
                if($stmt->execute()){

                    $notice = "Parool edukalt uuendatud!";
                } else {
                    $notice = "Parooli uuendamisel tekkis tõrge! " .$stmt->error;
                }


            }
            else{
                $notice = "Paroolid ei kattu";#.$pwdhash. "<br>".$passwordFromDb;
            }
        }

    }

    #$notice = "Profiili salvestamisel tekkis tõrge! " .$stmt->error;
    $stmt->close();
    $conn->close();
    return $notice;
}


function showMyDesc(){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT description FROM vpuserprofiles WHERE userid=?");
	echo $conn->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($descriptionFromDb);
	$stmt->execute();
    if($stmt->fetch()){
	  $notice = $descriptionFromDb;
	}
	$stmt->close();
	$conn->close();
	return $notice;
  }