@php
    $labelClass = 'block text-sm font-medium text-gray-900 dark:text-white';
    $errorClass = 'mt-1 text-sm text-red-600 dark:text-red-400';
    $submitLabel = $submitLabel ?? '저장하기';
    $showSampleButton = $showSampleButton ?? false;
    $showActions = $showActions ?? true;
    $settings = $popup->settings ?? [];
    $sampleBody = <<<'HTML'
<h2>여름 프로모션 안내</h2>
<p>기간 한정 혜택을 확인하고 원하는 상품을 더 좋은 조건으로 만나보세요.</p>
<ul>
    <li>신규 회원 쿠폰 지급</li>
    <li>주요 상품 무료 배송</li>
    <li>7일 동안만 제공되는 특별 가격</li>
</ul>
<p><a href="/event/summer">이벤트 바로가기</a></p>
HTML;
@endphp

<div
    class="mx-auto grid max-w-4xl grid-cols-1 gap-x-8 text-gray-900 md:grid-cols-12 dark:text-gray-100"
    x-data="{
        sampleBody: @js($sampleBody),
        formatDateTime(date) {
            const pad = value => String(value).padStart(2, '0');

            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        },
        setField(name, value) {
            const form = this.$root.closest('form');
            const field = form?.elements[name];

            if (! field) {
                return;
            }

            field.value = value;
            field.dispatchEvent(new Event('input', { bubbles: true }));
            field.dispatchEvent(new Event('change', { bubbles: true }));
        },
        fillSample() {
            const form = this.$root.closest('form');
            const hasInput = form && Array.from(form.elements).some(field => {
                if (! field.name || ['_token', '_method', 'image_disk', 'link_rel'].includes(field.name)) {
                    return false;
                }

                return String(field.value || '').trim() !== '';
            });

            if (hasInput && ! window.confirm('현재 입력값을 예제로 교체할까요?')) {
                return;
            }

            const startsAt = new Date();
            startsAt.setHours(9, 0, 0, 0);

            const endsAt = new Date(startsAt);
            endsAt.setDate(endsAt.getDate() + 14);
            endsAt.setHours(23, 59, 0, 0);

            this.setField('title', '여름 프로모션 팝업');
            this.setField('description', '메인 페이지와 이벤트 페이지에 노출할 여름 프로모션 안내 팝업입니다.');
            this.setField('type', 'popup');
            this.setField('status', 'draft');
            this.setField('priority', '50');
            this.setField('display_title', '여름 한정 혜택을 확인하세요');
            this.setField('image_path', '/storage/popups/sample-summer-event.jpg');
            this.setField('image_alt', '여름 프로모션 대표 이미지');
            this.setField('link_url', '/event/summer');
            this.setField('link_target', '_self');
            this.setField('starts_at', this.formatDateTime(startsAt));
            this.setField('ends_at', this.formatDateTime(endsAt));
            this.setField('device', 'all');
            this.setField('position', 'center');
            this.setField('sort_order', '10');
            this.setField('include_paths', `/\n/event/*`);
            this.setField('exclude_paths', `/admin/*\n/login`);
            this.setField('close_policy', 'hide_today');
            this.setField('close_duration', '1');
            this.setField('width', '480px');
            this.setField('max_width', '92vw');
            this.setField('background_color', '#ffffff');
            this.setField('text_color', '#111827');
            this.setField('z_index', '1050');
            this.setField('body', this.sampleBody);
            window.dispatchEvent(new CustomEvent('laravel-popup:fill-html-editor', {
                detail: { name: 'body', value: this.sampleBody },
            }));
        },
    }"
