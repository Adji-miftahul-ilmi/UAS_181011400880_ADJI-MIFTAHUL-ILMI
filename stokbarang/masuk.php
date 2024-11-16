<?php
require 'function.php';
require 'cek.php';
if (!memiliki_jabatan('admin') && !memiliki_jabatan('manajer') && !memiliki_jabatan('staff gudang')) {
    header('location:access_denied.php');
    exit;
}
setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia.1252');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Barang Masuk</title>
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
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Barang Masuk</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Barang masuk berfungsi untuk mencatat dan mengelola barang yang diterima</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                Tambah Barang Masuk
                            </button>
                            <a href="export_masuk.php" class="btn btn-info">Laporan Data Stok Barang Masuk</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                            <th>supplier</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ambilsemuadatastok = mysqli_query($conn, "SELECT s.idbarang, m.idmasuk, m.tanggal, s.namabarang, m.qty, m.satuan, m.supplier FROM masuk m JOIN stok s ON s.idbarang = m.idbarang");
                                        $i = 1;
                                        while ($data = mysqli_fetch_array($ambilsemuadatastok)) {
                                            $idb = $data["idbarang"];
                                            $idm = $data['idmasuk'];
                                            $tanggal = $data['tanggal'];
                                            $namabarang = $data['namabarang'];
                                            $qty = $data['qty'];
                                            $satuan = $data['satuan'];
                                            $supplier = $data['supplier'];
                                        ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?php echo strftime('%A, %d/%m/%Y, %H:%M', strtotime($tanggal)); ?></td>
                                                <td><?= $namabarang; ?></td>
                                                <td><?= $qty; ?></td>
                                                <td><?= $satuan; ?></td>
                                                <td><?= $supplier; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $idm; ?>">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $idm; ?>">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                            <!-- Edit Modal Masuk-->
                                            <div class="modal fade" id="edit<?= $idm; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Data Barang Masuk</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <select name="barangnya" id="barangnya" class="form-control" required>
                                                                    <option value="" disabled>Pilih Barang</option>
                                                                    <?php
                                                                    $ambilsemuadatanya = mysqli_query($conn, "SELECT * FROM stok");
                                                                    while ($fetcharray = mysqli_fetch_array($ambilsemuadatanya)) {
                                                                        $namabarangnya = $fetcharray['namabarang'];
                                                                        $idbarangnya = $fetcharray['idbarang'];
                                                                        $satuanbarang = $fetcharray['satuan'];
                                                                        $selected = ($idbarangnya == $idb) ? "selected" : "";
                                                                    ?>
                                                                        <option value="<?= $idbarangnya; ?>" data-satuan="<?= $satuanbarang; ?>" <?= $selected; ?>><?= $namabarangnya; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <br>
                                                                <input type="text" name="supplier" value="<?= $supplier; ?>" class="form-control" required>
                                                                <br>
                                                                <input type="number" name="qty" value="<?= $qty; ?>" class="form-control" required>
                                                                <br>
                                                                <input type="text" name="satuan" id="satuan-edit<?= $idm; ?>" value="<?= $satuan; ?>" class="form-control" required readonly>
                                                                <br>
                                                                <input type="hidden" name="idm" value="<?= $idm; ?>">
                                                                <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                            </div>
                                                            <!-- Modal footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary" name="updatebarangmasuk">Submit</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    // Event listener untuk memilih barang dan mengisi satuan secara otomatis
                                                    document.getElementById('barangnya').addEventListener('change', function(e) {
                                                        const selectedOption = e.target.options[e.target.selectedIndex];
                                                        const satuan = selectedOption.getAttribute('data-satuan');

                                                        // Mengisi input satuan dengan nilai yang sesuai
                                                        document.getElementById('satuan-edit<?= $idm; ?>').value = satuan;
                                                    });
                                                });
                                            </script>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete<?= $idm; ?>">
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
                                                                <input type="hidden" name="kty" value="<?= $qty; ?>">
                                                                <input type="hidden" name="idm" value="<?= $idm; ?>">
                                                            </div>
                                                            <!-- Modal footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-danger" name="hapusbarangmasuk">Submit</button>
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

<!-- Tambah Barang Masuk -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang Masuk</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form method="post" id="form-tambah-barang">
                <div class="modal-body" id="input-container">
                    <div class="input-group mb-3">
                        <select name="barangnya[]" class="form-control barang-select" required>
                            <option value="">Pilih Barang</option>
                            <?php
                            // Membuat string opsi barang yang siap digunakan di JavaScript
                            $options = '';
                            $ambilsemuadatanya = mysqli_query($conn, "SELECT * FROM stok");
                            while ($fetcharray = mysqli_fetch_array($ambilsemuadatanya)) {
                                $namabarangnya = $fetcharray['namabarang'];
                                $idbarangnya = $fetcharray['idbarang'];
                                $satuanbarang = $fetcharray['satuan'];
                                $options .= "<option value='$idbarangnya' data-satuan='$satuanbarang'>$namabarangnya</option>";
                            }
                            echo $options; // Tampilkan opsi pertama kali
                            ?>
                        </select>
                        <input type="number" name="qty[]" class="form-control" placeholder="Jumlah" required>
                        <select name="satuan[]" class="form-control satuan-select" required>
                            <option value="">Pilih Satuan</option>
                            <option value="pcs">Pcs</option>
                            <option value="kg">Kg</option>
                            <option value="meter">Meter</option>
                            <option value="batang">batang</option>
                            <option value="set">set</option>
                        </select>
                        <input type="text" name="supplier[]" class="form-control" placeholder="Supplier" required>
                        <button type="button" class="btn btn-danger remove-item">Hapus</button>
                    </div>
                </div>
                <button type="button" class="btn btn-success" id="add-more">Tambah Barang Lain</button>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="addbarangmasuk">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputContainer = document.getElementById('input-container');

        // Mengisi otomatis satuan ketika barang dipilih
        inputContainer.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('barang-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const satuan = selectedOption.getAttribute('data-satuan');
                const satuanSelect = e.target.closest('.input-group').querySelector('.satuan-select');
                if (satuanSelect && satuan) {
                    satuanSelect.value = satuan;
                }
            }
        });

        // Fungsi untuk menghapus input-group
        inputContainer.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-item')) {
                e.target.closest('.input-group').remove();
            }
        });

        // Tambah barang lain dengan mengkloning elemen input-group
        document.getElementById('add-more').addEventListener('click', function() {
            const newInputGroup = document.createElement('div');
            newInputGroup.classList.add('input-group', 'mb-3');
            newInputGroup.innerHTML = `
                <select name="barangnya[]" class="form-control barang-select" required>
                    <option value="">Pilih Barang</option>
                    <?php echo $options; // Gunakan opsi yang sama ?>
                </select>
                <input type="number" name="qty[]" class="form-control" placeholder="Jumlah" required>
                <select name="satuan[]" class="form-control satuan-select" required>
                    <option value="">Pilih Satuan</option>
                    <option value="pcs">Pcs</option>
                    <option value="kg">Kg</option>
                    <option value="meter">Meter</option>
                    <option value="batang">batang</option>
                    <option value="set">set</option>
                </select>
                <input type="text" name="supplier[]" class="form-control" placeholder="Supplier" required>
                <button type="button" class="btn btn-danger remove-item">Hapus</button>
            `;
            inputContainer.appendChild(newInputGroup);
        });
    });
</script>



</html>