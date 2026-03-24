<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Cat;
use App\Support\AdvertisementCreator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    private const SESSION_KEY = 'pending_advertisement';

    public function __construct(
        private readonly AdvertisementCreator $creator,
    ) {}

    public function create(Request $request): View
    {
        $step = (int) $request->integer('step', 1);
        $catalogs = Cat::query()->orderBy('name')->get(['id', 'name', 'cat_up_id']);

        if ($catalogs->isEmpty()) {
            abort(404, 'Каталоги не найдены.');
        }

        if ($step === 2) {
            $catalogId = (int) $request->integer('catalog_id');
            $adType = (string) $request->query('ad_type', '');
            $catalog = $catalogs->firstWhere('id', $catalogId);
            $hasChildren = $catalogId > 0
                ? Cat::query()->where('cat_up_id', $catalogId)->exists()
                : false;

            if ($catalog === null || ! in_array($adType, ['sell', 'buy'], true) || $hasChildren) {
                return redirect()->route('advertisements.create')->with('status', 'Выберите категорию и тип объявления.');
            }

            return view('advertisements.create', [
                'step' => 2,
                'catalogs' => $catalogs,
                'catalogId' => $catalog->id,
                'catalogName' => $catalog->name,
                'adType' => $adType,
            ]);
        }

        return view('advertisements.create', [
            'step' => 1,
            'catalogId' => $request->filled('catalog_id') ? (int) $request->integer('catalog_id') : null,
            'adType' => (string) $request->query('ad_type', 'sell'),
        ]);
    }

    public function stepTwo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'catalog_id' => [
                'required',
                'integer',
                'exists:cats,id',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (Cat::query()->where('cat_up_id', (int) $value)->exists()) {
                        $fail('Выберите конечную подкатегорию без вложенных категорий.');
                    }
                },
            ],
            'ad_type' => ['required', 'in:sell,buy'],
        ]);

        return redirect()->route('advertisements.create', [
            'step' => 2,
            'catalog_id' => $validated['catalog_id'],
            'ad_type' => $validated['ad_type'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'catalog_id' => ['required', 'integer', 'exists:cats,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'ad_type' => ['required', 'in:sell,buy'],
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['required', 'image', 'max:5120'],
        ]);

        $photoPaths = [];
        foreach ($request->file('photos', []) as $photo) {
            $path = $photo->store('advertisements', 'public');
            $photoPaths[] = '/storage/'.ltrim($path, '/');
        }

        $payload = [
            'catalog_id' => (int) $validated['catalog_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'ad_type' => $validated['ad_type'],
            'photo_paths' => $photoPaths,
        ];

        $user = Auth::user();
        if ($user === null) {
            $request->session()->put(self::SESSION_KEY, $payload);

            return redirect()->route('register')->with('status', 'Чтобы разместить объявление, зарегистрируйтесь. После регистрации объявление будет добавлено автоматически.');
        }

        $this->creator->create($user->id, $payload);

        return redirect()
            ->route('catalog.show', ['id' => $payload['catalog_id']])
            ->with('status', 'Объявление успешно добавлено.');
    }

    public function show(Advertisement $advertisement): View
    {
        $advertisement->loadMissing(['photos', 'user', 'catalog']);

        return view('advertisements.show', [
            'advertisement' => $advertisement,
        ]);
    }
}
