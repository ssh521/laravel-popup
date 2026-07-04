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

    <x-laravel-admin::admin.page-section title="팝업 상세" description="팝업 콘텐츠와 현재 노출 조건을 확인합니다." class="mx-auto max-w-5xl">
        <x-slot name="actions">
            <x-laravel-admin::admin.action-button variant="secondary" :href="route('popup.admin.items.index')" icon="list">
                목록
            </x-laravel-admin::admin.action-button>
        </x-slot>

        <div class="mx-auto max-w-4xl">
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $popup->title }}</h1>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $popup->description ?: '관리 메모가 없습니다.' }}</p>
                        </div>
                        <x-laravel-admin::admin.badge :variant="$popup->status === 'active' ? 'success' : ($popup->status === 'draft' ? 'warning' : 'neutral')">
                            {{ $statuses[$popup->status] ?? $popup->status }}
                        </x-laravel-admin::admin.badge>
                    </div>
                </div>

                <div class="px-4 py-6 sm:px-6">
                    <div class="space-y-8">
                        <section>
                            <div class="mb-4">
                                <h3 class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">기본 정보</h3>
                                <p class="mt-1 text-sm leading-6 text-gray-500 dark:text-gray-400">팝업 타입과 노출 상태입니다.</p>
                            </div>
                            <dl class="grid grid-cols-1 border-t border-gray-100 sm:grid-cols-2 dark:border-gray-800">
                                <div class="px-0 py-4 sm:px-0 sm:py-5">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-white">타입</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2 dark:text-gray-300">{{ $types[$popup->type] ?? $popup->type }}</dd>
                                </div>
                                <div class="border-t border-gray-100 px-0 py-4 sm:border-t-0 sm:px-0 sm:py-5 dark:border-gray-800">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-white">기간</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2 dark:text-gray-300">{{ $popup->starts_at?->format('Y-m-d H:i') ?: '즉시' }} - {{ $popup->ends_at?->format('Y-m-d H:i') ?: '무기한' }}</dd>
                                </div>
                            </dl>
                        </section>

                        <section class="border-t border-gray-200 pt-6 dark:border-gray-700">
                            <div class="mb-4">
                                <h3 class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">노출 조건</h3>
                                <p class="mt-1 text-sm leading-6 text-gray-500 dark:text-gray-400">노출 디바이스와 위치입니다.</p>
                            </div>
                            <dl class="grid grid-cols-1 border-t border-gray-100 sm:grid-cols-2 dark:border-gray-800">
                                <div class="px-0 py-4 sm:px-0 sm:py-5">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-white">디바이스</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2 dark:text-gray-300">{{ $devices[$popup->device] ?? $popup->device }}</dd>
                                </div>
                                <div class="border-t border-gray-100 px-0 py-4 sm:border-t-0 sm:px-0 sm:py-5 dark:border-gray-800">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-white">위치</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2 dark:text-gray-300">{{ $positions[$popup->position] ?? $popup->position }}</dd>
                                </div>
                            </dl>
                        </section>
                    </div>
                </div>

                <div class="border-t border-gray-200 px-4 py-5 sm:px-6 dark:border-gray-700">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">콘텐츠</h2>
                    @if($popup->image_path)
                        <img src="{{ $popup->image_path }}" alt="{{ $popup->image_alt }}" class="mt-4 max-h-80 rounded-md">
                    @endif
                    @if($popup->display_title)
                        <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">{{ $popup->display_title }}</h3>
                    @endif
                    <div class="prose mt-3 max-w-none dark:prose-invert">{!! $popup->body !!}</div>
                </div>

                <div class="flex justify-end gap-2 border-t border-gray-200 px-4 py-4 sm:px-6 dark:border-gray-700">
                    <x-laravel-admin::admin.action-button variant="secondary" :href="route('popup.admin.items.preview', $popup)" target="_blank" rel="noopener noreferrer" icon="eye">
                        미리보기
                    </x-laravel-admin::admin.action-button>
                    <x-laravel-admin::admin.action-button :href="route('popup.admin.items.edit', $popup)" icon="pen-to-square">
                        수정하기
                    </x-laravel-admin::admin.action-button>
                </div>
            </div>
        </div>
    </x-laravel-admin::admin.page-section>
</x-laravel-admin::admin.layouts.admin>
