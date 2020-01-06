<?php
require("../../../config_vp2019.php");
require("functions_truck.php");


require("classes/Session.class.php");
SessionManager::SessionStart("vprobert", 0, "/~hansnoo/", "greeny.cs.tlu.ee");



if(isset($_POST["submitLoad"])){

    $err = insert_load($_POST["reg_nrs"], $_POST["name"], $_POST["weight_in"], $_POST["weight_out"]);
    if ($err){
        echo "Sisestatud";
    }else{
        echo $err;
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
echo "<h1>Veokite haldamine</h1>";
?>
<p>See veebileht on veokite !</p>
<hr>
<p>Olete sisseloginud! Logi <a href="?logout=1">välja</a>!</p>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php echo(get_loads());?>
    <br>
    <label>Koorma nimetus:</label>
    <input name="name" type="text" ><br>
    <br>
    <label>Sisenemismass:</label>
    <input name="weight_in" type="text" ><br>
    <br>
    <label>Väljumismass:</label>
    <input name="weight_out" type="text" ><br>

    <br>
    <p><a href="insert_truck.php">Sisesta veokeid</a>!</p>
    <br>
    <input name="submitLoad" type="submit" value="Sisesta koorem"><span></span>
</form>
</body>
</html>