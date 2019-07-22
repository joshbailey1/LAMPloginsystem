<?php
session_start();
session_destroy();
//redirect to login
header('Location: index.html');
?>
