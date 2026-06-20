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

    <div class="w-full bg-white px-2 py-2 dark:bg-gray-900">
        <div class="bg-white px-4 py-6 sm:px-6 lg:px-8 dark:bg-gray-900">
            <form method="post" action="{{ route('popup.admin.items.store') }}">
                @csrf
                @include('laravel-popup::admin.popups.partials.form')
            </form>
        </div>
    </div>
</x-laravel-admin::admin.layouts.admin>
