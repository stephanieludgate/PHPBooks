<?php

require "inc/function.inc.php"; // configuration file
logMsg("users", "info", "User has logged out", $_SESSION['name']);

// start session to have access to SESSION superglobal
session_start();

// remove all existing session data
session_destroy();
session_unset();

// redirect
header("Location: login.php");

?>