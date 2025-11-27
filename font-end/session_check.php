<!-- session_check.php -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo json_encode(['loggedIn' => isset($_SESSION['user_id'])]);
?>
