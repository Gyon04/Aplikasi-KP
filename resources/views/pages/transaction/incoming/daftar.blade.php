@extends('layout.main')

@section('content')

    <x-breadcrumb :values="[__('menu.daftar.menu')]" />

    <div class="card mb-4 p-4">
        {{-- Filter Jenis Hak --}}
        <div class="card mb-4 p-4">
            <h4>Pilih Jenis Hak</h4>

            <form method="GET" action="{{ route('daftar.incoming') }}">
                <div class="mb-3">
                    <select name="jenis_hak" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Pilih Jenis Hak --</option>
                        @foreach($jenisHakList as $kode => $label)
                            <option value="{{ $kode }}" {{ ($selectedJenisHak == $kode) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Kelurahan</label>
                    <select name="kelurahan" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Semua Kelurahan --</option>
                        @foreach($kelurahanList as $nama)
                            <option value="{{ $nama }}" {{ request('kelurahan') == $nama ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Nomor Hak</label>
                    <input type="text" name="no_hak" class="form-control" value="{{ request('no_hak') }}" placeholder="Cari Nomor Hak" onchange="this.form.submit()">
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end gap-2">
                    <a href="{{ route('daftar.incoming') }}" class="btn btn-secondary w-50">Reset</a>
                </div>
            </form>
        </div>
    

        {{-- Tabel Surat --}}
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Hak</th>
                    <th>Nomor Hak</th>
                    <th>Kelurahan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($suratList) && count($suratList) > 0)
                    @foreach ($suratList as $index => $surat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $surat['jenis_hak'] }}</td>
                            <td>{{ $surat['no_hak'] }}</td>
                            <td>{{ $surat['kelurahan'] }}</td>
                            <td>
                            <a href="{{ route('transaction.incoming.create', [
                                'classification_code' => $surat['jenis_hak'],
                                'agenda_number' => $surat['no_hak'],
                                'note' => $surat['kelurahan']
                            ]) }}" class="btn btn-sm btn-primary">
                                Input Surat
                            </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">Silakan pilih Jenis Hak terlebih dahulu.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
