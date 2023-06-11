<!DOCTYPE html>
<html>
<head>
    <title>Tampilan Data Seniman</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {background-color: #f5f5f5;}

        .form-container {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-container label {
            display: inline-block;
            width: 100px;
            margin-bottom: 10px;
        }

        .form-container input[type="text"] {
            width: 300px;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-container button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Tampilan Data Seniman</h1>
    <table id="senimanTable">
        <thead>
            <tr>
                <th>Nama Seniman</th>
                <th>Bidang Seni</th>
                <th>Umur</th>
                <th>Alamat</th>
                <th>ID Seni</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div class="form-container">
        <h2>Tambah Data Seniman</h2>
        <div>
            <label for="namaSenimanInput">Nama Seniman:</label>
            <input type="text" id="namaSenimanInput">
        </div>
        <div>
            <label for="bidangSeniInput">Bidang Seni:</label>
            <input type="text" id="bidangSeniInput">
        </div>
        <div>
            <label for="umurInput">Umur:</label>
            <input type="text" id="umurInput">
        </div>
        <div>
            <label for="alamatInput">Alamat:</label>
            <input type="text" id="alamatInput">
        </div>
        <button id="tambahDataBtn">Tambah Data</button>
    </div>

    <script>
        $(document).ready(function() {
            // Mengambil data dari REST API
            function getData() {
                $.ajax({
                    url: 'http://localhost/assesment3_web/koneksi.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Mengisi tabel dengan data yang diterima
                        var tableBody = $('#senimanTable tbody');
                        tableBody.empty();
                        for (var i = 0; i < data.length; i++) {
                            var seniman = data[i];
                            var row = "<tr>";
                            row += "<td>" + seniman.nama_seniman + "</td>";
                            row += "<td>" + seniman.bidang_seni + "</td>";
                            row += "<td>" + seniman.umur + "</td>";
                            row += "<td>" + seniman.alamat + "</td>";
                            row += "<td>" + seniman.id_seni + "</td>";
                            row += "<td><button class='updateBtn' data-id='" + seniman.id_seni + "'>Update</button> <button class='deleteBtn' data-id='" + seniman.id_seni + "'>Delete</button></td>";
                            row += "</tr>";
                            tableBody.append(row);
                        }
                        setUpdateButtonHandlers();
                        setDeleteButtonHandlers();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            getData();

            function addData() {
                var nama_seniman = $('#namaSenimanInput').val();
                var bidang_seni = $('#bidangSeniInput').val();
                var umur = $('#umurInput').val();
                var alamat = $('#alamatInput').val();

                // Membuat objek data yang akan dikirim
                var newData = {
                    nama_seniman: nama_seniman,
                    bidang_seni: bidang_seni,
                    umur: umur,
                    alamat: alamat
                };

                $.ajax({
                    url: 'http://localhost/assesment3_web/koneksi.php',
                    type: 'POST',
                    data: newData,
                    dataType: 'json',
                    success: function(response) {
                        getData();

                        $('#namaSenimanInput').val('');
                        $('#bidangSeniInput').val('');
                        $('#umurInput').val('');
                        $('#alamatInput').val('');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            $('#tambahDataBtn').click(addData);

            function setUpdateButtonHandlers() {
                $('.updateBtn').click(function() {
                    var id_seni = $(this).data('id');
                    var row = $(this).closest('tr');
                    var nama_seniman = row.find('td:nth-child(1)').text();
                    var bidang_seni = row.find('td:nth-child(2)').text();
                    var umur = row.find('td:nth-child(3)').text();
                    var alamat = row.find('td:nth-child(4)').text();

                    var updateForm = "<td><input type='text' class='updateNama' name='nama_seniman' value='" + nama_seniman + "'></td>";
                    updateForm += "<td><input type='text' class='updateBidang' name='bidang_seni' value='" + bidang_seni + "'></td>";
                    updateForm += "<td><input type='text' class='updateUmur' name='umur' value='" + umur + "'></td>";
                    updateForm += "<td><input type='text' class='updateAlamat' name='alamat' value='" + alamat + "'></td>";
                    updateForm += "<td><button class='saveBtn' data-id='" + id_seni + "'>Save</button></td>";

                    row.html(updateForm);
                    setSaveButtonHandlers();
                });
            }
            
            function setDeleteButtonHandlers() {
                $('.deleteBtn').click(function() {
                    var id_seni = $(this).data('id');
                    $.ajax({
                        url: 'http://localhost/assesment3_web/koneksi.php',
                        type: 'DELETE',
                        data: { id_seni: id_seni }, // Mengirim ID data yang ingin dihapus ke API
                        dataType: 'json',
                        success: function(response) {
                            getData();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            }


            function setSaveButtonHandlers() {
                $('.saveBtn').click(function() {
                    var id_seni = $(this).data('id');
                    var row = $(this).closest('tr');
                    var nama_seniman = row.find('.updateNama').val();
                    var bidang_seni = row.find('.updateBidang').val();
                    var umur = row.find('.updateUmur').val();
                    var alamat = row.find('.updateAlamat').val();

                    var updatedData = {
                        id_seni: id_seni,
                        nama_seniman: nama_seniman,
                        bidang_seni: bidang_seni,
                        umur: umur,
                        alamat: alamat
                    };

                    $.ajax({
                        url: 'http://localhost/assesment3_web/koneksi.php',
                        type: 'PUT',
                        data: updatedData,
                        dataType: 'json',
                        success: function(response) {
                            getData();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
