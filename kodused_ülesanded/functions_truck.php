<?php

//sessiooni kasutamise algatamine
//session_start();
//var_dump($_SESSION);

function insert_load($regNr, $name, $weight_in, $weight_out){

    $ret = False;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("INSERT INTO Truck_Loads (Truck_ID, Load_Name, Weight_IN, 	Weight_OUT) VALUES ((select ID from Trucks where Reg_NR=?), (?),(?),(?))");

    $stmt->bind_param("ssdd", $regNr,$name, $weight_in, $weight_out);
    if($stmt->execute()){
        $ret = True;
    }else{
        $ret = "Uue kauba viga: " .$stmt->error;
    }

    $stmt->close();

    $conn->close();

    return  $ret;

}

function insert_truck($reg_nr){

    $ret = False;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("INSERT INTO Trucks (Reg_NR) VALUES (?)");
    $stmt->bind_param("s", $reg_nr);
    if($stmt->execute()){
        $ret = True;
    }

    $stmt->close();

    $conn->close();

    return  $ret;

}

function get_trucks(){
    $truckHTML = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT Reg_NR FROM Trucks ORDER BY Reg_NR");
    echo $conn->error;
    $stmt->bind_result($regNrFromDb);
    $stmt->execute();
    $truckHTML .= "<select name='reg_nrs'>";


    while($stmt->fetch()){
        $truckHTML .= '<option value="' .$regNrFromDb .'"';

        $truckHTML .= ">" .$regNrFromDb . "</option> \n";
    }
    $truckHTML .= "</select>";
    $stmt->close();
    $conn->close();
    return $truckHTML;
}

function get_loads(){
    $truckHTML = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT Truck_ID, Load_Name, Weight_IN, Weight_OUT FROM Truck_Loads ORDER BY Truck_ID");
    echo $conn->error;
    $stmt->bind_result($idFromDb,$LoadFRomDb,$weight_IN,$weight_OUT);
    $stmt->execute();
    $truckHTML .= "<table>";
    $truckHTML .= "
    <tr>
        <th>KAUP</th>
        <th>SISSE</th>
        <th>VÄLJA</th>
        <th></th>
    </tr>";


    while($stmt->fetch()){
        $truckHTML .= "
        <tr>
            <td>".$LoadFRomDb."</td>
            <td>".$weight_IN."</td>
            <td>".$weight_OUT."</td>
            <td><input name=\"change\" type=\"submit\" value=\"Muuda\"></td>
        </tr>";
    }
    $truckHTML .= "</table>";
    $stmt->close();
    $conn->close();
    return $truckHTML;
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