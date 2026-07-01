<?php

namespace Ssh521\LaravelPopup\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ssh521\LaravelPopup\Http\Requests\Admin\StorePopupRequest;
use Ssh521\LaravelPopup\Http\Requests\Admin\UpdatePopupRequest;
use Ssh521\LaravelPopup\Models\Popup;

class PopupController extends Controller
{
    public function index(Request $request): View
    {
        $popups = Popup::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('display_title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->string('type')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('device'), fn ($query) => $query->where('device', $request->string('device')))
            ->orderByDesc('priority')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('laravel-popup::admin.popups.index', [
            'popups' => $popups,
            ...$this->options(),
        ]);
    }

    public function create(): View
    {
        return view('laravel-popup::admin.popups.create', [
            'popup' => new Popup([
                'type' => 'popup',
                'status' => 'draft',
                'position' => 'center',
                'device' => 'all',
                'close_policy' => 'close',
                'link_target' => '_self',
                'priority' => 0,
                'sort_order' => 0,
            ]),
            ...$this->options(),
        ]);
    }

    public function store(StorePopupRequest $request): RedirectResponse
    {
        Popup::create($this->popupData($request->validated()));

        return redirect()
            ->route('popup.admin.items.index')
            ->with('success', '팝업 항목이 생성되었습니다.');
    }

    public function show(Popup $popup): View
    {
        return view('laravel-popup::admin.popups.show', [
            'popup' => $popup,
            ...$this->options(),
        ]);
    }

    public function edit(Popup $popup): View
    {
        return view('laravel-popup::admin.popups.edit', [
            'popup' => $popup,
            ...$this->options(),
        ]);
    }

    public function update(UpdatePopupRequest $request, Popup $popup): RedirectResponse
    {
        $popup->update($this->popupData($request->validated()));

        return redirect()
            ->route('popup.admin.items.index')
            ->with('success', '팝업 항목이 수정되었습니다.');
    }

    public function duplicate(Popup $popup): RedirectResponse
    {
        $copy = $popup->replicate();
        $copy->title = $popup->title.' 복사본';
        $copy->status = 'draft';
        $copy->save();

        return redirect()
            ->route('popup.admin.items.edit', $copy)
            ->with('success', '팝업 항목이 복제되었습니다.');
    }

    public function preview(Popup $popup): View
    {
        return view('laravel-popup::admin.popups.preview', compact('popup'));
    }

    public function destroy(Popup $popup): RedirectResponse
    {
        $popup->delete();

        return redirect()
            ->route('popup.admin.items.index')
            ->with('success', '팝업 항목이 삭제되었습니다.');
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function options(): array
    {
        return [
            'types' => config('laravel-popup.types', []),
            'statuses' => config('laravel-popup.statuses', []),
            'devices' => config('laravel-popup.devices', []),
            'positions' => config('laravel-popup.positions', []),
            'closePolicies' => config('laravel-popup.close_policies', []),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function popupData(array $data): array
    {
        $data['include_paths'] = $this->normalizeLines($data['include_paths'] ?? null);
        $data['exclude_paths'] = $this->normalizeLines($data['exclude_paths'] ?? null);
        $data['settings'] = $this->settingsData($data);
        $data['priority'] = $data['priority'] ?? 0;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['link_target'] = $data['link_target'] ?? '_self';

        unset($data['width'], $data['max_width'], $data['background_color'], $data['text_color'], $data['z_index']);

        return $data;
    }

    /**
     * @return array<int, string>
     */
    private function normalizeLines(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value)));
        }

        if (! is_string($value)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value) ?: [])));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function settingsData(array $data): array
    {
        return array_filter([
            'width' => $data['width'] ?? null,
            'max_width' => $data['max_width'] ?? null,
            'background_color' => $data['background_color'] ?? null,
            'text_color' => $data['text_color'] ?? null,
            'z_index' => $data['z_index'] ?? null,
        ], fn ($value) => $value !== null && $value !== '');
    }
}
