<?php
require_once 'baglan.php';
session_destroy();
header("Location: index.php");
exit;
?>
