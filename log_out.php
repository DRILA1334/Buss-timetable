<!-- filepath: c:\xampp\htdocs\drila-02\BusTimeTable-DBMS-main\logout.php -->
<?php
session_start();
session_unset();
session_destroy();
header("location: index.php");
exit();
?>