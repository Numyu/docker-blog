<?php
require('../includes/pdo.inc.php');
$id = filter_input(INPUT_GET, "id");
$delete = $db->query("DELETE FROM posts WHERE id = '$id'");
header("Location: ../pages/home.php");