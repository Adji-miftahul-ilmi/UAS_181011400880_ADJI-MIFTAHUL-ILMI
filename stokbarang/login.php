<?php
require 'function.php';

// Cek apakah sesi sudah aktif sebelum memanggil session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah ada pesan dari logout
if (isset($_GET['message']) && $_GET['message'] == 'logout_success') {
    echo "<div class='alert alert-success'>Anda telah berhasil logout.</div>";
}

$login_error = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cocokkan dengan database
    $cekdatabase = mysqli_query($conn, "SELECT * FROM login WHERE email='$email' AND password='$password'");
    $hitung = mysqli_num_rows($cekdatabase);

    if ($hitung > 0) {
        // Ambil data pengguna dari hasil query
        $data = mysqli_fetch_assoc($cekdatabase);

        $_SESSION['log'] = 'True';
        $_SESSION['email'] = $data['email'];
        $_SESSION['jabatan'] = $data['jabatan'];

        header('location:index.php?login=success');
    } else {
        $login_error = 'Email atau password salah. Silakan coba lagi.';
    }
}

if (isset($_GET['login']) && $_GET['login'] == 'success') {
    echo "<div class='alert alert-success'>Login berhasil! Selamat datang.</div>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,.5)), url('https://images.unsplash.com/photo-1557683316-973673baf926');
            background-size: cover;
            height: 100vh;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border-bottom: none;
        }

        .card-body {
            padding: 2rem;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.5);
        }
    </style>
</head>
<body>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Login</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Notifikasi Error Login -->
                                    <?php if (!empty($login_error)): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $login_error; ?>
                                        </div>
                                    <?php endif;
                                    ?>
                                    
                                    <form method="post">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputEmailAddress">Email</label>
                                            <input class="form-control py-4" name="email" id="inputEmailAddress" type="email" placeholder="Enter email address" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPassword">Password</label>
                                            <input class="form-control py-4" name="password" id="inputPassword" type="password" placeholder="Enter password" required>
                                        </div>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button class="btn btn-primary" name="login">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>