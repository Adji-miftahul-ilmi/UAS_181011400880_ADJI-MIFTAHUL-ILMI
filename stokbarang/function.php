<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Membuat akses jabatan
function memiliki_jabatan($jabatan_diperlukan)
{
    if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == $jabatan_diperlukan) {
        return true;
    } else {
        return false;
    }
}

// Membuat koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "stokbarang");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Menambah barang baru
if (isset($_POST['addnewbarang'])) {
    $kodebarangArr = $_POST['kodebarang'];
    $namabarangArr = $_POST['namabarang'];
    $deskripsiArr = $_POST['deskripsi'];
    $stokArr = $_POST['stok'];
    $satuanArr = $_POST['satuan'];

    $error = false;

    for ($i = 0; $i < count($namabarangArr); $i++) {
        $kodebarang = mysqli_real_escape_string($conn, $kodebarangArr[$i]);
        $namabarang = mysqli_real_escape_string($conn, $namabarangArr[$i]);
        $deskripsi = mysqli_real_escape_string($conn, $deskripsiArr[$i]);
        $stok = intval($stokArr[$i]);
        $satuan = mysqli_real_escape_string($conn, $satuanArr[$i]);

        $addtotable = mysqli_query($conn, "INSERT INTO stok (kodebarang, namabarang, deskripsi, stok, satuan) 
                                        VALUES ('$kodebarang', '$namabarang', '$deskripsi', '$stok', '$satuan')");

        if (!$addtotable) {
            $error = true;
            break;
        }
    }

    // Cek jika terjadi kesalahan dalam proses menambah barang
    if ($error) {
        echo "<script>
                alert('Gagal menambah beberapa barang!');
                window.location.href='index.php';
            </script>";
    } else {
        echo "<script>
                alert('Berhasil menambah barang!');
                window.location.href='index.php';
            </script>";
    }
}

// Menambah barang masuk
if (isset($_POST['addbarangmasuk'])) {
    $barangnyaArr = $_POST['barangnya'];
    $qtyArr = $_POST['qty'];
    $satuanArr = $_POST['satuan'];
    $supplierArr = $_POST['supplier'];
    $error = false;

    // Melakukan loop untuk setiap barang yang ditambahkan
    for ($i = 0; $i < count($barangnyaArr); $i++) {
        $barangnya = mysqli_real_escape_string($conn, $barangnyaArr[$i]);
        $qty = intval($qtyArr[$i]);
        $satuan = mysqli_real_escape_string($conn, $satuanArr[$i]);
        $supplier = mysqli_real_escape_string($conn, $supplierArr[$i]);

        // Query untuk menambahkan data barang masuk
        $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, qty, satuan, supplier, tanggal) 
                                        VALUES ('$barangnya', '$qty', '$satuan', '$supplier', NOW())");
        if ($addtomasuk) {
            // Perbarui stok barang
            $updatestokmasuk = mysqli_query($conn, "UPDATE stok SET stok = stok + $qty WHERE idbarang = '$barangnya'");
            if (!$updatestokmasuk) {
                $error = true;
                break;
            }
        } else {
            $error = true;
            break;
        }
    }

    if ($error) {
        echo "<script>alert('Gagal menambahkan barang masuk.');</script>";
    } else {
        echo "<script>alert('Barang masuk berhasil ditambahkan.');</script>";
    }
}

