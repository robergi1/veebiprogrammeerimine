<?php
require("../../../config_vp2019.php");
require("functions_news.php");
require("functions_main.php");

$error = null;
$newsTitle = null;
$newsContent = null;
$expiredate = null;

$titleError = null;
$contentError = null;
$dateError = null;

  require("classes/Session.class.php");
  SessionManager::SessionStart("vprobert", 0, "/~hansnoo/", "greeny.cs.tlu.ee");

if(isset($_POST["newsBtn"])) {

    if (isset($_POST["newsTitle"]) and !empty($_POST["newsTitle"])) {
        $newsTitle = test_input($_POST["newsTitle"]);
    } else {
        $titleError = "Palun sisesta uudise pealkiri!";
    }

    if (isset($_POST["newsEditor"]) and !empty($_POST["newsEditor"])) {
        $newsContent = test_input($_POST["newsEditor"]);
        $newsContent = strip_tags(html_entity_decode($newsContent));
    } else {
        $contentError = "Palun sisesta uudise sisu!";
    }

    if (isset($_POST["expiredate"]) and !empty($_POST["expiredate"])) {
        $expiredate = test_input($_POST["expiredate"]);
    } else {
        $dateError = "Palun sisesta uudise sisu!";
    }

    $userid = $_SESSION["userId"];
    $userid = 4;

    if(empty($titleError) and empty($contentError) and empty($dateError)){
        $notice = insertNews($userid, $newsTitle, $newsContent, $expiredate);

    } else {
        $notice = "Ei saa salvestada, andmed on puudulikud!";
    }




}

?>
//Javascript osa:
<!-- Lisame tekstiredaktory TinyMCE -->
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

<script>tinymce.init({selector:"textarea#newsEditor", plugins: "link", menubar: "edit",});</script>




<h2>Lisa uudis</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label>Uudise pealkiri:</label><br><input type="text" name="newsTitle" id="newsTitle" style="width: 100%;" value="<?php echo $newsTitle; ?>"><span><?php echo $titleError; ?></span><br>
    <label>Uudise sisu:</label><br>
    <textarea name="newsEditor" id="newsEditor"><?php echo $newsContent; ?></textarea>
    <br>
    <label>Uudis nähtav kuni (kaasaarvatud)</label>
    <input type="date" name="expiredate" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php echo $expiredate; ?>">

    <input name="newsBtn" id="newsBtn" type="submit" value="Salvesta uudis!"> <span>&nbsp;</span><span><?php echo $error; ?></span>
</form>



//Kui lasete uudise läbi test_input funktsiooni, siis html "<" ja ">" muudetakse koodideks. Uudise näitamisel siis tuleb need tagasi muuta ja selleks on vaja andmetabelist loetud uudis lasta läbi php funktsiooni htmlspecialchars_decode()