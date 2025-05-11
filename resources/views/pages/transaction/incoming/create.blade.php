@extends('layout.main')

@section('content')
    <x-breadcrumb
        :values="[__('menu.transaction.menu'), __('menu.transaction.incoming_letter'), __('menu.general.create')]">
    </x-breadcrumb>

    <div class="card mb-4">
        <form action="{{ route('transaction.incoming.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body row">
                <input type="hidden" name="type" value="incoming">

                {{-- Ubah reference_number menjadi classification_code --}}
                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <x-input-form name="classification_code" :label="__('model.letter.classification_code')" :value="request('classification_code') ?? ''" />
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <x-input-form name="agenda_number" :label="__('model.letter.agenda_number')" :value="request('agenda_number') ?? ''"/>
                </div>

                {{-- 2. Note --}}
                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                   <x-input-form name="note" :label="__('model.letter.note')" :value="request('note') ?? ''"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <x-input-form name="from" :label="__('model.letter.from')"/>
                </div>
                
                <div class="col-sm-12 col-12 col-md-6 col-lg-6">
                    <x-input-form name="letter_date" :label="__('model.letter.letter_date')" type="date"/>
                </div>

                <div class="col-sm-12 col-12 col-md-6 col-lg-6">
                    <x-input-form name="received_date" :label="__('model.letter.received_date')" type="date"/>
                </div>

                <div class="col-sm-12 col-12 col-md-12 col-lg-12">
                    <x-input-textarea-form name="description" :label="__('model.letter.description')"/>
                </div>

                {{-- Hapus dropdown classification_code --}}
                {{-- <div class="col-sm-12 col-12 col-md-6 col-lg-4">
                    <div class="mb-3">
                        <label for="classification_code"
                               class="form-label">{{ __('model.letter.classification_code') }}</label>
                        <select class="form-select" id="classification_code" name="classification_code">
                            @foreach($classifications as $classification)
                                <option
                                    value="{{ $classification->code }}"
                                    @selected(old('classification_code') == $classification->code)>
                                    {{ $classification->type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}                
            </div>
            <div class="card-footer pt-0">
                <button class="btn btn-primary" type="submit">{{ __('menu.general.save') }}</button>
            </div>
        </form>
    </div>
@endsection