// Menambah barang keluar
if (isset($_POST['addbarangkeluar'])) {
    $barangnyaArr = $_POST['barangnya'];
    $penerimaArr = $_POST['penerima'];
    $qtyArr = $_POST['qty'];
    $satuanArr = $_POST['satuan'];
    $error = false;
    $errorMessage = "";

    for ($i = 0; $i < count($barangnyaArr); $i++) {
        $barangnya = mysqli_real_escape_string($conn, $barangnyaArr[$i]);
        $penerima = mysqli_real_escape_string($conn, $penerimaArr[$i]);
        $qty = intval($qtyArr[$i]);
        $satuan = mysqli_real_escape_string($conn, $satuanArr[$i]);

        // Periksa apakah stok cukup
        $cekstoksekarang = mysqli_query($conn, "SELECT namabarang, stok FROM stok WHERE idbarang = '$barangnya'");
        $ambildatanya = mysqli_fetch_array($cekstoksekarang);
        $stoksekarang = $ambildatanya['stok'];
        $namabarangnya = $ambildatanya['namabarang'];

        if ($stoksekarang >= $qty) {
            // Update query untuk menyertakan satuan
            $addtokeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, qty, satuan) VALUES ('$barangnya', '$penerima', $qty, '$satuan')");
            if ($addtokeluar) {
                // Perbarui stok barang
                $updatestokkeluar = mysqli_query($conn, "UPDATE stok SET stok = stok - $qty WHERE idbarang = '$barangnya'");
                if (!$updatestokkeluar) {
                    $error = true;
                    $errorMessage = "Gagal memperbarui stok untuk barang $namabarangnya.";
                    break;
                }
            } else {
                $error = true;
                $errorMessage = "Gagal menambahkan barang keluar untuk $namabarangnya.";
                break;
            }
        } else {
            $error = true;
            $errorMessage = "Stok untuk $namabarangnya tidak mencukupi. Hanya tersedia $stoksekarang.";
            break;
        }
    }

    if ($error) {
        echo "<script>alert('$errorMessage');</script>";
    } else {
        echo "<script>alert('Barang berhasil keluar.');</script>";
    }
}


// Edit Barang
if (isset($_POST['update_namabarang'])) {
    $idb = $_POST['idb'];
    $kodebarang = $_POST['kodebarang'];
    $newName = $_POST['namabarang'];
    $newDescription = $_POST['deskripsi'];
    $satuan = $_POST['satuan'];

    // Siapkan query SQL untuk mencegah SQL injection
    $stmt = $conn->prepare("UPDATE stok SET kodebarang = ?, namabarang = ?, deskripsi = ?, satuan = ? WHERE idbarang = ?");
    $stmt->bind_param("ssssi", $kodebarang, $newName, $newDescription, $satuan, $idb);

    // Eksekusi query dan cek apakah berhasil
    if ($stmt->execute()) {
        echo "<script>
                alert('Berhasil diubah!');
                window.location.href='index.php';
            </script>";
    } else {
        echo "<script>
                alert('Gagal mengubah!');
                window.location.href='index.php';
            </script>";
    }

    // Tutup statement
    $stmt->close();
}


//Hapus Barang
if (isset($_POST['hapusbarang'])) {
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "DELETE FROM stok WHERE idbarang='$idb'");

    if ($hapus) {
        echo "<script>
                alert('Berhasil dihapus!');
                window.location.href='index.php';
            </script>";
    } else {
        echo "<script>
                alert('Gagal Menghapus!');
                window.location.href='index.php';
            </script>";
    }
}

