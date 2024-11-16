<?php
session_start();
session_destroy(); // Hapus semua sesi

header('location:login.php?message=logout_success');
exit();
?>