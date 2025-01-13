<?php

require 'modules/database.php';
require 'modules/functions.php';
session_start();

var_dump($_SESSION);
if (!isManager()) {
    header("location:index.php");
}
?>


<!doctype html>
<html lang="en">
<head>
    <title>Security</title>
</head>
<body>
<h4>Welkom Manager</h4>
<a href="logout.php">uitloggen</a>
</body>
</html>