>
    <div class="col-span-full h-6 sm:h-10"></div>

    <div class="md:col-span-4">
        <h2 class="text-base font-semibold">기본 정보</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">관리 제목, 표시 타입, 상태를 설정합니다.</p>
        @if($showSampleButton)
            <div class="mt-4">
                <x-laravel-admin::admin.action-button type="button" variant="secondary" icon="file-lines" x-on:click="fillSample()">
                    전체 예제 입력
                </x-laravel-admin::admin.action-button>
            </div>
        @endif
    </div>
    <div class="mt-6 space-y-5 md:col-span-8 md:mt-0">
        <div>
            <label class="{{ $labelClass }}" for="title">관리 제목</label>
            <x-laravel-admin::admin.form-input id="title" name="title" value="{{ old('title', $popup->title) }}" class="mt-2" required />
            @error('title')<p class="{{ $errorClass }}">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="{{ $labelClass }}" for="description">관리 메모</label>
            <x-laravel-admin::admin.form-textarea id="description" name="description" rows="2" class="mt-2">{{ old('description', $popup->description) }}</x-laravel-admin::admin.form-textarea>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="{{ $labelClass }}" for="type">타입</label>
                <x-laravel-admin::admin.form-select id="type" name="type" class="mt-2">
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" @selected(old('type', $popup->type) === $key)>{{ $label }}</option>
                    @endforeach
                </x-laravel-admin::admin.form-select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="status">상태</label>
                <x-laravel-admin::admin.form-select id="status" name="status" class="mt-2">
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(old('status', $popup->status) === $key)>{{ $label }}</option>
                    @endforeach
                </x-laravel-admin::admin.form-select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="priority">우선순위</label>
                <x-laravel-admin::admin.form-input id="priority" name="priority" type="number" value="{{ old('priority', $popup->priority ?? 0) }}" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="mt-8 mb-6 border-b border-gray-900/10 md:col-span-12 sm:my-10 dark:border-white/10"></div>

    <div class="md:col-span-4">
        <h2 class="text-base font-semibold">콘텐츠</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">방문자에게 표시할 제목, 본문, 이미지와 링크입니다.</p>
    </div>
    <div class="mt-6 space-y-5 md:col-span-8 md:mt-0">
        <div>
            <label class="{{ $labelClass }}" for="display_title">표시 제목</label>
            <x-laravel-admin::admin.form-input id="display_title" name="display_title" value="{{ old('display_title', $popup->display_title) }}" class="mt-2" />
        </div>
        <div>
            <label class="{{ $labelClass }}" for="body">본문 HTML</label>
            <x-laravel-popup::admin.html-editor
                id="body"
                name="body"
                rows="14"
                :value="old('body', $popup->body)"
                placeholder="팝업 본문 HTML을 입력하세요."
            />
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}" for="image_path">이미지 경로</label>
                <x-laravel-admin::admin.form-input id="image_path" name="image_path" value="{{ old('image_path', $popup->image_path) }}" class="mt-2" />
            </div>
            <div>
                <label class="{{ $labelClass }}" for="image_alt">이미지 대체 텍스트</label>
                <x-laravel-admin::admin.form-input id="image_alt" name="image_alt" value="{{ old('image_alt', $popup->image_alt) }}" class="mt-2" />
            </div>
        </div>
        <input type="hidden" name="image_disk" value="{{ old('image_disk', $popup->image_disk) }}">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="sm:col-span-2">
                <label class="{{ $labelClass }}" for="link_url">링크 URL</label>
                <x-laravel-admin::admin.form-input id="link_url" name="link_url" value="{{ old('link_url', $popup->link_url) }}" class="mt-2" />
            </div>
            <div>
                <label class="{{ $labelClass }}" for="link_target">링크 열기</label>
                <x-laravel-admin::admin.form-select id="link_target" name="link_target" class="mt-2">
                    <option value="_self" @selected(old('link_target', $popup->link_target) === '_self')>현재 창</option>
                    <option value="_blank" @selected(old('link_target', $popup->link_target) === '_blank')>새 창</option>
                </x-laravel-admin::admin.form-select>
            </div>
        </div>
        <input type="hidden" name="link_rel" value="{{ old('link_rel', $popup->link_rel) }}">
    </div>

    <div class="mt-8 mb-6 border-b border-gray-900/10 md:col-span-12 sm:my-10 dark:border-white/10"></div>

    <div class="md:col-span-4">
        <h2 class="text-base font-semibold">노출 조건</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">기간, 디바이스, 위치, 페이지 조건을 설정합니다.</p>
    </div>
    <div class="mt-6 space-y-5 md:col-span-8 md:mt-0">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}" for="starts_at">시작 시각</label>
                <x-laravel-admin::admin.form-input id="starts_at" name="starts_at" type="datetime-local" value="{{ old('starts_at', $popup->starts_at?->format('Y-m-d\\TH:i')) }}" class="mt-2" />
            </div>
            <div>
                <label class="{{ $labelClass }}" for="ends_at">종료 시각</label>
                <x-laravel-admin::admin.form-input id="ends_at" name="ends_at" type="datetime-local" value="{{ old('ends_at', $popup->ends_at?->format('Y-m-d\\TH:i')) }}" class="mt-2" />
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="{{ $labelClass }}" for="device">디바이스</label>
                <x-laravel-admin::admin.form-select id="device" name="device" class="mt-2">
                    @foreach($devices as $key => $label)
                        <option value="{{ $key }}" @selected(old('device', $popup->device) === $key)>{{ $label }}</option>
                    @endforeach
                </x-laravel-admin::admin.form-select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="position">위치</label>
                <x-laravel-admin::admin.form-select id="position" name="position" class="mt-2">
                    @foreach($positions as $key => $label)
                        <option value="{{ $key }}" @selected(old('position', $popup->position) === $key)>{{ $label }}</option>
                    @endforeach
                </x-laravel-admin::admin.form-select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="sort_order">정렬</label>
                <x-laravel-admin::admin.form-input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $popup->sort_order ?? 0) }}" class="mt-2" />
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}" for="include_paths">노출 path</label>
                <x-laravel-admin::admin.form-textarea id="include_paths" name="include_paths" rows="4" placeholder="/&#10;/products/*" class="mt-2">{{ old('include_paths', implode("\n", $popup->include_paths ?? [])) }}</x-laravel-admin::admin.form-textarea>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="exclude_paths">제외 path</label>
                <x-laravel-admin::admin.form-textarea id="exclude_paths" name="exclude_paths" rows="4" placeholder="/admin/*" class="mt-2">{{ old('exclude_paths', implode("\n", $popup->exclude_paths ?? [])) }}</x-laravel-admin::admin.form-textarea>
            </div>
        </div>
    </div>

    <div class="mt-8 mb-6 border-b border-gray-900/10 md:col-span-12 sm:my-10 dark:border-white/10"></div>

    <div class="md:col-span-4">
        <h2 class="text-base font-semibold">닫기와 표시</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">닫기 정책과 기본 스타일 값을 설정합니다.</p>
    </div>
    <div class="mt-6 space-y-5 md:col-span-8 md:mt-0">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}" for="close_policy">닫기 정책</label>
                <x-laravel-admin::admin.form-select id="close_policy" name="close_policy" class="mt-2">
                    @foreach($closePolicies as $key => $label)
                        <option value="{{ $key }}" @selected(old('close_policy', $popup->close_policy) === $key)>{{ $label }}</option>
                    @endforeach
                </x-laravel-admin::admin.form-select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="close_duration">기간 닫기 시간</label>
                <x-laravel-admin::admin.form-input id="close_duration" name="close_duration" type="number" min="1" value="{{ old('close_duration', $popup->close_duration) }}" class="mt-2" />
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
            <div>
                <label class="{{ $labelClass }}" for="width">Width</label>
                <x-laravel-admin::admin.form-input id="width" name="width" value="{{ old('width', $settings['width'] ?? '') }}" class="mt-2" />
            </div>
            <div>
                <label class="{{ $labelClass }}" for="max_width">Max width</label>
                <x-laravel-admin::admin.form-input id="max_width" name="max_width" value="{{ old('max_width', $settings['max_width'] ?? '') }}" class="mt-2" />
            </div>
            <div>
                <label class="{{ $labelClass }}" for="background_color">배경색</label>
                <x-laravel-admin::admin.form-input id="background_color" name="background_color" value="{{ old('background_color', $settings['background_color'] ?? '') }}" class="mt-2" />
            </div>
            <div>
                <label class="{{ $labelClass }}" for="text_color">글자색</label>
                <x-laravel-admin::admin.form-input id="text_color" name="text_color" value="{{ old('text_color', $settings['text_color'] ?? '') }}" class="mt-2" />
            </div>
            <div>
                <label class="{{ $labelClass }}" for="z_index">Z-index</label>
                <x-laravel-admin::admin.form-input id="z_index" name="z_index" type="number" min="0" value="{{ old('z_index', $settings['z_index'] ?? '') }}" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="mt-8 mb-6 border-b border-gray-900/10 md:col-span-12 sm:my-10 dark:border-white/10"></div>

    @if($showActions)
        <div class="col-span-full flex items-center justify-end gap-x-3">
            <x-laravel-admin::admin.action-button variant="secondary" :href="route('popup.admin.items.index')">
                취소
            </x-laravel-admin::admin.action-button>
            <x-laravel-admin::admin.action-button type="submit">
                {{ $submitLabel }}
            </x-laravel-admin::admin.action-button>
        </div>
    @endif
</div>
