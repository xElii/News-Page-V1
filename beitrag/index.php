<?php 
require_once('/DATEN/Config/db.php');
$id = $_GET['id'];
if ($id == null) {
    header('Location: /');
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/lib/style.css">
    <link rel="icon" href="/lib/favicon.webp" type="image/webp">
    <script src="/lib/jquery.js"></script>
    <script src="https://kit.fontawesome.com/09de98c34f.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body>
    <div id="navbar-load"></div>
    <div class="post-container" style="margin-top: 60px;"><br>
        <?php
        // Get article
        $sqlb = "SELECT * FROM news WHERE id=$id";
        $result = $link->query($sqlb);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ($row["public"] == 0) {
                    $title="Beitrag gesperrt!";
                    $content="Dieser Beitrag ist gesperrt, deshalb kannst du ihn nicht lesen. Vielleicht ist er später Verfügbar.";
                    $beitragid=$row['id'];
                } else {
                    $title=$row['title'];
                    $content=$row['content'];
				    $beitragid=$row['id'];
					$views='<span><i class="fa-solid fa-eye"></i>'.$row['views'].'</span>';
                    $time=date_create($row["time"])->format("d. M Y");
                }
            }
        } else {
            echo "<h1 style='color: red; text-align: center;'>Es gibt keinen Beitrag mit der ID: $id</h1>";
        }
        // Add 1 view to article
        if ($link->query("UPDATE news SET views=views+1 WHERE id = $id") === TRUE) {}
        ?><title>Beitrag <?php echo $beitragid; ?> | Datalok News</title><br>
        <a href="#" onclick="history.back()" data-aos="fade-down"><- Zurück</a><br><br>
        <time data-aos="fade-down"><?php echo $time?></time>
        <h1 data-aos="fade-right" style="display:flex; align-items:center; margin-bottom:8px;"><?php echo $title?></h1>
        <div style="display:flex; align-items:center;"><?php echo $views?></div>
        <hr>
        <span data-aos="zoom-in"><?php echo $content?></span>
    </div><br><br>
<script>
var likes = <?php echo $likes?>;
document.getElementById("likespan").innerHTML = likes;

function addLike() {
    document.getElementById("likebefore").innerHTML = '<button class="likebtn" id="likebtn"><i class="fa-solid fa-thumbs-up"></i><span id="likespan">' + ++likes + '</span></button>';
    document.getElementById("likebtn").style.backgroundColor = "#4267B2";
    document.getElementById("likebtn").style.cursor = "not-allowed";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        document.getElementById("demo").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "/ajax/likebtn.php?id="+<?php echo $id?>, true);
    xhttp.send();
};
</script>
<script>
  $("#navbar-load").load("/lib/navbar.html");
</script>
<script>
  AOS.init();
</script>
</body>
</html>
<?php $link->close();?>