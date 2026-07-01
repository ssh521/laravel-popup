<x-laravel-admin::admin.layouts.admin title="팝업 수정">
    <x-slot name="header">
        <x-laravel-admin::admin.admin-header>
            <x-slot name="navigation">
                <a href="{{ route('admin.index') }}">관리자 홈</a>
                - <a href="{{ route('popup.admin.items.index') }}">팝업 목록</a>
                - 수정
            </x-slot>
            <x-slot name="description">Edit Popup</x-slot>
        </x-laravel-admin::admin.admin-header>
    </x-slot>

    <x-laravel-admin::admin.page-section title="팝업 정보 수정" description="팝업 콘텐츠, 노출 조건, 닫기 정책을 수정합니다." class="mx-auto max-w-5xl">
        <form id="popup-edit-form" method="post" action="{{ route('popup.admin.items.update', $popup) }}">
            @csrf
            @method('put')
            @include('laravel-popup::admin.popups.partials.form', [
                'submitLabel' => '수정하기',
                'showSampleButton' => false,
                'showActions' => false,
            ])
        </form>

        <div class="mx-auto mt-10 flex w-full max-w-4xl flex-row items-center justify-between gap-3">
            <div class="flex shrink-0 justify-start">
                @can('laravel-popup-items-delete')
                    <form action="{{ route('popup.admin.items.destroy', $popup) }}" method="post" onsubmit="return confirm('정말 삭제하시겠습니까?')">
                        @csrf
                        @method('delete')
                        <x-laravel-admin::admin.action-button type="submit" variant="danger" icon="trash-can">
                            삭제하기
                        </x-laravel-admin::admin.action-button>
                    </form>
                @endcan
            </div>

            <div class="flex shrink-0 flex-nowrap justify-end gap-3">
                <x-laravel-admin::admin.action-button variant="secondary" :href="route('popup.admin.items.index')">
                    취소
                </x-laravel-admin::admin.action-button>
                <x-laravel-admin::admin.action-button type="submit" form="popup-edit-form">
                    수정하기
                </x-laravel-admin::admin.action-button>
            </div>
        </div>
    </x-laravel-admin::admin.page-section>
</x-laravel-admin::admin.layouts.admin>
