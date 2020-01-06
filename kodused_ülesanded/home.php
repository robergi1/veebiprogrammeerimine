<?php
require("../../../config_vp2019.php");
require("functions_main.php");


require("classes/Session.class.php");
SessionManager::SessionStart("vprobert", 0, "/~hansnoo/", "greeny.cs.tlu.ee");

//kontrollime, kas on sisse loginud
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
}

// cookies e küpsised

setcookie("vpusername", $_SESSION["userFirstname"] . " " . $_SESSION["userLastname"],
    time() + (86400*31),"/~hansnoo/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]),true);


echo count($_COOKIE);
if (isset($_COOKIE["vpusername"])) {
    echo "Leiti küpsis " . $_COOKIE["vpusername"];
} else {
    echo "Ei ole küpsiseid";
}

$userName = $_SESSION["userFirstname"] . " " . $_SESSION["userLastname"];

require("header.php");
?>
<body>
<?php
echo "<h1>" . $userName . ", veebiprogrammeerimine 2019</h1>";
?>
<p>See veebileht on valminud õppetöö käigus ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
<hr>
<p>Olete sisseloginud! Logi <a href="?logout=1">välja</a>!</p>
<ul>
    <li><a href="userprofile.php">Kasutajaprofiil</a></li>
    <li><a href="messages.php">Sõnumid</a></li>
    <li><a href="showfilminfo.php">Filmid</a></li>
    <li><a href="picupload.php">Piltide üleslaadimine</a></li>
    <li><a href="news.php">Uudised</a></li>
    <li><a href="addfilminfo.php">Filmide lisamine</a></li>
    <li><a href="gallery.php">Gallerii</a></li>

</ul>

</body>
</html>