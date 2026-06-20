<?php

namespace Ssh521\LaravelPopup\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Popup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'status',
        'display_title',
        'body',
        'image_disk',
        'image_path',
        'image_alt',
        'link_url',
        'link_target',
        'link_rel',
        'position',
        'device',
        'starts_at',
        'ends_at',
        'include_paths',
        'exclude_paths',
        'close_policy',
        'close_duration',
        'priority',
        'sort_order',
        'settings',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'include_paths' => 'array',
        'exclude_paths' => 'array',
        'settings' => 'array',
    ];

    public function scopeActive(Builder $query, ?Carbon $now = null): Builder
    {
        $now ??= now();

        return $query
            ->where('status', 'active')
            ->where(function (Builder $query) use ($now) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function (Builder $query) use ($now) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            });
    }

    public function isActive(?Carbon $now = null): bool
    {
        $now ??= now();

        return $this->status === 'active'
            && ($this->starts_at === null || $this->starts_at->lte($now))
            && ($this->ends_at === null || $this->ends_at->gte($now));
    }

    public function matchesDevice(string $device): bool
    {
        return $this->device === 'all' || $this->device === $device;
    }

    public function matchesPath(string $path): bool
    {
        $path = '/'.trim($path, '/');
        $path = $path === '/' ? '/' : rtrim($path, '/');

        if ($this->matchesAnyPath($path, $this->exclude_paths ?? [])) {
            return false;
        }

        $includePaths = $this->include_paths ?? [];

        if ($includePaths === []) {
            return true;
        }

        return $this->matchesAnyPath($path, $includePaths);
    }

    public function closeKey(): string
    {
        return 'laravel_popup_'.$this->getKey().'_'.$this->close_policy;
    }

    /**
     * @param  array<int, string|null>  $patterns
     */
    private function matchesAnyPath(string $path, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (! is_string($pattern) || trim($pattern) === '') {
                continue;
            }

            $normalized = '/'.trim($pattern, '/');
            $normalized = $normalized === '/' ? '/' : rtrim($normalized, '/');

            if (Str::is($normalized, $path)) {
                return true;
            }
        }

        return false;
    }
}
