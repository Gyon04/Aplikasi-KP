<?php

namespace App\Http\Controllers;

use App\Enums\LetterType;
use App\Http\Requests\StoreLetterRequest;
use App\Http\Requests\UpdateLetterRequest;
use App\Models\Attachment;
use App\Models\Classification;
use App\Models\Config;
use App\Models\Letter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class IncomingLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        return view('pages.transaction.incoming.index', [
            'data' => Letter::incoming()->render($request->search),
            'search' => $request->search,
        ]);
    }
     /**
     * Display a listing of the incoming letter agenda.
     *
     * @param Request $request
     * @return View
     */
    public function agenda(Request $request): View
{
    $jenisHak = $request->jenis_hak ?? null; // ← 🆕 Tambahan baris ini

    return view('pages.transaction.incoming.agenda', [
        'data' => Letter::incoming()->agenda($request->since, $request->until, $request->filter, $jenisHak)->render($request->search), // ← 🆕 Argument ke-4 ditambahkan
        'search' => $request->search,
        'since' => $request->since,
        'until' => $request->until,
        'filter' => $request->filter,
        'jenis_hak' => $jenisHak, // ← 🆕 Tambahan baris ini
        'query' => $request->getQueryString(),
    ]);
}

    /**
     * @param Request $request
     * @return View
     */
    public function print(Request $request): View
    {
        $agenda = __('menu.agenda.menu');
        $letter = __('menu.agenda.incoming_letter');
        $title = App::getLocale() == 'id' ? "$agenda $letter" : "$letter $agenda";
        return view('pages.transaction.incoming.print', [
            'data' => Letter::incoming()->agenda($request->since, $request->until, $request->filter, $request->jenis_hak)->render($request->search),
            'search' => $request->search,
            'since' => $request->since,
            'until' => $request->until,
            'filter' => $request->filter,
            'config' => Config::pluck('value','code')->toArray(),
            'title' => $title,
        ]);
    }

    /**
     * Display a listing of the incoming letter daftar.
     *
     * @param Request $request
     * @return View
     */
    public function daftar(Request $request): View
    {
        $jenisHakList = [
            'HM' => 'HM',
            'HGB' => 'HGB',
            'HP' => 'HP',
            'HPL' => 'HPL',
            'HGU' => 'HGU',
            'W' => 'W',
        ];

        // Daftar seluruh kelurahan di Pontianak
        $kelurahanList = [
            'Benua Melayu Darat', 'Benua Melayu Laut', 'Darat Sekip', 'Darat Bangsa',
            'Pal Lima', 'Siantan Hulu', 'Siantan Tengah', 'Siantan Hilir',
            'Sungai Bangkong', 'Sungai Jawi', 'Sungai Jawi Dalam', 'Sungai Raya Dalam',
            'Sungai Beliung', 'Tanjung Hilir', 'Tanjung Hulu'
        ];

        $filtered = [];

        if ($request->filled('jenis_hak') && isset($jenisHakList[$request->jenis_hak])) {
            for ($i = 1; $i <= 200; $i++) {
                foreach ($kelurahanList as $kelurahan) {
                    // Buat nomor hak
                    $noHak = $jenisHakList[$request->jenis_hak] . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);
    
                    // Filter berdasarkan kelurahan dan no_hak jika ada
                    if (
                        (!$request->filled('kelurahan') || $request->kelurahan === $kelurahan) &&
                        (!$request->filled('no_hak') || str_contains($noHak, $request->no_hak))
                    ) {
                        $filtered[] = [
                            'jenis_hak' => $jenisHakList[$request->jenis_hak],
                            'no_hak' => $noHak,
                            'kelurahan' => $kelurahan,
                        ];
                    }
                }
            }
        }

        return view('pages.transaction.incoming.daftar', [
            'jenisHakList' => $jenisHakList,
            'kelurahanList' => $kelurahanList,
            'suratList' => $filtered,
            'selectedJenisHak' => $request->jenis_hak,
            'selectedKelurahan' => $request->kelurahan,
            'selectedNoHak' => $request->no_hak,
        ]);
    }


    /**
     * @param Request $request
     * @return View
     */
    

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('pages.transaction.incoming.create', [
            'classifications' => Classification::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLetterRequest $request
     * @return RedirectResponse
     */
    public function store(StoreLetterRequest $request): RedirectResponse
    {
        try {
            $user = auth()->user();

            if ($request->type != LetterType::INCOMING->type()) throw new \Exception(__('menu.transaction.incoming_letter'));
            $newLetter = $request->validated();
            $newLetter['user_id'] = $user->id;
            $letter = Letter::create($newLetter);
            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $attachment) {
                    $extension = $attachment->getClientOriginalExtension();
                    if (!in_array($extension, ['png', 'jpg', 'jpeg', 'pdf'])) continue;
                    $filename = time() . '-'. $attachment->getClientOriginalName();
                    $filename = str_replace(' ', '-', $filename);
                    $attachment->storeAs('public/attachments', $filename);
                    Attachment::create([
                        'filename' => $filename,
                        'extension' => $extension,
                        'user_id' => $user->id,
                        'letter_id' => $letter->id,
                    ]);
                }
            }
            return redirect()
                ->route('transaction.incoming.index')
                ->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Letter $incoming
     * @return View
     */
    public function show(Letter $incoming): View
    {
        return view('pages.transaction.incoming.show', [
            'data' => $incoming->load(['classification', 'user', 'attachments']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Letter $incoming
     * @return View
     */
    public function edit(Letter $incoming): View
    {
        return view('pages.transaction.incoming.edit', [
            'data' => $incoming,
            'classifications' => Classification::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateLetterRequest $request
     * @param Letter $incoming
     * @return RedirectResponse
     */
    public function update(UpdateLetterRequest $request, Letter $incoming): RedirectResponse
    {
        try {
            $incoming->update($request->validated());
            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $attachment) {
                    $extension = $attachment->getClientOriginalExtension();
                    if (!in_array($extension, ['png', 'jpg', 'jpeg', 'pdf'])) continue;
                    $filename = time() . '-'. $attachment->getClientOriginalName();
                    $filename = str_replace(' ', '-', $filename);
                    $attachment->storeAs('public/attachments', $filename);
                    Attachment::create([
                        'filename' => $filename,
                        'extension' => $extension,
                        'user_id' => auth()->user()->id,
                        'letter_id' => $incoming->id,
                    ]);
                }
            }
            return back()->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Letter $incoming
     * @return RedirectResponse
     */
    public function destroy(Letter $incoming): RedirectResponse
    {
        try {
            $incoming->delete();
            return redirect()
                ->route('transaction.incoming.index')
                ->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
