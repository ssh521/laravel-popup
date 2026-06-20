@php
    $popupItems = $popups ?? app('laravel-popup')->forRequest(request());
    $positionClasses = [
        'top' => 'top-0 left-0 right-0',
        'bottom' => 'bottom-0 left-0 right-0',
        'center' => 'top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2',
        'top_left' => 'top-4 left-4',
        'top_right' => 'top-4 right-4',
        'bottom_left' => 'bottom-4 left-4',
        'bottom_right' => 'bottom-4 right-4',
    ];
@endphp

@if($popupItems->isNotEmpty())
    <div data-laravel-popup-root>
        @foreach($popupItems as $popup)
            @php
                $settings = $popup->settings ?? [];
                $style = collect([
                    'width' => $settings['width'] ?? null,
                    'max-width' => $settings['max_width'] ?? null,
                    'background-color' => $settings['background_color'] ?? null,
                    'color' => $settings['text_color'] ?? null,
                    'z-index' => $settings['z_index'] ?? 50,
                ])->filter(fn ($value) => $value !== null && $value !== '')->map(fn ($value, $key) => $key.': '.$value)->implode('; ');
                $isBar = in_array($popup->type, ['banner', 'notice_bar'], true) && in_array($popup->position, ['top', 'bottom'], true);
            @endphp

            <section
                data-laravel-popup
                data-popup-id="{{ $popup->id }}"
                data-close-key="{{ $popup->closeKey() }}"
                data-close-policy="{{ $popup->close_policy }}"
                data-close-duration="{{ $popup->close_duration }}"
                class="fixed {{ $positionClasses[$popup->position] ?? $positionClasses['center'] }} {{ $isBar ? 'w-full' : 'w-[calc(100vw-2rem)] sm:w-auto' }}"
                style="{{ $style }}"
                role="{{ $popup->type === 'popup' ? 'dialog' : 'region' }}"
                aria-label="{{ $popup->display_title ?: $popup->title }}"
            >
                <div class="{{ $isBar ? 'w-full' : 'rounded-lg' }} border border-gray-200 bg-white text-gray-900 shadow-lg dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <div class="{{ $isBar ? 'mx-auto flex max-w-7xl items-center gap-3 px-4 py-3' : 'p-5' }}">
                        @if($popup->image_path)
                            @if($popup->link_url)
                                <a href="{{ $popup->link_url }}" target="{{ $popup->link_target }}" rel="{{ $popup->link_rel ?: ($popup->link_target === '_blank' ? 'noopener noreferrer' : null) }}">
                                    <img src="{{ $popup->image_path }}" alt="{{ $popup->image_alt }}" class="{{ $isBar ? 'h-10 w-auto shrink-0' : 'mb-4 max-h-96 w-full rounded-md object-cover' }}">
                                </a>
                            @else
                                <img src="{{ $popup->image_path }}" alt="{{ $popup->image_alt }}" class="{{ $isBar ? 'h-10 w-auto shrink-0' : 'mb-4 max-h-96 w-full rounded-md object-cover' }}">
                            @endif
                        @endif

                        <div class="{{ $isBar ? 'min-w-0 flex-1' : '' }}">
                            @if($popup->display_title)
                                <h2 class="{{ $isBar ? 'truncate text-sm font-semibold' : 'text-lg font-semibold' }}">{{ $popup->display_title }}</h2>
                            @endif
                            @if($popup->body)
                                <div class="{{ $isBar ? 'truncate text-sm' : 'prose mt-2 max-w-none text-sm dark:prose-invert' }}">{!! $popup->body !!}</div>
                            @endif
                            @if($popup->link_url && ! $popup->image_path)
                                <a href="{{ $popup->link_url }}" target="{{ $popup->link_target }}" rel="{{ $popup->link_rel ?: ($popup->link_target === '_blank' ? 'noopener noreferrer' : null) }}" class="mt-3 inline-flex text-sm font-semibold !text-indigo-600 hover:no-underline dark:!text-indigo-300">
                                    자세히 보기
                                </a>
                            @endif
                        </div>

                        @if($popup->close_policy !== 'none')
                            <div class="{{ $isBar ? 'ml-auto flex shrink-0 items-center gap-2' : 'mt-4 flex justify-end gap-2' }}">
                                @if(in_array($popup->close_policy, ['hide_today', 'hide_period'], true))
                                    <button type="button" data-laravel-popup-hide class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                        {{ $popup->close_policy === 'hide_today' ? '오늘 하루 닫기' : '다시 보지 않기' }}
                                    </button>
                                @endif
                                <button type="button" data-laravel-popup-close class="inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white" aria-label="닫기">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endforeach
    </div>

    <script>
        (() => {
            const storageDriver = @json(config('laravel-popup.close_state.driver', 'localStorage'));
            const cookieName = @json(config('laravel-popup.close_state.cookie_name', 'laravel_popup_closed'));

            const todayKey = () => new Date().toISOString().slice(0, 10);
            const expiresAt = (policy, duration) => {
                const now = new Date();

                if (policy === 'hide_today') {
                    const end = new Date(now);
                    end.setHours(23, 59, 59, 999);

                    return end.getTime();
                }

                if (policy === 'hide_period' && duration) {
                    return now.getTime() + (Number(duration) * 60 * 60 * 1000);
                }

                return now.getTime();
            };

            const readCookieState = () => {
                const match = document.cookie.match(new RegExp('(?:^|; )' + cookieName.replace(/[.$?*|{}()[\]\\/+^]/g, '\\$&') + '=([^;]*)'));

                if (! match) {
                    return {};
                }

                try {
                    return JSON.parse(decodeURIComponent(match[1]));
                } catch (error) {
                    return {};
                }
            };

            const writeCookieState = (state) => {
                const expires = new Date(Date.now() + (Number(@json(config('laravel-popup.close_state.cookie_minutes', 1440))) * 60 * 1000)).toUTCString();
                document.cookie = cookieName + '=' + encodeURIComponent(JSON.stringify(state)) + '; expires=' + expires + '; path=/; samesite=lax';
            };

            const getState = () => {
                if (storageDriver === 'cookie') {
                    return readCookieState();
                }

                try {
                    return JSON.parse(localStorage.getItem(cookieName) || '{}');
                } catch (error) {
                    return {};
                }
            };

            const setState = (state) => {
                if (storageDriver === 'cookie') {
                    writeCookieState(state);
                    return;
                }

                localStorage.setItem(cookieName, JSON.stringify(state));
            };

            document.querySelectorAll('[data-laravel-popup]').forEach((item) => {
                const key = item.dataset.closeKey;
                const state = getState();
                const closedUntil = Number(state[key] || 0);

                if (closedUntil && closedUntil > Date.now()) {
                    item.remove();
                    return;
                }

                const close = () => item.remove();
                const hide = () => {
                    const next = getState();
                    next[key] = expiresAt(item.dataset.closePolicy, item.dataset.closeDuration);
                    next[key + '_date'] = todayKey();
                    setState(next);
                    close();
                };

                item.querySelectorAll('[data-laravel-popup-close]').forEach((button) => button.addEventListener('click', close));
                item.querySelectorAll('[data-laravel-popup-hide]').forEach((button) => button.addEventListener('click', hide));
            });
        })();
    </script>
@endif
