@extends('layout.main')

@section('content')
    <x-breadcrumb :values="[__('menu.transaction.menu'), __('menu.transaction.outgoing_letter')]"> </x-breadcrumb>

    <div class="card mb-4 p-4">
        <h4>Daftar Surat yang Sedang Dipinjam</h4>

        <form action="{{ route('transaction.outgoing.kembalikan') }}" method="POST">
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
                                <input type="checkbox" name="surat[]" value="{{ $surat->kode }}" style="transform: scale(1.5);">
                            </td>
                            <td>{{ $surat->classification_code }}</td>
                            <td>{{ $surat->agenda_number }}</td>
                            <td>{{ $surat->note }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary mt-3">
                Kembalikan Surat yang Dipilih
            </button>

        </form>

    </div>
@endsection
