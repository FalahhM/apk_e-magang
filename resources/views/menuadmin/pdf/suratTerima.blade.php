<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Terima</title>
    <style>
        /* Mengatur margin dan padding halaman */
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 9pt;
            margin: 30px;
            line-height: 1.6;
        }

        /* Header Surat */
        .kopsurat1 {
            align-items: center;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .kopsurat1 p {
            margin: 0;
            text-align: left;
            font-weight: bold;
            font-size: 11pt;
        }

        img {
            width: 90px; /* Tentukan ukuran logo */
            height: auto;
            float: right;
            margin-top: -70px;
        }

        /* Tabel Informasi Surat */
        
        .surat2 p{
          text-align: right;
        }

        .surat2 .tabel{
          margin-top: -36px
        }

        .surat2 table {
            width: 50%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .surat2 table td {
            padding: 5px 0;
            vertical-align: top;
        }

        /* Halaman */
        .hal {
            margin-bottom: 5px;
        }


        /* Badan Surat */
        .badan p {
            text-align: justify;
            text-indent: 50px;
            margin-bottom: 40px;
        }

        /* Tanda Tangan */
        .ttd {
            margin-top: 60px;
            margin-left: 450px;
        }
        
        .ttd table{
            text-align: center;
        }

    </style>
</head>
<body>
    
<div class="kopsurat1">
    <p>PT. PERKEBUNAN NUSANTARA IV <br> REGIONAL IV</p>
    <img src="gambar/logoptpn.png" alt="">
</div>

<div class="surat2">
    <div class="tanggal">
      <p>Jambi, {{ \Carbon\Carbon::parse($pengajuan->cetakTerima_timestamp)->format('d F Y') }}</p>
    </div>
    <div class="tabel">
      <table>
          <tr>
              <td>Nomor</td>
              <td>: 4SDM/X.{{ $pengajuan->noSuratTerima }}/{{ $bulanRomawi }}/{{ now()->year }}</td>
          </tr>
          <tr>
              <td>Hal</td>
              <td>: {{ $pengajuan->perihal }}</td>
          </tr>
      </table>
    </div>
</div>

<div class="hal">
    <p>Kepada Yth.</p>
    <p>{{ $pengajuan->user->name }}</p>
</div>

<div class="badan">
    <p>
        Menindaklanjuti surat  No: {{ $pengajuan->no_surat }} tanggal {{ \Carbon\Carbon::parse($pengajuan->tanggal_surat)->format('d F Y') }}, perihal {{ $pengajuan->perihal }}
        yang telah disetujui, maka kegiatan tersebut dapat dilaksanakan mulai tanggal <b>{{ \Carbon\Carbon::parse($pengajuan->mulai_tanggal)->format('d F Y') }}</b> s.d <b>{{ \Carbon\Carbon::parse($pengajuan->sampai_tanggal)->format('d F Y') }} </b>
        di <i>Region Office</i> PT. Perkebunan Nusantara IV Regional 4 sebanyak {{ $jumlahOrang }} orang (nama mahasiswa terlampir).
        dengan rincian terlampir. 
        <br><br>

        Berkaitan dengan hal tersebut diatas, kami sampaikan sebagai berikut :
        <ol>
          <li>Setiap peserta wajib mematuhi semua peraturan yang berlaku di PT. Perkebunan Nusantara IV Regional 4.</li>
          <li>Hasil kegiatan semata-mata hanya untuk kepentingan pendidikan, tidak untuk dikomersilkan.</li>
          <li>Yang bersangkutan wajib menyampaikan draft laporan kegiatan/penelitian/pengambilan data/skripsi kepada Pimpinan di Bagian Kerja dan hanya dapat dibenarkan apabila telah divalidasi dan dilengkapi dengan surat keterangan dari pimpinan di Bagian Kerja tempat kegiatan yang berlangsung.</li>
          <li>Menyerahkan hasil kegiatan ke PT. Perkebunan Nusantara IV Regional 4 yang telah ditandangani dan disahkan oleh pembimbingyang bersangkutan (setelah poin 3 dilaksanakan).</li>
          <li>Biaya yang timbul dalam pelaksanaan kegiatan tersebut adalah menjadi beban dan tanggung jawab yang bersangkutan sepenuhnya.</li>
          <li>Tidak dibenarkan melaksanakan kegiatan pada hari libur kerja/tanpa pengawasan dari PT. Perkebunan Nusantara IV Regional 4.</li>
          <li>PT. Perkebunan Nusantara IV Regional 4 tidak menyediakan transportasi, akomodasi dan penginapan bagi peserta, pembimbing maupun pihak pemohon.</li>
          <li>Hal-hal yang dapat mencemarkan nama baik PT. Perkebunan Nusantara IV Regional 4 akibat dari perbuatan peserta akan diselesaikan oleh pihak yang berwenang.</li>
        </ol>

        <br><br>
        Atas perhatian dan kerjasamanya diucapkan terima kasih.
        
    </p>
</div>

<div class="ttd">
    <table>
        <tr>
            <td>Bagian SDM & Sistem Manajemen</td><br><br><br><br><br><br>
        </tr>
        <tr>
            <td>
                <img src="{{ $qrCodeUri }}"/>
            </td>
        </tr>
        <tr>
            <td><u><b>{{ $pengajuan->nama_kabag }}</b></u></td>
        </tr>
        <tr>
            <td>Kepala Bagian</td>
        </tr>
    </table>
</div>

</body>
</html>


