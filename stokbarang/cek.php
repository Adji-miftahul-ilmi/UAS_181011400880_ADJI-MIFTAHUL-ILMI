<?php
// Mulai sesi jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}
// Jika belum login, arahkan ke halaman login
if(!isset($_SESSION['log'])){
    header('location:login.php');
    exit; // Hentikan eksekusi skrip setelah redirect
}
?>