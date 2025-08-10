@extends('templateadmin.header')
@section('content')

<style>
    #toastSuccess {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        background-color: #198754;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        display: none;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    #toastSuccess.show {
        display: block;
        animation: fadeInOut 3s forwards;
    }

    @keyframes fadeInOut {
        0%   { opacity: 0; transform: translateX(100%); }
        10%  { opacity: 1; transform: translateX(0); }
        90%  { opacity: 1; transform: translateX(0); }
        100% { opacity: 0; transform: translateX(100%); }
    }
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-success">Absensi Mahasiswa Magang</h3>
        <a href="{{ route('absensi.rekap') }}" class="btn btn-secondary">Lihat Rekapan Absen</a>
    </div>

    <div class="form-group mb-4">
        <label for="tanggal">Tanggal</label>
        <input type="date" class="form-control" id="tanggal" value="{{ $tanggalHariIni }}" readonly>
    </div>

    <form method="GET" class="row g-3 align-items-center mb-4">
        <div class="col-md-4">
            <label for="kampus">Filter Kampus</label>
            <select name="kampus_id" id="kampus" class="form-control">
                <option value="">-- Semua Kampus --</option>
                @foreach($listKampus as $kampus)
                    <option value="{{ $kampus->id }}" {{ request('kampus_id') == $kampus->id ? 'selected' : '' }}>
                        {{ $kampus->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="search">Cari Mahasiswa</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="Cari nama..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary" style="margin-top: 2rem">Filter</button>
        </div>
    </form>

    <div id="toastSuccess">✅ Absensi berhasil disimpan.</div>

    <h5 class="mb-2 text-primary">Mahasiswa yang Sudah Absen</h5>
    <table class="table table-bordered table-sm">
        <thead class="table-light text-center">
            <tr>
                <th>Nama</th>
                <th>NIM</th>
                <th>Asal Kampus</th>
                <th>Jurusan</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody id="sudah-absen-body">
            @foreach($sudahAbsen as $mhs)
                @php
                    $status = \App\Models\Absensi::where('mahasiswa_id', $mhs->id)
                        ->whereDate('tanggal', $tanggalHariIni)
                        ->value('status');
                @endphp
                <tr>
                    <td>{{ $mhs->nama_mahasiswa }}</td>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->kampus->name ?? '-' }}</td>
                    <td>{{ $mhs->jurusan }}</td>
                    <td class="text-center">{{ $status }}</td>
                    <td>
                        {{ \App\Models\Absensi::where('mahasiswa_id', $mhs->id)
                            ->whereDate('tanggal', $tanggalHariIni)
                            ->value('keterangan') ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h5 class="mt-5 mb-2 text-danger">Mahasiswa yang Belum Absen</h5>
    @if($belumAbsen->count())
        <table class="table table-bordered">
            <thead class="table-success text-center">
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Asal Kampus</th>
                    <th>Jurusan</th>
                    <th>Status Kehadiran</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($belumAbsen as $mhs)
                    <tr>
                        <td>{{ $mhs->nama_mahasiswa }}</td>
                        <td>{{ $mhs->nim }}</td>
                        <td>{{ $mhs->kampus->name ?? '-' }}</td>
                        <td>{{ $mhs->jurusan }}</td>
                        <td class="text-center">
                            @foreach(['Hadir', 'Izin', 'Sakit', 'Alfa'] as $status)
                                <label class="me-2">
                                    <input type="radio" name="absensi_{{ $mhs->id }}" value="{{ $status }}" data-id="{{ $mhs->id }}">
                                    {{ $status }}
                                </label>
                            @endforeach
                        </td>
                        <td>
                            <input type="text"
                                   class="form-control"
                                   name="keterangan_{{ $mhs->id }}"
                                   placeholder="Isi Keterangan....">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-success">Semua mahasiswa sudah absen hari ini.</div>
    @endif

    @if($belumMulaiAbsen->count())
        <h5 class="mt-5 mb-2 text-warning">Mahasiswa yang Belum Bisa Absen (Magang Belum Dimulai)</h5>
        <table class="table table-bordered table-sm">
            <thead class="table-warning text-center">
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Asal Kampus</th>
                    <th>Jurusan</th>
                    <th>Periode Magang</th>
                </tr>
            </thead>
            <tbody>
                @foreach($belumMulaiAbsen as $mhs)
                    <tr>
                        <td>{{ $mhs->nama_mahasiswa }}</td>
                        <td>{{ $mhs->nim }}</td>
                        <td>{{ $mhs->kampus->name ?? '-' }}</td>
                        <td>{{ $mhs->jurusan }}</td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($mhs->pengajuan->mulai_tanggal)->format('d M Y') }}
                            s/d
                            {{ \Carbon\Carbon::parse($mhs->pengajuan->sampai_tanggal)->format('d M Y') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<audio id="clickSound" src="{{ asset('sounds/click.mp3') }}"></audio>
<audio id="successSound" src="{{ asset('sounds/success.mp3') }}"></audio>

<script>
    const clickSound = document.getElementById("clickSound");
    const successSound = document.getElementById("successSound");

    function showToast() {
        const toast = document.getElementById("toastSuccess");
        toast.classList.add("show");
        setTimeout(() => {
            toast.classList.remove("show");
        }, 3000);
    }

    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function () {
            if (clickSound) clickSound.play();

            const mahasiswaId = this.dataset.id;
            const status = this.value;
            const tanggal = document.getElementById('tanggal').value;
            const keterangan = document.querySelector(`input[name="keterangan_${mahasiswaId}"]`)?.value || '';

            fetch("{{ route('absensi.store.ajax') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    mahasiswa_id: mahasiswaId,
                    status: status,
                    tanggal: tanggal,
                    keterangan: keterangan
                })
            })
            .then(async res => {
                if (!res.ok) {
                    const errorMsg = await res.text();
                    console.error("Server error:", errorMsg);
                    alert("Gagal menyimpan absensi. Lihat console.");
                    throw new Error("HTTP error " + res.status);
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    if (successSound) successSound.play();
                    showToast();

                    const row = this.closest('tr');
                    const nama = row.children[0].innerText;
                    const nim = row.children[1].innerText;
                    const kampus = row.children[2].innerText;
                    const jurusan = row.children[3].innerText;

                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${nama}</td>
                        <td>${nim}</td>
                        <td>${kampus}</td>
                        <td>${jurusan}</td>
                        <td class="text-center"><span class="badge">${status}</span></td>
                    `;
                    document.getElementById('sudah-absen-body').appendChild(newRow);

                    row.remove();
                } else {
                    alert(data.message || "Gagal menyimpan absensi.");
                }
            })
            .catch(error => {
                console.error("Fetch error:", error);
                alert("Terjadi kesalahan jaringan atau server.");
            });
        });
    });
</script>

@endsection
