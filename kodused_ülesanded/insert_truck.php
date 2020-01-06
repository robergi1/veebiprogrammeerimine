<?php
require("../../../config_vp2019.php");
require("functions_truck.php");


require("classes/Session.class.php");
SessionManager::SessionStart("vprobert", 0, "/~hansnoo/", "greeny.cs.tlu.ee");

if(isset($_POST["submitTruck"])){
    $err = insert_truck($_POST["reg_nr"]);
    if ($err){
        echo "Sisestatud";
    }else{
        echo "Selline auto on juba andmebaasis!";
    }
}

//kontrollime, kas on sisse loginud
/*
if (!isset($_SESSION["userId"])) {
    header("Location: myindex.php");
    exit();
}

//väljalogimine
if (isset($_GET["logout"])) {
    //sessioon kinni
    session_unset();
    session_destroy();
    header("Location: myindex.php");
    exit();
}*/

// cookies e küpsised

?>
<body>
<?php
echo "<h1>Veokite sisestus</h1>";
?>
<p>See veebileht on veokite sisestamise jaoks!</p>
<br>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

    <label>Auto registrinumber:</label>
    <input name="reg_nr" type="text" ><span></span><br>
    <br>
    <p><a href="insert_load.php">Koorma sisestus</a></p>
    <br>
    <input name="submitTruck" type="submit" value="Sisesta auto"><span></span>
</form>
</body>
</html>