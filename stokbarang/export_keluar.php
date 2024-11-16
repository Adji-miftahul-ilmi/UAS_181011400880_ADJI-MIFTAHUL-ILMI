<?php
require 'function.php';
require 'cek.php';
setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia.1252');
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Data Barang Keluar</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
        <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      margin-top: 50px;
    }
    .table-container {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    h2, h4 {
      color: #343a40;
    }
    .kop-surat img {
      width: 100px;
      margin-bottom: 10px;
    }
    .kop-surat h4 {
      margin: 0;
    }
    .kop-surat p {
      margin: 0;
    }
    .kop-surat hr {
      border: 1px solid black;
      margin-top: 10px;
    }
    .report-header {
      margin-bottom: 20px;
      padding: 10px;
    }
    .report-header h5 {
      margin: 0;
      font-weight: bold;
      color: #343a40;
    }
    .report-header p {
      margin: 0;
    }
    .kop-surat {
      text-align: center;
      margin-bottom: 40px;
    }
    .kop-surat img {
      width: 100px;
      margin-bottom: 10px;
    }
    .kop-surat h4 {
      margin: 0;
    }
    .kop-surat p {
      margin: 0;
    }
    .kop-surat hr {
      border: 1px solid black;
      margin-top: 10px;
    }
  </style>
</head>
<body>
    <div class="container">
        <div class="table-container">
            <div class="kop-surat">
                <table>
                <tr>
                    <td>
                    <img src="data:image/jpeg;base64,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" alt="Logo Perusahaan" style="width: 100px; margin-right: 20px;">
                    </td>
                    <td>
                    <h4>PT Nusantara Ekahandal Electrostatic</h4>
                    <p>Jl. Raya Serang No.KM.25 No.9, Balaraja, Kec. Balaraja, Kabupaten Tangerang,</p>
                    <p>Banten 15610</p>
                    <p>Telepon: (021) 595 1092 | Email: <a href="mailto:info@nekahandal.com">info@nekahandal.com</a></p>
                    </td>
                </tr>
                </table>
                <hr style="border: 1px solid black; margin-top: 20px;">
            </div>
        <div class="report-header">
            <h5>Laporan Data Barang Keluar</h5>
            <p id="tanggal"></p>
            <p id="waktu"></p>
        </div>
        <div class="data-tables datatable-dark">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Penerima / Tujuan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ambilsemuadatastok = mysqli_query($conn, "SELECT k.idkeluar, k.tanggal, s.namabarang, k.qty, k.satuan, k.penerima FROM keluar k JOIN stok s ON s.idbarang = k.idbarang");
                    $i = 1;
                    while ($data = mysqli_fetch_array($ambilsemuadatastok)) {
                        $idk = $data['idkeluar'];
                        $tanggal = $data['tanggal'];
                        $namabarang = $data['namabarang'];
                        $qty = $data['qty'];
                        $satuan = $data['satuan'];
                        $penerima = $data['penerima'];
                        ?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo strftime('%A, %d/%m/%Y, %H:%M', strtotime($tanggal)); ?></td>
                            <td><?php echo htmlspecialchars ($namabarang);?></td>
                            <td><?php echo $qty;?></td>
                            <td><?php echo $satuan;?></td>
                            <td><?php echo htmlspecialchars($penerima);?></td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>					
                </div>
            </div>
<script>
$(document).ready(function() {
    // Set date and time
    var now = new Date();
    var formattedDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    var formattedTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0') + ' WIB';
    
    $('#tanggal').text('Tanggal : ' + formattedDate);
    $('#waktu').text('Waktu : ' + formattedTime);

    var titleText = 'PT Nusantara Ekahandal Electrostatic\n' +
            'Jl. Raya Serang No.KM.25 No.9, Balaraja, Kec. Balaraja, Kabupaten Tangerang,\n' +
            'Banten 15610\n' +
            'Telepon: (021) 595 1092 | Email: info@nekahandal.com\n' +
            '\n' + 
            'Tanggal : ' + formattedDate + '\n' +
            'Waktu : ' + formattedTime;

    $('#dataTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                title: 'Laporan Data Barang Keluar ' + formattedDate,
                messageTop: ''
            },
            {
                extend: 'csv',
                title: 'Laporan Data Barang Keluar ' + formattedDate,
                messageTop: ''
            },
            {
                extend: 'excel',
                title: 'Laporan Data Barang keluar ' + formattedDate,
                messageTop: ''
            },
            {
                extend: 'pdf',
                  title: 'Laporan Data Barang Keluar ' + formattedDate,
                  customize: function (doc) {
                    doc.content.splice(0, 1, {
                      columns: [
                        {
                          image: 'data:data:image/jpeg;base64,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',  // Ganti dengan base64 dari logo Anda
                          width: 40,
                          alignment: 'left'
                        },
                        {
                          text: [
                            { text: 'PT Nusantara Ekahandal Electrostatic\n', fontSize: 12, bold: true },
                            { text: 'Jl. Raya Serang No.KM.25 No.9, Balaraja, Kec. Balaraja\n', fontSize: 10 },
                            { text: 'Kabupaten Tangerang, Banten 15610\n', fontSize: 10 },
                            { text: 'Telepon: (021) 595 1092 | Email: info@nekahandal.com\n', fontSize: 10 }
                          ],
                          alignment: 'left',
                          margin: [10, 0, 0, 0]
                        }
                      ],
                      margin: [0, 0, 0, 10]
                    });
                    
                    doc.content.splice(1, 0, {
                      canvas: [
                        {
                          type: 'line',
                          x1: 0, y1: 0,
                          x2: 515, y2: 0,
                          lineWidth: 1
                        }
                      ],
                      margin: [0, 0, 0, 10]
                    });
                    doc.content.splice(2, 0, {
                      text: [
                        { text: 'Laporan Data Barang Keluar\n', fontSize: 12, bold: true },
                        { text: 'Tanggal: ' + formattedDate + '\n', fontSize: 10 },
                        { text: 'Waktu: ' + formattedTime + '\n\n', fontSize: 10 }
                      ],
                      alignment: 'left',
                      margin: [0, 10, 0, 10]
                    });        
                  }
                },
                {
                  extend: 'print',
                  title: '',
                  messageTop: function () {
                    var logoHtml = '<div style="display: flex; align-items: center;">' +
                    '<img src="data:data:image/jpeg;base64,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"; style="max-width:100px; height:auto; margin-right: 10px;">' +
                    '<div style="font-size: 12px; line-height: 1.5;">' +
                    '<span style="font-weight: bold; font-size: 12px;">PT Nusantara Ekahandal Electrostatic</span><br>' +
                    'Jl. Raya Serang No.KM.25 No.9, Balaraja, Kec. Balaraja, Kabupaten Tangerang,<br>' +
                    'Banten 15610<br>' +
                    'Telepon: (021) 595 1092 | Email: info@nekahandal.com<br>' +
                    '</div>' +
                    '</div>' +
                    '<hr style="border: none; border-top: 1px solid #000; margin: 5px 0;">' +
                    '<div style="font-size: 12px; line-height: 1.5; margin-top: 10px;">' +
                    '<span style="font-weight: bold; font-size: 12px;">Laporan Data Barang Keluar</span><br>' +
                    'Tanggal: ' + formattedDate + '<br>' +
                    'Waktu: ' + formattedTime + 
                    '</div>';
                    return logoHtml;
                  },
                  customize: function (win) {
                    $(win.document.body).css({
                      'font-size': '12pt',
                      'line-height': '1.5',
                      'margin': '20px'
                    });
                    $(win.document.body).find('table').css({
                      'border-collapse': 'collapse',
                      'width': '100%'
                    });
                    $(win.document.body).find('table th, table td').css({
                      'border': '1px solid #ddd',
                      'padding': '8px',
                      'font-size': '12pt'
                    });
                    $(win.document.body).find('table th').css({
                      'background-color': '#f2f2f2',
                      'font-weight': 'bold'
                    });
                  }
                }
              ]
            });
          });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

</body>
</html>