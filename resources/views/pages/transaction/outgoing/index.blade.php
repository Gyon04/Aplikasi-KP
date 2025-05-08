@extends('layout.main')

@section('content')
    <x-breadcrumb :values="[__('menu.transaction.menu'), __('menu.transaction.outgoing_letter')]"> </x-breadcrumb>

    <div class="card mb-4 p-4">
        <h4>Daftar Surat yang Sedang Dipinjam</h4>

        @php
            $daftarSurat = [
                ['kode' => 'SRT-001', 'jenis_hak' => 'Hak Milik', 'no_surat' => '001/ADM/2025', 'kelurahan' => 'Kelurahan A'],
                ['kode' => 'SRT-002', 'jenis_hak' => 'HGU', 'no_surat' => '002/ADM/2025', 'kelurahan' => 'Kelurahan B'],
                ['kode' => 'SRT-003', 'jenis_hak' => 'HGB', 'no_surat' => '003/ADM/2025', 'kelurahan' => 'Kelurahan C'],
            ];
        @endphp

        <form action="#" method="POST">
            @csrf

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th style="width: 50px;">Pilih</th>
                        <th>Jenis Hak</th>
                        <th>Nomor Surat</th>
                        <th>Kelurahan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($daftarSurat as $surat)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="surat[]" value="{{ $surat['kode'] }}" style="transform: scale(1.5);">
                            </td>
                            <td>{{ $surat['jenis_hak'] }}</td>
                            <td>{{ $surat['no_surat'] }}</td>
                            <td>{{ $surat['kelurahan'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="btn btn-primary mt-3">
                Kembalikan Surat yang Dipilih
            </a>
        </form>
    </div>
@endsection
