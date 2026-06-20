<x-laravel-admin::admin.layouts.admin title="팝업 상세">
    <x-slot name="header">
        <x-laravel-admin::admin.admin-header>
            <x-slot name="navigation">
                <a href="{{ route('admin.index') }}">관리자 홈</a>
                - <a href="{{ route('popup.admin.items.index') }}">팝업 목록</a>
                - 상세
            </x-slot>
            <x-slot name="description">Popup Detail</x-slot>
        </x-laravel-admin::admin.admin-header>
    </x-slot>

    <div class="w-full bg-white px-2 py-2 dark:bg-gray-900">
        <div class="mx-auto max-w-4xl bg-white px-4 py-6 sm:px-6 lg:px-8 dark:bg-gray-900">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $popup->title }}</h1>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $popup->description }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('popup.admin.items.preview', $popup) }}" target="_blank" class="inline-flex h-9 items-center rounded-md border border-gray-300 px-3 text-sm font-semibold !text-gray-700 hover:no-underline dark:border-gray-600 dark:!text-gray-100">미리보기</a>
                    <a href="{{ route('popup.admin.items.edit', $popup) }}" class="inline-flex h-9 items-center rounded-md bg-indigo-600 px-3 text-sm font-semibold !text-white hover:bg-indigo-500 hover:no-underline">수정</a>
                </div>
            </div>

            <dl class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                    <dt class="text-xs font-semibold uppercase text-gray-500">타입</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $types[$popup->type] ?? $popup->type }}</dd>
                </div>
                <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                    <dt class="text-xs font-semibold uppercase text-gray-500">상태</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $statuses[$popup->status] ?? $popup->status }}</dd>
                </div>
                <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                    <dt class="text-xs font-semibold uppercase text-gray-500">기간</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $popup->starts_at?->format('Y-m-d H:i') ?: '즉시' }} - {{ $popup->ends_at?->format('Y-m-d H:i') ?: '무기한' }}</dd>
                </div>
                <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                    <dt class="text-xs font-semibold uppercase text-gray-500">조건</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $devices[$popup->device] ?? $popup->device }} / {{ $positions[$popup->position] ?? $popup->position }}</dd>
                </div>
            </dl>

            <div class="mt-8 rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">콘텐츠</h2>
                @if($popup->image_path)
                    <img src="{{ $popup->image_path }}" alt="{{ $popup->image_alt }}" class="mt-4 max-h-80 rounded-md">
                @endif
                @if($popup->display_title)
                    <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">{{ $popup->display_title }}</h3>
                @endif
                <div class="prose mt-3 max-w-none dark:prose-invert">{!! $popup->body !!}</div>
            </div>
        </div>
    </div>
</x-laravel-admin::admin.layouts.admin>
