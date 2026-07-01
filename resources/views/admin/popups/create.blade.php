<x-laravel-admin::admin.layouts.admin title="팝업 등록">
    <x-slot name="header">
        <x-laravel-admin::admin.admin-header>
            <x-slot name="navigation">
                <a href="{{ route('admin.index') }}">관리자 홈</a>
                - <a href="{{ route('popup.admin.items.index') }}">팝업 목록</a>
                - 등록
            </x-slot>
            <x-slot name="description">Create Popup</x-slot>
        </x-laravel-admin::admin.admin-header>
    </x-slot>

    <x-laravel-admin::admin.page-section title="팝업 정보 등록" description="방문자에게 노출할 팝업, 배너, 공지 바의 콘텐츠와 조건을 입력합니다." class="mx-auto max-w-5xl">
        <form method="post" action="{{ route('popup.admin.items.store') }}">
            @csrf
            @include('laravel-popup::admin.popups.partials.form', [
                'submitLabel' => '등록하기',
                'showSampleButton' => true,
            ])
        </form>
    </x-laravel-admin::admin.page-section>
</x-laravel-admin::admin.layouts.admin>
