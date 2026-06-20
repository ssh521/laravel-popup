<?php

namespace Ssh521\LaravelPopup\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePopupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', Rule::in(array_keys(config('laravel-popup.types', [])))],
            'status' => ['required', 'string', Rule::in(array_keys(config('laravel-popup.statuses', [])))],
            'display_title' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'image_disk' => ['nullable', 'string', 'max:100'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'link_target' => ['nullable', 'string', Rule::in(['_self', '_blank'])],
            'link_rel' => ['nullable', 'string', 'max:100'],
            'position' => ['required', 'string', Rule::in(array_keys(config('laravel-popup.positions', [])))],
            'device' => ['required', 'string', Rule::in(array_keys(config('laravel-popup.devices', [])))],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'include_paths' => ['nullable', 'string'],
            'exclude_paths' => ['nullable', 'string'],
            'close_policy' => ['required', 'string', Rule::in(array_keys(config('laravel-popup.close_policies', [])))],
            'close_duration' => ['nullable', 'integer', 'min:1'],
            'priority' => ['nullable', 'integer', 'min:-100000', 'max:100000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'width' => ['nullable', 'string', 'max:50'],
            'max_width' => ['nullable', 'string', 'max:50'],
            'background_color' => ['nullable', 'string', 'max:50'],
            'text_color' => ['nullable', 'string', 'max:50'],
            'z_index' => ['nullable', 'integer', 'min:0', 'max:2147483647'],
        ];
    }
}
