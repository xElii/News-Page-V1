<?php
require("/DATEN/Config/db.php");

$id = $_GET['id'];
if ($id == null) {header('Location: /');}
else {
    $del = "UPDATE news SET likes = likes+1 WHERE id = $id";
    if ($link->query($del) === TRUE) {echo "Like zu $id hinzugefügt.";}
}
?>