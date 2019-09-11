<?php
  $username = "Robert Noor";
  $fullTimeNow = date("d.m.Y H:i:s");
?>
<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title>
  <?php
    echo $username;
  ?>
  programmeerib veebi</title>
</head>
<body>
  <?php
    echo "<h1>" $username ."veebiprogrammeerimine 2019</h1>";
	echo "<p>Lehe avamise hetkel oli " .$fullTimeNow .".</p>";
	?>
  <h1>robert, veebiprogrammeerimine 2019</h1>
  <p>See veebileht on valminud õppetöö käigus ning ei sisalda mingisugust tõsisevaltvõetavat sisu!</p>
</body>
</html>