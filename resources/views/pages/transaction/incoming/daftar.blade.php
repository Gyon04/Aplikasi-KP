@extends('layout.main')

@section('content')
    <x-breadcrumb :values="[__('menu.daftar.menu')]" />

    <div class="card mb-4 p-4">
        <h4>Daftar Surat</h4>
        
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
                                <td>{{ $surat['no_hak'] }}</td>
                                <td>{{ $surat['no_hak'] }}</td>
                                <td>{{ $surat['kelurahan'] }}</td>
                                <td>-</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">Data surat tidak tersedia.</td>
                        </tr>
                    @endif
                </tfoot>
            </table>
        </div>
    </div>
@endsection
