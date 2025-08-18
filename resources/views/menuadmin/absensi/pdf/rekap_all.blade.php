<!DOCTYPE html>
<html>
<head>
    <title>Rekap Absensi Per Bulan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 7px; }
        th, td { border: 1px solid black; padding: 2px 4px; text-align: center; }
        .title { font-weight: bold; font-size: 16px; text-align: center; margin: 15px 0; }
        .kampus-title { font-weight: bold; font-size: 14px; margin-top: 20px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
@foreach ($groupedByKampus as $kampusName => $bulanData)
    <div class="kampus-title">Kampus: {{ $kampusName }}</div>

    @foreach ($bulanData as $bulan => $mahasiswaData)
        @php
            $carbon = \Carbon\Carbon::createFromFormat('Y-m', $bulan);
            $daysInMonth = $carbon->daysInMonth;
        @endphp

        <div class="title">
            Rekapitulasi Absensi Bulan {{ $carbon->translatedFormat('F Y') }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Kampus</th>
                    @for ($d=1; $d<=$daysInMonth; $d++)
                        <th>{{ str_pad($d,2,'0',STR_PAD_LEFT) }}/{{ $carbon->format('m') }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach ($mahasiswaData as $row)
                    <tr>
                        <td>{{ $row['mahasiswa']->nama_mahasiswa }}</td>
                        <td>{{ $row['mahasiswa']->nim }}</td>
                        <td>{{ $kampusName }}</td>
                        @for ($d=1; $d<=$daysInMonth; $d++)
                            @php
                                $tgl = $carbon->format("Y-m-").str_pad($d,2,'0',STR_PAD_LEFT);
                                $status = $row['absensi'][$tgl] ?? '-';
                            @endphp
                            <td>{{ $status }}</td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="page-break"></div>
    @endforeach
@endforeach
</body>
</html>