// Mengubah data barang masuk
if (isset($_POST['updatebarangmasuk'])) {
    $idm = $_POST['idm'];
    $idbarang = $_POST['barangnya'];
    $qty = $_POST['qty'];
    $supplier = $_POST['supplier'];
    $satuan = $_POST['satuan'];

    // Ambil qty lama dari tabel masuk
    $ambilStokMasuk = mysqli_query($conn, "SELECT qty FROM masuk WHERE idmasuk = '$idm'");
    $stokMasuk = mysqli_fetch_array($ambilStokMasuk)['qty'];

    // Ambil stok sekarang dari tabel stok
    $ambilStokBarang = mysqli_query($conn, "SELECT stok FROM stok WHERE idbarang = '$idbarang'");
    $stokSekarang = mysqli_fetch_array($ambilStokBarang)['stok'];

    // Hitung selisih jumlah baru dengan jumlah lama
    $selisihQty = $qty - $stokMasuk;

    // Update data barang masuk
    $updateQuery = "UPDATE masuk SET idbarang = '$idbarang', qty = '$qty', supplier = '$supplier', satuan = '$satuan' WHERE idmasuk = '$idm'";
    $updateResult = mysqli_query($conn, $updateQuery);

    // Sinkronisasi stok berdasarkan selisih jumlah baru dengan jumlah lama
    if ($selisihQty > 0) {
        // Jika jumlah baru lebih besar dari jumlah lama, tambahkan stok
        mysqli_query($conn, "UPDATE stok SET stok = stok + $selisihQty WHERE idbarang = '$idbarang'");
    } elseif ($selisihQty < 0) {
        // Jika jumlah baru lebih kecil dari jumlah lama, kurangi stok
        mysqli_query($conn, "UPDATE stok SET stok = stok - " . abs($selisihQty) . " WHERE idbarang = '$idbarang'");
    }

    // Redirect kembali ke halaman masuk.php
    if ($updateResult) {
        echo "<script>
                alert('Data barang masuk berhasil diubah!');
                window.location.href='masuk.php';
            </script>";
    } else {
        echo "<script>
                alert('Gagal mengubah data barang masuk!');
                window.location.href='masuk.php';
            </script>";
    }
}


