<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameAccountField;
use App\Models\GameService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GameAccountFieldController extends Controller
{
    protected array $inputTypes = ['text', 'number', 'email', 'tel'];

    public function index(Request $request)
    {
        $gamesFromServices = GameService::select('game')
            ->distinct()
            ->orderBy('game')
            ->pluck('game')
            ->toArray();

        $gamesFromFields = GameAccountField::select('game_name')
            ->distinct()
            ->orderBy('game_name')
            ->pluck('game_name')
            ->toArray();

        $games = collect(array_merge($gamesFromServices, $gamesFromFields))
            ->filter()
            ->unique()
            ->values();

        $selectedGame = $request->query('game');

        if (!$selectedGame) {
            $selectedGame = GameAccountField::select('game_name')
                ->orderBy('game_name')
                ->value('game_name');
        }

        if (!$selectedGame && $games->isNotEmpty()) {
            $selectedGame = $games->first();
        }

        $fields = $selectedGame
            ? GameAccountField::where('game_name', $selectedGame)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
            : collect();

        return view('admin.game-account-fields.index', [
            'games' => $games,
            'selectedGame' => $selectedGame,
            'fields' => $fields,
            'inputTypes' => $this->inputTypes,
        ]);
    }

    public function store(Request $request)
    {
        $this->normalizeFieldKey($request);

        $validated = $this->validateData($request);

        $validated = $this->applyFinalTransforms($validated);

        GameAccountField::create($validated);

        return redirect()->back()->with('success', 'Field akun berhasil ditambahkan.');
    }

    public function update(Request $request, GameAccountField $gameAccountField)
    {
        $this->normalizeFieldKey($request);

        $validated = $this->validateData($request, $gameAccountField->id);

        if (!array_key_exists('sort_order', $validated) || $validated['sort_order'] === null) {
            $validated['sort_order'] = $gameAccountField->sort_order;
        }

        $validated = $this->applyFinalTransforms($validated, $gameAccountField->game_name, $gameAccountField->id);

        $gameAccountField->update($validated);

        return redirect()->back()->with('success', 'Field akun berhasil diperbarui.');
    }

    public function destroy(GameAccountField $gameAccountField)
    {
        $gameAccountField->delete();

        return redirect()->back()->with('success', 'Field akun berhasil dihapus.');
    }

    protected function normalizeFieldKey(Request $request): void
    {
        $fieldKey = $request->input('field_key');
        $label = $request->input('label');

        if (!$fieldKey && $label) {
            $fieldKey = Str::snake(Str::lower($label));
        }

        if ($fieldKey) {
            $request->merge([
                'field_key' => Str::snake(Str::lower($fieldKey)),
            ]);
        }
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'game_name' => ['required', 'string', 'max:255'],
            'field_key' => [
                'required',
                'alpha_dash',
                'max:255',
                Rule::unique('game_account_fields', 'field_key')
                    ->where(fn ($query) => $query->where('game_name', $request->game_name))
                    ->ignore($ignoreId),
            ],
            'label' => ['required', 'string', 'max:255'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'input_type' => ['required', Rule::in($this->inputTypes)],
            'is_required' => ['nullable', 'boolean'],
            'helper_text' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [], [
            'game_name' => 'Nama game',
            'field_key' => 'Field key',
            'label' => 'Label',
            'placeholder' => 'Placeholder',
            'input_type' => 'Tipe input',
            'is_required' => 'Wajib diisi',
            'helper_text' => 'Catatan bantuan',
            'sort_order' => 'Urutan',
        ]);
    }

    protected function applyFinalTransforms(array $validated, ?string $fallbackGameName = null, ?int $ignoreId = null): array
    {
        $gameName = $validated['game_name'] ?? $fallbackGameName;

        if (!isset($validated['sort_order']) || $validated['sort_order'] === null) {
            $nextOrder = $gameName
                ? GameAccountField::where('game_name', $gameName)
                    ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                    ->max('sort_order')
                : null;
            $validated['sort_order'] = $nextOrder !== null ? $nextOrder + 1 : 1;
        }

        $validated['is_required'] = isset($validated['is_required'])
            ? (bool) $validated['is_required']
            : false;

        return $validated;
    }
}
