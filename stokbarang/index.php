<?php
session_start();
require 'function.php';
require 'cek.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Stok Barang</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous">
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
                    <h1 class="mt-4">Dashboard Stok Barang</h1>
                    <ol class="breadcrumb mb=4">
                        <li class="breadcrumb-item active">Dashboard stok barang berfungsi untuk memantau, mengelola, dan mengoptimalkan persediaan barang</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                Tambah Barang
                            </button>
                            <a href="export_index.php" class="btn btn-info">Laporan Data Stok Barang</a>
                        </div>
                        <div class="card-body">
                            <?php
                            $ambildatastok = mysqli_query($conn, "SELECT * FROM stok WHERE stok < 1");
                            while ($fetch = mysqli_fetch_array($ambildatastok)) {
                                $barang = $fetch['namabarang'];
                            ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Perhatian!</strong> Stok <?= $barang; ?> Telah Habis.
                                </div>
                            <?php
                            }
                            ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Deskripsi</th>
                                            <th>Stok</th>
                                            <th>Satuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ambilsemuadatastok = mysqli_query($conn, "SELECT * FROM stok");
                                        $i = 1;
                                        while ($data = mysqli_fetch_array($ambilsemuadatastok)) {
                                            $kodebarang = $data['kodebarang'];
                                            $namabarang = $data['namabarang'];
                                            $deskripsi = $data['deskripsi'];
                                            $stok = $data['stok'];
                                            $satuan = $data['satuan'];
                                            $idb = $data['idbarang'];
                                        ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= $kodebarang; ?></td>
                                                <td><?= $namabarang; ?></td>
                                                <td><?= $deskripsi; ?></td>
                                                <td><?= $stok; ?></td>
                                                <td><?= $satuan; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $idb; ?>">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $idb ?>">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="edit<?= $idb; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Data Barang</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <!-- Modal Body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <input type="text" name="kodebarang" value="<?= $kodebarang; ?>" class="form-control" required>
                                                                <br>
                                                                <input type="text" name="namabarang" value="<?= $namabarang; ?>" class="form-control" required>
                                                                <br>
                                                                <input type="text" name="deskripsi" value="<?= $deskripsi; ?>" class="form-control" required>
                                                                <br>
                                                                <select name="satuan" class="form-control satuan-select" required>
                                                                    <option value="">Pilih Satuan</option>
                                                                    <option value="pcs" <?php if ($satuan == "pcs") echo "selected"; ?>>Pcs</option>
                                                                    <option value="kg" <?php if ($satuan == "kg") echo "selected"; ?>>Kg</option>
                                                                    <option value="meter" <?php if ($satuan == "meter") echo "selected"; ?>>Meter</option>
                                                                    <!-- Tambahkan satuan lain sesuai kebutuhan -->
                                                                </select>
                                                                <br>
                                                                <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                            </div>

                                                            <!-- Modal Footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary" name="update_namabarang">Submit</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete<?= $idb; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Barang</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <p>Apakah Anda yakin ingin menghapus <?= $namabarang; ?>?</p>
                                                                <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                            </div>

                                                            <!-- Modal footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-danger" name="hapusbarang">Submit</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

    <!-- Tambah Barang -->
    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <form method="post" id="form-tambah-barang">
                    <div class="modal-body" id="input-container">
                        <div class="input-group mb-3">
                            <input type="text" name="kodebarang[]" placeholder="Kode Barang" class="form-control" required>
                            <input type="text" name="namabarang[]" placeholder="Nama Barang" class="form-control" required>
                            <input type="text" name="deskripsi[]" placeholder="Deskripsi Barang" class="form-control" required>
                            <input type="number" name="stok[]" class="form-control" placeholder="Stok" required>
                            <select name="satuan[]" class="form-control" required>
                                <option value="">Pilih Satuan</option>
                                <option value="pcs">Pcs</option>
                                <option value="kg">Kg</option>
                                <option value="meter">Meter</option>
                                <option value="batang">batang</option>
                                <option value="set">set</option>
                                <!-- Tambahkan satuan lain sesuai kebutuhan -->
                            </select>
                            <button type="button" class="btn btn-danger remove-item">Hapus</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" id="add-more">Tambah Barang Lain</button>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="addnewbarang">Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-more').addEventListener('click', function() {
            var container = document.getElementById('input-container');
            var newInputGroup = document.createElement('div');
            newInputGroup.classList.add('input-group', 'mb-3');
            newInputGroup.innerHTML = `
            <input type="text" name="kodebarang[]" placeholder="Kode Barang" class="form-control" required>
            <input type="text" name="namabarang[]" placeholder="Nama Barang" class="form-control" required>
            <input type="text" name="deskripsi[]" placeholder="Deskripsi Barang" class="form-control" required>
            <input type="number" name="stok[]" class="form-control" placeholder="Stok" required>
            <select name="satuan[]" class="form-control" required>
                <option value="">Pilih Satuan</option>
                <option value="pcs">Pcs</option>
                <option value="kg">Kg</option>
                <option value="meter">Meter</option>
                <option value="batang">batang</option>
                <option value="set">set</option>
                <!-- Tambahkan satuan lain sesuai kebutuhan -->
            </select>
            <button type="button" class="btn btn-danger remove-item">Hapus</button>
        `;
            container.appendChild(newInputGroup);
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-item')) {
                var item = e.target.closest('.input-group');
                item.remove();
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "searching": true
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('change', 'input[name="namabarang[]"]', function() {
                var namabarang = $(this).val();
                $.ajax({
                    url: 'get_satuan.php',
                    type: 'POST',
                    data: {
                        namabarang: namabarang
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        $(this).closest('.input-group').find('input[name="satuan[]"]').val(data.satuan);
                    }.bind(this)
                });
            });
        });
    </script>

</body>

</html>