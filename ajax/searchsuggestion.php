<?php
require_once("/DATEN/Config/db.php");
$suche = $_GET['s'];

if ($suche == null) {}
else {
    $result = $link->query("SELECT id,title,time,views FROM news WHERE title LIKE '%$suche%' AND public='1' ORDER BY id DESC LIMIT 8");
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $datum = date_create($row["time"])->format("d. M Y");
            echo('<div><i class="fa-solid fa-newspaper"></i><a href="/beitrag?id='. $row["id"].'">'. $row["title"].'<span> vom '.$datum.'</span></a></div>');
            }
        }
}
?>