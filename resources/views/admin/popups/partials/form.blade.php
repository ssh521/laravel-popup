@php
    $inputClass = 'mt-2 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-white';
    $labelClass = 'block text-sm font-medium text-gray-900 dark:text-white';
    $errorClass = 'mt-1 text-sm text-red-600 dark:text-red-400';
    $settings = $popup->settings ?? [];
@endphp

<div class="mx-auto grid max-w-5xl grid-cols-1 gap-x-8 text-gray-900 md:grid-cols-12 dark:text-gray-100">
    <div class="md:col-span-4">
        <h2 class="text-base font-semibold">기본 정보</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">관리 제목, 표시 타입, 상태를 설정합니다.</p>
    </div>
    <div class="mt-6 space-y-5 md:col-span-8 md:mt-0">
        <div>
            <label class="{{ $labelClass }}" for="title">관리 제목</label>
            <input id="title" name="title" value="{{ old('title', $popup->title) }}" class="{{ $inputClass }}" required>
            @error('title')<p class="{{ $errorClass }}">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="{{ $labelClass }}" for="description">관리 메모</label>
            <textarea id="description" name="description" rows="2" class="{{ $inputClass }}">{{ old('description', $popup->description) }}</textarea>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="{{ $labelClass }}" for="type">타입</label>
                <select id="type" name="type" class="{{ $inputClass }}">
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" @selected(old('type', $popup->type) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="status">상태</label>
                <select id="status" name="status" class="{{ $inputClass }}">
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(old('status', $popup->status) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="priority">우선순위</label>
                <input id="priority" name="priority" type="number" value="{{ old('priority', $popup->priority ?? 0) }}" class="{{ $inputClass }}">
            </div>
        </div>
    </div>

    <div class="my-10 border-b border-gray-900/10 md:col-span-12 dark:border-white/10"></div>

    <div class="md:col-span-4">
        <h2 class="text-base font-semibold">콘텐츠</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">방문자에게 표시할 제목, 본문, 이미지와 링크입니다.</p>
    </div>
    <div class="mt-6 space-y-5 md:col-span-8 md:mt-0">
        <div>
            <label class="{{ $labelClass }}" for="display_title">표시 제목</label>
            <input id="display_title" name="display_title" value="{{ old('display_title', $popup->display_title) }}" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="{{ $labelClass }}" for="body">본문 HTML</label>
            <textarea id="body" name="body" rows="8" class="{{ $inputClass }}">{{ old('body', $popup->body) }}</textarea>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}" for="image_path">이미지 경로</label>
                <input id="image_path" name="image_path" value="{{ old('image_path', $popup->image_path) }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="image_alt">이미지 대체 텍스트</label>
                <input id="image_alt" name="image_alt" value="{{ old('image_alt', $popup->image_alt) }}" class="{{ $inputClass }}">
            </div>
        </div>
        <input type="hidden" name="image_disk" value="{{ old('image_disk', $popup->image_disk) }}">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="sm:col-span-2">
                <label class="{{ $labelClass }}" for="link_url">링크 URL</label>
                <input id="link_url" name="link_url" value="{{ old('link_url', $popup->link_url) }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="link_target">링크 열기</label>
                <select id="link_target" name="link_target" class="{{ $inputClass }}">
                    <option value="_self" @selected(old('link_target', $popup->link_target) === '_self')>현재 창</option>
                    <option value="_blank" @selected(old('link_target', $popup->link_target) === '_blank')>새 창</option>
                </select>
            </div>
        </div>
        <input type="hidden" name="link_rel" value="{{ old('link_rel', $popup->link_rel) }}">
    </div>

    <div class="my-10 border-b border-gray-900/10 md:col-span-12 dark:border-white/10"></div>

    <div class="md:col-span-4">
        <h2 class="text-base font-semibold">노출 조건</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">기간, 디바이스, 위치, 페이지 조건을 설정합니다.</p>
    </div>
    <div class="mt-6 space-y-5 md:col-span-8 md:mt-0">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}" for="starts_at">시작 시각</label>
                <input id="starts_at" name="starts_at" type="datetime-local" value="{{ old('starts_at', $popup->starts_at?->format('Y-m-d\\TH:i')) }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="ends_at">종료 시각</label>
                <input id="ends_at" name="ends_at" type="datetime-local" value="{{ old('ends_at', $popup->ends_at?->format('Y-m-d\\TH:i')) }}" class="{{ $inputClass }}">
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="{{ $labelClass }}" for="device">디바이스</label>
                <select id="device" name="device" class="{{ $inputClass }}">
                    @foreach($devices as $key => $label)
                        <option value="{{ $key }}" @selected(old('device', $popup->device) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="position">위치</label>
                <select id="position" name="position" class="{{ $inputClass }}">
                    @foreach($positions as $key => $label)
                        <option value="{{ $key }}" @selected(old('position', $popup->position) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="sort_order">정렬</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $popup->sort_order ?? 0) }}" class="{{ $inputClass }}">
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}" for="include_paths">노출 path</label>
                <textarea id="include_paths" name="include_paths" rows="4" placeholder="/&#10;/products/*" class="{{ $inputClass }}">{{ old('include_paths', implode("\n", $popup->include_paths ?? [])) }}</textarea>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="exclude_paths">제외 path</label>
                <textarea id="exclude_paths" name="exclude_paths" rows="4" placeholder="/admin/*" class="{{ $inputClass }}">{{ old('exclude_paths', implode("\n", $popup->exclude_paths ?? [])) }}</textarea>
            </div>
        </div>
    </div>

    <div class="my-10 border-b border-gray-900/10 md:col-span-12 dark:border-white/10"></div>

    <div class="md:col-span-4">
        <h2 class="text-base font-semibold">닫기와 표시</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">닫기 정책과 기본 스타일 값을 설정합니다.</p>
    </div>
    <div class="mt-6 space-y-5 md:col-span-8 md:mt-0">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}" for="close_policy">닫기 정책</label>
                <select id="close_policy" name="close_policy" class="{{ $inputClass }}">
                    @foreach($closePolicies as $key => $label)
                        <option value="{{ $key }}" @selected(old('close_policy', $popup->close_policy) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="close_duration">기간 닫기 시간</label>
                <input id="close_duration" name="close_duration" type="number" min="1" value="{{ old('close_duration', $popup->close_duration) }}" class="{{ $inputClass }}">
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
            <div>
                <label class="{{ $labelClass }}" for="width">Width</label>
                <input id="width" name="width" value="{{ old('width', $settings['width'] ?? '') }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="max_width">Max width</label>
                <input id="max_width" name="max_width" value="{{ old('max_width', $settings['max_width'] ?? '') }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="background_color">배경색</label>
                <input id="background_color" name="background_color" value="{{ old('background_color', $settings['background_color'] ?? '') }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="text_color">글자색</label>
                <input id="text_color" name="text_color" value="{{ old('text_color', $settings['text_color'] ?? '') }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="z_index">Z-index</label>
                <input id="z_index" name="z_index" type="number" min="0" value="{{ old('z_index', $settings['z_index'] ?? '') }}" class="{{ $inputClass }}">
            </div>
        </div>
    </div>

    <div class="my-10 border-b border-gray-900/10 md:col-span-12 dark:border-white/10"></div>

    <div class="col-span-full flex items-center justify-end gap-x-3">
        <a href="{{ route('popup.admin.items.index') }}" class="inline-flex h-10 items-center rounded-md border border-gray-300 bg-white px-4 text-sm font-semibold !text-gray-700 shadow-sm hover:bg-gray-50 hover:no-underline dark:border-gray-600 dark:bg-gray-800 dark:!text-gray-100">취소</a>
        <button type="submit" class="inline-flex h-10 cursor-pointer items-center rounded-md bg-indigo-600 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">저장</button>
    </div>
</div>
