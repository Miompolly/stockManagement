<?php
session_start();
if (isset($_SESSION['user_id'])) {
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
}
header('Location: login.php');
exit();
?>
