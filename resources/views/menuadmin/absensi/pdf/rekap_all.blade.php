<!DOCTYPE html>
<html>

  <head>
    <title>
      Rekap Absensi
    </title>
      <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 6px; text-align: center; }
        .title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 10px; }
      </style>
  </head>

  <body>
    
    <div class="title">Rekapitulasi Absensi Mahasiswa Magang</div>

    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>NIM</th>
          <th>Kampus</th>
          <th>Periode</th>
          <th>Hadir</th>
          <th>Izin</th>
          <th>Sakit</th>
          <th>Alfa</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($data as $index => $mhs)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $mhs['nama'] }}</td>
            <td>{{ $mhs['nim'] }}</td>
            <td>{{ $mhs['kampus'] }}</td>
            <td>
              {{ $mhs['mulai_tanggal'] ? \Carbon\Carbon::parse($mhs['mulai_tanggal'])->format('d/m/Y') : '-' }} -
              {{ $mhs['sampai_tanggal'] ? \Carbon\Carbon::parse($mhs['sampai_tanggal'])->format('d/m/Y') : '-' }}
            </td>
            <td>{{ $mhs['hadir'] }}</td>
            <td>{{ $mhs['izin'] }}</td>
            <td>{{ $mhs['sakit'] }}</td>
            <td>{{ $mhs['alfa'] }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

  </body>

</html>