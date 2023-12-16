<?php require_once("/DATEN/Config/db.php");

$results_per_page = 10;
if (!isset ($_GET['p'])) {$page=1;} elseif($_GET['p']==0) {header("Location: ?p=1");} else {$page=$_GET['p'];}
//if ($_GET["p"]==null) {$pageid=1;} else {$pageid=$_GET["p"];}
$pageid=$_GET["p"] ?? null;

$result = mysqli_query($link, "select * from news");
$number_of_result = mysqli_num_rows($result);
$number_of_page = ceil($number_of_result / $results_per_page);
$page_first_result = ($page-1) * $results_per_page;
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>Home | Datalok News</title>
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
    <div class="loading-screen" id="loading-screen"><svg class="ring" viewBox="25 25 50 50" stroke-width="5"><circle cx="50" cy="50" r="20" /></svg></div>
    <div id="navbar-load"></div>
    <div class="container" style="margin-top: 65px;"><br><br>
        <form class="search-group" style="margin-bottom: 20px;" data-aos='fade-down'>
            <div style="display:flex; align-items:center;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input placeholder="Suchen" type="search" class="input" name="s" id="searchbar" value="<?php echo($_GET['s'])?>">
            </div>
            <div id="searchsuggestion"></div>
        </form>
        <div class="posts-wrapper" data-aos='fade-up'>
            <?php
            $sql = "SELECT * FROM news WHERE public=1 ORDER BY id DESC LIMIT ".$page_first_result.','.$results_per_page;
            $result = $link->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                $datum = date_create($row["time"])->format("d. M Y");
                $articles = "<a href='/beitrag/". $row["id"]."'><h2>". $row["title"]. "</h2><time>$datum</time>・<i class='fa-solid fa-eye'></i> ".$row["views"]."<div class='arrow'><i class='fa-solid fa-right-to-bracket'></i></div></a>";
                echo $articles;
            }
            } else {$emptysite='<h2 style="margin-bottom:30px; text-align:center;">Diese Seite ist noch Leer!<br><i>Seite: '.$pageid.'</i></h2>';}
            ?>
        </div>
        <?php echo $emptysite;?>
        <div class="pagination" style="animation: fadeIn .8s;">
            <a href="?p=<?php echo $pageid-1 ?>">&laquo;</a>
            <?php
            for($page = 1; $page<= $number_of_page; $page++) {
                echo '<a id=listitem'.$page.' href="?p='.$page.'">'.$page.'</a>';
            }?>
            <a href="?p=<?php echo $pageid+1 ?>">&raquo;</a>
        </div>
    </div>
<script>
// Current Page Active Styling
var currentUrl = window.location.href;
var url = new URL(currentUrl);
var id = url.searchParams.get("p");
if (id==null) {var id = "1"}
var element = document.getElementById('listitem'+id);
element.classList.add("active");
</script>
<script>
// Search bar suggestion script - SBSS
searchbar.addEventListener("keyup", (event) => {
  if (event.isComposing || event.keyCode === 229) {
    return;
  }
  searchsuggestion()
});

function searchsuggestion() {
    let input = document.getElementById("searchbar")
    let suche = input.value
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        document.getElementById("searchsuggestion").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "../ajax/searchsuggestion.php?s="+suche, true);
    xhttp.send();
};

$(document).keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
});
</script>
<script>$("#navbar-load").load("lib/navbar.html");</script>
<script>AOS.init();</script>
</body>
</html>
<script src="/lib/loading.js"></script>
<?php $link->close();?>