<?php
require 'function.php';
            require 'cek.php';
if (!memiliki_jabatan('admin')) {
    header('location:access_denied.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Kelola Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>

            body {
                background-color: #f8f9fa;
            }
            .sb-topnav {
                background-color: #343a40;
            }
            .sb-sidenav {
                background-color: #343a40;
            }
            .sb-sidenav-menu {
                color: #ffffff;
            }
            .sb-sidenav-menu .nav-link {
                color: #ffffff;
            }
            .sb-sidenav-menu .nav-link:hover {
                background-color: #495057;
            }
            .breadcrumb {
                background-color: #ffffff;
                border: none;
                border-radius: 0;
            }
            .breadcrumb .breadcrumb-item.active {
                color: #000000;
            }
            .card {
                background-color: #ffffff;
                border: none;
                border-radius: 0;
            }
            .card-header {
                background-color: #007bff;
                color: #ffffff;
                border-bottom: none;
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
            <body class="sb-nav-fixed">
                <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
                    <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="mx-auto">
                        <a class="navbar-brand" href="index.php" style="font-size: 18px; font-weight: bold; text-align: center;">
                            PT Nusantara Ekahandal Electrostatic
                        </a>
                    </div>
                    <ul class="navbar-nav ml-auto">
                        <?php
                        // Pastikan sesi sudah dimulai dan pengguna sudah login
                        if (isset($_SESSION['log'])) {
                            $email = $_SESSION['email'];
                            $jabatan = $_SESSION['jabatan'];
                            echo "<li class='nav-item'><span class='nav-link'>$email ($jabatan)</span></li>";
                        }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="logout.php" onclick="return confirmLogout();">Logout</a>
                        </li>
                    </ul>
                </nav>
                <!-- JavaScript untuk notifikasi konfirmasi saat logout -->
                <script>
                function confirmLogout() {
                    return confirm('Apakah Anda yakin ingin keluar?');
                    }
                    </script>
                    
                    <style>
                    .navbar-brand {
                        font-size: 18px;
                        font-weight: bold;
                        text-align: center;
                    }
                    .nav-link.text-danger {
                        color: red !important;
                        font-weight: bold;
                    }
                    .nav-link.text-danger:hover {
                        color: darkred !important;
                        }
                        </style>
                        
                        <div id="layoutSidenav">
                            <div id="layoutSidenav_nav">
                                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                                    <div class="sb-sidenav-menu">
                                        <div class="nav">
                                            <a class="nav-link" href="index.php">
                                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                                Dashboard Stok Barang
                                            </a>
                                            <a class="nav-link" href="masuk.php">
                                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                                Barang Masuk
                                            </a>
                                            <a class="nav-link" href="keluar.php">
                                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                                Barang Keluar
                                            </a>
                                            <a class="nav-link" href="admin.php">
                                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                                Kelola Pengguna
                                            </a>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                            <div id="layoutSidenav_content">
                                <main>
                                    <div class="container-fluid">
                                        <h1 class="mt-4">Kelola Pengguna</h1>
                                        <ol class="breadcrumb mb-4">
                                            <li class="breadcrumb-item active">kelola Pengguna berfungsi untuk mengelola pengguna</li>
                                        </ol>
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                                    Tambah Pengguna
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Username</th>
                                                                <th>Password</th>
                                                                <th>Jabatan</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $ambilsemuadataadmin = mysqli_query($conn, "SELECT * FROM login");
                                                            $i = 1; // Inisialisasi variabel $i
                                                            while ($data = mysqli_fetch_array($ambilsemuadataadmin)) {
                                                                $em = $data['email'];
                                                                $iduser= $data['iduser'];
                                                                $pw = "********"; // Password disamarkan
                                                                $jabatan = $data['jabatan'];
                                                                ?>
                                                                <tr>
                                                                    <td><?=$i++;?></td>
                                                                    <td><?=$em;?></td>
                                                                    <td>
                                                                        <input type="password" id="password<?=$iduser;?>" value="<?=$data['password'];?>" class="form-control" placeholder="Password" readonly>
                                                                        <input type="checkbox" onclick="togglePasswordVisibility('password<?=$iduser;?>')"> Lihat Password
                                                                    </td> <!-- Tampilkan password yang disamarkan -->
                                                                    <td><?=$jabatan;?></td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?=$iduser;?>">
                                                                            <i class="fas fa-edit"></i> Edit
                                                                        </button>
                                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$iduser?>">
                                                                            <i class="fas fa-trash-alt"></i> Hapus
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <!-- Edit Modal -->
                                                                <div class="modal fade" id="edit<?=$iduser;?>">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <!-- Modal Header -->
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Edit Data Pengguna</h4>
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            </div>
                                                                            <!-- Modal body -->
                                                                            <form method="post">
                                                                                <div class="modal-body">
                                                                                    <input type="email" name="emailadmin" value="<?=$em;?>" class="form-control" required placeholder="Email Baru">
                                                                                    <br>
                                                                                    <input type="password" name="passwordbaru" class="form-control" placeholder="Password Baru (kosongkan jika tidak ingin mengubah)">
                                                                                    <br>
                                                                                    <select name="jabatan" id="jabatan" class="form-control" required>
                                                                                        <option value="" disabled>Pilih Jabatan</option>
                                                                                        <option value="admin" <?php if ($jabatan == "admin") echo "selected"; ?>>Admin</option>
                                                                                        <option value="staf gudang" <?php if ($jabatan == "staf gudang") echo "selected"; ?>>Staf Gudang</option>
                                                                                        <option value="manajer" <?php if ($jabatan == "manajer") echo "selected"; ?>>Manajer</option>
                                                                                    </select>
                                                                                    <!-- Hidden input untuk iduser -->
                                                                                    <input type="hidden" name="id" value="<?= $iduser; ?>">
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="submit" class="btn btn-primary" name="updateadmin">Submit</button>
                                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Delete Modal -->
                                                                    <div class="modal fade" id="delete<?=$iduser;?>">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                    <!-- Modal Header -->
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Hapus Pengguna</h4>
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                    <!-- Modal body -->
                                                                    <form method="post">
                                                                        <div class="modal-body">
                                                                            <p>Apakah Anda yakin ingin menghapus <?=$em;?>?</p>
                                                                            <input type="hidden" name="id" value="<?=$iduser;?>">
                                                                        </div>
                                                                    <!-- Modal footer -->
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-danger" name="hapusadmin">Submit</button>
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/datatables-demo.js"></script>
</body>

<!-- Tambah Pengguna-->
<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Pengguna</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form method="post" id="form-tambah-admin">
                <div class="modal-body" id="admin-input-container">
                    <div class="input-group mb-3">
                        <input type="email" name="email[]" placeholder="Email" class="form-control" required>
                        <input type="password" name="password[]" placeholder="Password" class="form-control" required>
                        <select name="jabatan[]" class="form-control" required>
                            <option value="" disabled selected>Pilih Jabatan</option>
                            <option value="admin">Admin</option>
                            <option value="staf gudang">Staf Gudang</option>
                            <option value="manajer">Manajer</option>
                        </select>
                        <button type="button" class="btn btn-danger remove-admin">Hapus</button>
                    </div>
                </div>
                <button type="button" class="btn btn-success" id="add-more-admin">Tambah Admin Lain</button>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="addadmin">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event listener untuk tombol tambah admin
        document.getElementById('add-more-admin').addEventListener('click', function() {
            const container = document.getElementById('admin-input-container');
            const newInputGroup = document.createElement('div');
            newInputGroup.classList.add('input-group', 'mb-3');
            newInputGroup.innerHTML = `
                <input type="email" name="email[]" placeholder="Email" class="form-control" required>
                <input type="password" name="password[]" placeholder="Password" class="form-control" required>
                <select name="jabatan[]" class="form-control" required>
                    <option value="" disabled selected>Pilih Jabatan</option>
                    <option value="admin">Admin</option>
                    <option value="staf gudang">Staf Gudang</option>
                    <option value="manajer">Manajer</option>
                </select>
                <button type="button" class="btn btn-danger remove-admin">Hapus</button>
            `;
            container.appendChild(newInputGroup);
        });

        document.getElementById('admin-input-container').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-admin')) {
                e.target.closest('.input-group').remove();
            }
        });
    });
</script>

<script>
    function togglePasswordVisibility(passwordFieldId) {
        var passwordField = document.getElementById(passwordFieldId);
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>

</html>