// Menghapus barang masuk 
if (isset($_POST["hapusbarangmasuk"])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST["idm"];

    // Mengambil data stok barang yang akan dihapus
    $getdatastok = mysqli_query($conn, "SELECT * FROM stok WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];

    // Menghitung stok baru setelah barang dihapus
    $stokBaru = $stok - $qty;

    // Update stok di tabel stok
    $update = mysqli_query($conn, "UPDATE stok SET stok = '$stokBaru' WHERE idbarang='$idb'");

    // Hapus data barang masuk dari tabel masuk
    $hapusdata = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idm'");

    // Jika kedua operasi berhasil
    if ($update && $hapusdata) {
        // Redirect ke halaman masuk.php
        header("Location: masuk.php");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>
        alert('Gagal menghapus data barang!');
        window.location.href='masuk.php';
        </script>";
    }
}

// Mengubah data barang keluar
if (isset($_POST['updatebarangkeluar'])) {
    $idk = $_POST['idk'];
    $idbarang = $_POST['barangnya'];
    $qty = $_POST['qty'];
    $penerima = $_POST['penerima'];
    $satuan = $_POST['satuan'];

    // Ambil data qty lama dari tabel keluar
    $ambilStokKeluar = mysqli_query($conn, "SELECT qty FROM keluar WHERE idkeluar = '$idk'");
    $stokKeluar = mysqli_fetch_array($ambilStokKeluar)['qty'];

    // Ambil stok sekarang dari tabel stok
    $ambilStokBarang = mysqli_query($conn, "SELECT stok FROM stok WHERE idbarang = '$idbarang'");
    $stokSekarang = mysqli_fetch_array($ambilStokBarang)['stok'];

    // Validasi agar barang keluar tidak melebihi stok saat ini
    if ($qty > $stokSekarang) {
        echo "<script>
                alert('Gagal, jumlah barang keluar melebihi stok saat ini!');
                window.location.href='keluar.php';
            </script>";
        exit();
    }

    // Hitung selisih jumlah baru dengan jumlah lama
    $selisihQty = $qty - $stokKeluar;

    // Update data barang keluar
    $updateQuery = "UPDATE keluar SET idbarang = '$idbarang', qty = '$qty', penerima = '$penerima', satuan = '$satuan' WHERE idkeluar = '$idk'";
    $updateResult = mysqli_query($conn, $updateQuery) or die(mysqli_error($conn));

    // Sinkronisasi stok berdasarkan selisih jumlah baru dengan jumlah lama
    if ($selisihQty > 0) {
        // Jika jumlah baru lebih besar dari jumlah lama, stok berkurang
        mysqli_query($conn, "UPDATE stok SET stok = stok - $selisihQty WHERE idbarang = '$idbarang'") or die(mysqli_error($conn));
    } elseif ($selisihQty < 0) {
        // Jika jumlah baru lebih kecil dari jumlah lama, stok bertambah
        mysqli_query($conn, "UPDATE stok SET stok = stok + " . abs($selisihQty) . " WHERE idbarang = '$idbarang'") or die(mysqli_error($conn));
    }

    // Redirect kembali ke halaman keluar.php
    if ($updateResult) {
        echo "<script>
                alert('Data barang keluar berhasil diubah!');
                window.location.href='keluar.php';
            </script>";
    } else {
        echo "<script>
                alert('Gagal mengubah data barang keluar!');
                window.location.href='keluar.php';
            </script>";
    }
}


//Menghapus barang keluar
if (isset($_POST["hapusbarangkeluar"])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    // Mengambil data stok barang yang akan dihapus
    $getdatastok = mysqli_query($conn, "SELECT * FROM stok WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];

    // Menghitung stok baru setelah barang dihapus
    $stokBaru = $stok + $qty; // Mengurangkan jumlah barang yang dihapus dari stok yang ada

    // Update stok di tabel stok
    $update = mysqli_query($conn, "UPDATE stok SET stok = '$stokBaru' WHERE idbarang='$idb'");

    // Hapus data barang keluar dari tabel keluar
    $hapusdata = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idk'");

    // Jika kedua operasi berhasil
    if ($update && $hapusdata) {
        // Redirect ke halaman keluar.php
        header("Location: keluar.php");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>
        alert('Gagal menghapus data barang!');
        window.location.href='keluar.php';
        </script>";
    }
}

// Menambah pengguna baru
if (isset($_POST['addadmin'])) {
    // Loop untuk menambah setiap admin
    foreach ($_POST['email'] as $index => $email) {
        // Sanitasi input untuk mencegah SQL injection
        $email = mysqli_real_escape_string($conn, $email);
        $password = $_POST['password'][$index];
        $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan'][$index]);

        // Query untuk menambah admin baru
        $query = "INSERT INTO login (email, password, jabatan) VALUES ('$email', '$password', '$jabatan')";

        // Eksekusi query
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Admin berhasil ditambahkan');</script>";
        } else {
            echo "<script>alert('Gagal menambahkan admin: " . mysqli_error($conn) . "');</script>";
        }
    }
}


// Edit data admin
if (isset($_POST['updateadmin'])) {
    $iduser = $_POST['id'];
    $email = $_POST['emailadmin'];
    $jabatan = $_POST['jabatan'];
    $passwordbaru = $_POST['passwordbaru'];

    // Cek jika password baru diisi, maka update password
    if (!empty($passwordbaru)) {
        $query = "UPDATE login SET email = '$email', jabatan = '$jabatan', password = '$passwordbaru' WHERE iduser = $iduser";
    } else {
        // Jika password tidak diubah
        $query = "UPDATE login SET email = '$email', jabatan = '$jabatan' WHERE iduser = $iduser";
    }

    // Eksekusi query update
    if (mysqli_query($conn, $query)) {
        echo "Data berhasil diperbarui!";
    } else {
        echo "Terjadi kesalahan saat memperbarui data.";
    }
}


//hapus admin
if (isset($_POST['hapusadmin'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // Query untuk menghapus admin
    $query = "DELETE FROM login WHERE iduser='$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Admin berhasil dihapus');</script>";
    } else {
        echo "<script>alert('Gagal menghapus admin: " . mysqli_error($conn) . "');</script>";
    }
}

//get satuan
if (isset($_POST['namabarang'])) {
    $namabarang = $_POST['namabarang'];
    $result = mysqli_query($conn, "SELECT satuan FROM stok WHERE namabarang = '$namabarang' LIMIT 1");
    $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
}
