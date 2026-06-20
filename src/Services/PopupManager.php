<?php

namespace Ssh521\LaravelPopup\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Ssh521\LaravelPopup\Models\Popup;

class PopupManager
{
    public function __construct(private readonly DeviceDetector $deviceDetector)
    {
    }

    /**
     * @return Collection<int, Popup>
     */
    public function forRequest(Request $request): Collection
    {
        if (! config('laravel-popup.public.enabled', true)) {
            return new Collection;
        }

        $device = $this->deviceDetector->detect($request);
        $path = '/'.trim($request->path(), '/');
        $path = $path === '/' ? '/' : rtrim($path, '/');

        $items = Popup::query()
            ->active()
            ->whereIn('device', ['all', $device])
            ->orderByDesc('priority')
            ->orderBy('sort_order')
            ->latest('id')
            ->get()
            ->filter(fn (Popup $popup) => $popup->matchesPath($path))
            ->values();

        return $this->applyTypeLimits($items);
    }

    /**
     * @param  Collection<int, Popup>  $items
     * @return Collection<int, Popup>
     */
    private function applyTypeLimits(Collection $items): Collection
    {
        $limits = config('laravel-popup.public.max_items_per_type', []);
        $counts = [];

        return $items->filter(function (Popup $popup) use (&$counts, $limits) {
            $limit = $limits[$popup->type] ?? null;

            if ($limit === null) {
                return true;
            }

            $counts[$popup->type] = ($counts[$popup->type] ?? 0) + 1;

            return $counts[$popup->type] <= $limit;
        })->values();
    }
}
