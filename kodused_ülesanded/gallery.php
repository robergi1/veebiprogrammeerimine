<?php
  require("../../../config_vp2019.php");
  require("functions_user.php");
  require("functions_pic.php");

  require("classes/Session.class.php");
  SessionManager::SessionStart("vprobert", 0, "/~hansnoo/", "greeny.cs.tlu.ee");

  
  //kui pole sisseloginud
  if(!isset($_SESSION["userId"])){
	  //siis jõuga sisselogimise lehele
	  header("Location: myindex.php");
	  exit();
  }
  
  //väljalogimine
  if(isset($_GET["logout"])){
	  session_destroy();
	  header("Location: myindex.php");
	  exit();
  }
    $privacy = 1;
    if(isset($_POST["public"])){
        $privacy = 1;
    }

    if(isset($_POST["private"])){
        $privacy = 2;
    }

    if(isset($_POST["personal"])){
        $privacy = 3;
    }

$userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];

$notice = null;

$page = 1;
$limit = 2;
#$privacy = 3;
$picCount = countPics($privacy);
if(!isset($_GET["page"]) or $_GET["page"] < 1){
    $page = 1;
} elseif (round(($_GET["page"] - 1) * $limit) >= $picCount){
    $page = ceil($picCount / $limit);
} else {
    $page = round($_GET["page"]);
}

$galleryHTML = showPics($privacy, $page, $limit);

$toScript = "\t" .'<link rel="stylesheet" type="text/css" href="style/modal.css">' ."\n";
$toScript .= "\t" .'<script type="text/javascript" src="javascript/gallery.js" defer></script>' ."\n";

require("header.php");
?>
<body>
<?php
echo "<h1>" .$userName ." koolitöö leht</h1>";
?>
<p>See leht on loodud koolis õppetöö raames
    ja ei sisalda tõsiseltvõetavat sisu!</p>
<hr>
<p><a href="?logout=1">Logi välja!</a> | Tagasi <a href="home.php">avalehele</a></p>
<hr>
<h2>Pildigalerii</h2>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="submit" value="Avalikud" name="public">
    <input type="submit" value="Privaatsed" name="private">
    <input type="submit" value="Isiklikud" name="personal">
</form>

<!--Piltide näitamise modaalaken W3Schools eeskujul-->
<div id="myModal" class="modal">
    <!--sulgemisnupp-->
    <span id="close" class="close">&times;</span>
    <!--pildikoht-->
    <img id="modalImg" class="modal-content" alt="pilt">
    <!--pilditekst-->
    <div id="caption"></div>

    <div id="pictureid"></div>

    <div id="rating" class="modalcaption">
        <label><input type="radio" id="rate1" name="rating" value="1">1</label>
        <label><input type="radio" id="rate2" name="rating" value="2">2</label>
        <label><input type="radio" id="rate3" name="rating" value="3">3</label>
        <label><input type="radio" id="rate4" name="rating" value="4">4</label>
        <label><input type="radio" id="rate5" name="rating" value="5">5</label>
        <input type="button" value="Salvesta hinnang" id="storeRating">
        <br>
        <span id="avgRating"></span>
    </div>

</div>

<p>
    <?php
    if($page > 1){
        echo '<a href="?page=' .($page - 1) .'">Eelmine leht</a> | ';
    } else {
        echo "<span>Eelmine leht</span> | ";
    }
    if($page * $limit < $picCount){
        echo '<a href="?page=' .($page + 1) .'">Järgmine leht</a>';
    } else {
        echo "<span>Järgmine leht</span>";
    }
    ?>

    <!--<a href="?page=1">Eelmine leht</a><a href="?page=2">Järgmine leht</a>-->
</p>
<div id="gallery">
    <?php
    echo $galleryHTML;
    ?>
</div>
</body>
</html>




