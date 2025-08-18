@extends('templateadmin.header')
@section('content')

<style>
    .status-radio { margin-right: 15px; font-size: 16px; }
    .status-radio input[type="radio"] { margin-right: 5px; transform: scale(1.2); }

    /* ====== TOAST GENERIK (success/error/warning) ====== */
    .toast-notif{
        position:fixed; bottom:20px; right:20px; z-index:9999;
        min-width:280px; max-width:360px;
        padding:14px 18px; border-radius:10px; color:#fff;
        display:flex; align-items:center; gap:12px;
        box-shadow:0 6px 12px rgba(0,0,0,.2);
        opacity:0; transform:translateX(120%); transition:all .5s ease;
        font-size:15px; font-weight:500;
    }
    .toast-notif.show{opacity:1; transform:translateX(0)}
    .toast-notif.success{background:#198754}
    .toast-notif.error{background:#dc3545}
    .toast-notif.warning{background:#ffc107; color:#000}
    .toast-icon{font-size:20px; flex-shrink:0; animation:bounce 1s infinite alternate}
    @keyframes bounce{from{transform:scale(1)} to{transform:scale(1.15)}}

    /* badge warna */
    .badge{display:inline-block; padding:.35em .6em; border-radius:.4rem; font-weight:600}
    .badge-hadir{background:#d1e7dd; color:#0f5132}
    .badge-sakit{background:#f8d7da; color:#842029}
    .badge-izin{background:#cff4fc; color:#055160}
    .badge-alfa{background:#fde2e1; color:#842029}
</style>

<div id="toast-container"></div>

<div class="container mt-4">
    @php
        use Carbon\Carbon;
        $hariIni = Carbon::now();
    @endphp

    @if($hariIni->isWeekend())
        <div class="alert alert-warning text-center fw-bold">
            ⚠️ Hari ini adalah <strong>{{ $hariIni->translatedFormat('l') }}</strong>. Absensi hanya tersedia pada hari kerja (Senin - Jumat).
        </div>
    @endif

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
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="sudah-absen-body">
            @foreach($sudahAbsen as $mhs)
                @php
                    $absensiData = \App\Models\Absensi::where('mahasiswa_id', $mhs->id)
                        ->whereDate('tanggal', $tanggalHariIni)
                        ->first();
                    $status = $absensiData ? ucfirst($absensiData->status) : '-';
                    $keterangan = $absensiData ? $absensiData->keterangan : '-';
                @endphp
                <tr>
                    <td>{{ $mhs->nama_mahasiswa }}</td>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->kampus->name ?? '-' }}</td>
                    <td>{{ $mhs->jurusan }}</td>
                    <td class="text-center">
                        @if(strtolower($status) == 'hadir')
                            <span class="badge badge-hadir">Hadir</span>
                        @elseif(strtolower($status) == 'sakit')
                            <span class="badge badge-sakit">Sakit</span>
                        @elseif(strtolower($status) == 'izin')
                            <span class="badge badge-izin">Izin</span>
                        @elseif(strtolower($status) == 'alfa')
                            <span class="badge badge-alfa">Alfa</span>
                        @else
                            {{ $status }}
                        @endif
                    </td>
                    <td>
                        @if(strtolower($status) == 'hadir' && (empty($keterangan) || $keterangan == '-'))
                            Hadir melaksanakan magang
                        @else
                            {{ $keterangan ?? '-' }}
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('absensi.edit', $absensiData->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h5 class="mt-5 mb-2 text-danger">Mahasiswa yang Belum Absen</h5>
    @if($hariIni->isWeekend())
        <div class="alert alert-info text-center">Tidak bisa mengisi absensi hari ini, karena bukan hari kerja.</div>
    @elseif($belumAbsen->count())
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
                            <div class="d-flex flex-wrap justify-content-center">
                                <label class="status-radio status-hadir">
                                    <input type="radio" name="absensi_{{ $mhs->id }}" value="Hadir" data-id="{{ $mhs->id }}"> Hadir
                                </label>
                                <label class="status-radio status-izin">
                                    <input type="radio" name="absensi_{{ $mhs->id }}" value="Izin" data-id="{{ $mhs->id }}"> Izin
                                </label>
                                <label class="status-radio status-sakit">
                                    <input type="radio" name="absensi_{{ $mhs->id }}" value="Sakit" data-id="{{ $mhs->id }}"> Sakit
                                </label>
                                <label class="status-radio status-alfa">
                                    <input type="radio" name="absensi_{{ $mhs->id }}" value="Alfa" data-id="{{ $mhs->id }}"> Alfa
                                </label>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="keterangan_{{ $mhs->id }}" placeholder="Isi Keterangan....">
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

<script>
    function showToast(type, message) {
        const container = document.getElementById("toast-container");
        const toast = document.createElement("div");
        toast.className = `toast-notif ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">${
                type === 'success' ? '✅' :
                type === 'error'   ? '❌' : '⚠️'
            }</div>
            <div>${message}</div>
        `;
        container.appendChild(toast);
        setTimeout(() => toast.classList.add("show"), 50);
        setTimeout(() => { toast.classList.remove("show"); setTimeout(() => toast.remove(), 500); }, 4000);
    }

    // Handler radio -> AJAX
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const mahasiswaId = this.dataset.id;
            const status = this.value;
            const tanggal = document.getElementById('tanggal').value;
            const keterangan = document.querySelector(`input[name="keterangan_${mahasiswaId}"]`)?.value || '';

            const allRadios = document.querySelectorAll(`input[name="absensi_${mahasiswaId}"]`);
            allRadios.forEach(r => r.disabled = true);

            fetch("{{ route('absensi.store.ajax') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    mahasiswa_id: mahasiswaId,
                    status, tanggal, keterangan
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message || 'Absensi berhasil disimpan.');
                    const row = this.closest('tr');
                    const nama = row.children[0].innerText;
                    const nim = row.children[1].innerText;
                    const kampus = row.children[2].innerText;
                    const jurusan = row.children[3].innerText;

                    let statusBadge = '';
                    let ketText = keterangan;

                    if (status === 'Hadir') {
                        statusBadge = '<span class="badge badge-hadir">Hadir</span>';
                        if (!ketText) ketText = 'Hadir melaksanakan magang';
                    } else if (status === 'Sakit') {
                        statusBadge = '<span class="badge badge-sakit">Sakit</span>';
                    } else if (status === 'Izin') {
                        statusBadge = '<span class="badge badge-izin">Izin</span>';
                    } else {
                        statusBadge = '<span class="badge badge-alfa">Alfa</span>';
                    }

                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${nama}</td>
                        <td>${nim}</td>
                        <td>${kampus}</td>
                        <td>${jurusan}</td>
                        <td class="text-center">${statusBadge}</td>
                        <td>${ketText || '-'}</td>
                        <td class="text-center">
                            <a href="/absensi/${data.id}/edit" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    `;
                    document.getElementById('sudah-absen-body').appendChild(newRow);
                    row.remove();
                } else {
                    // gagal (mis. hari libur)
                    allRadios.forEach(r => r.disabled = false);
                    this.checked = false;
                    showToast('warning', data.message || 'Gagal menyimpan absensi.');
                }
            })
            .catch(err => {
                allRadios.forEach(r => r.disabled = false);
                this.checked = false;
                showToast('error', 'Terjadi kesalahan: ' + (err?.message || 'Tidak diketahui'));
            });
        });
    });

    // Disable radio saat weekend (prevent klik)
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const day = today.getDay();
        if (day === 0 || day === 6){
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.disabled = true;
            });
        }
    });
</script>

@endsection
