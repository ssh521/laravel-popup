@props([
    'id',
    'name',
    'value' => '',
    'rows' => 12,
    'placeholder' => '본문 HTML을 입력하세요.',
])

@php
    $editorId = $id.'-editor';
    $sourceId = $id.'-source';
@endphp

<div
    {{ $attributes->merge(['class' => 'mt-2 overflow-hidden rounded-md border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-900']) }}
    x-data="{
        fieldName: @js($name),
        value: @js((string) $value),
        sourceMode: false,
        init() {
            this.syncToEditor();
        },
        syncFromEditor() {
            this.value = this.$refs.editor.innerHTML;
        },
        syncToEditor() {
            if (this.$refs.editor && this.$refs.editor.innerHTML !== (this.value || '')) {
                this.$refs.editor.innerHTML = this.value || '';
            }
        },
        command(command, argument = null) {
            this.sourceMode = false;
            this.$nextTick(() => {
                this.$refs.editor.focus();
                document.execCommand(command, false, argument);
                this.syncFromEditor();
            });
        },
        setBlock(tag) {
            this.command('formatBlock', tag);
        },
        createLink() {
            const url = window.prompt('링크 URL을 입력하세요.');

            if (url) {
                this.command('createLink', url);
            }
        },
        toggleSource() {
            if (! this.sourceMode) {
                this.syncFromEditor();
                this.sourceMode = true;
                return;
            }

            this.sourceMode = false;
            this.$nextTick(() => this.syncToEditor());
        },
    }"
    x-on:laravel-popup:fill-html-editor.window="
        if ($event.detail.name === fieldName) {
            value = $event.detail.value || '';
            sourceMode = false;
            $nextTick(() => syncToEditor());
        }
    "
>
    <div class="flex flex-wrap items-center gap-1 border-b border-gray-200 bg-gray-50 p-2 dark:border-gray-700 dark:bg-gray-800/80">
        <button type="button" class="inline-flex h-8 min-w-8 items-center justify-center rounded-md px-2 text-sm font-semibold text-gray-700 hover:bg-white dark:text-gray-200 dark:hover:bg-gray-700" x-on:click="command('bold')" title="굵게">B</button>
        <button type="button" class="inline-flex h-8 min-w-8 items-center justify-center rounded-md px-2 text-sm italic text-gray-700 hover:bg-white dark:text-gray-200 dark:hover:bg-gray-700" x-on:click="command('italic')" title="기울임">I</button>
        <button type="button" class="inline-flex h-8 min-w-8 items-center justify-center rounded-md px-2 text-sm underline text-gray-700 hover:bg-white dark:text-gray-200 dark:hover:bg-gray-700" x-on:click="command('underline')" title="밑줄">U</button>
        <span class="mx-1 h-5 w-px bg-gray-200 dark:bg-gray-700"></span>
        <button type="button" class="inline-flex h-8 items-center justify-center rounded-md px-2 text-sm font-medium text-gray-700 hover:bg-white dark:text-gray-200 dark:hover:bg-gray-700" x-on:click="setBlock('p')">문단</button>
        <button type="button" class="inline-flex h-8 items-center justify-center rounded-md px-2 text-sm font-medium text-gray-700 hover:bg-white dark:text-gray-200 dark:hover:bg-gray-700" x-on:click="setBlock('h2')">제목</button>
        <button type="button" class="inline-flex h-8 items-center justify-center rounded-md px-2 text-sm font-medium text-gray-700 hover:bg-white dark:text-gray-200 dark:hover:bg-gray-700" x-on:click="command('insertUnorderedList')">목록</button>
        <button type="button" class="inline-flex h-8 items-center justify-center rounded-md px-2 text-sm font-medium text-gray-700 hover:bg-white dark:text-gray-200 dark:hover:bg-gray-700" x-on:click="createLink()">링크</button>
        <span class="mx-1 h-5 w-px bg-gray-200 dark:bg-gray-700"></span>
        <button type="button" class="inline-flex h-8 items-center justify-center rounded-md px-2 text-sm font-medium text-gray-700 hover:bg-white dark:text-gray-200 dark:hover:bg-gray-700" x-on:click="toggleSource()" x-text="sourceMode ? '에디터' : 'HTML'"></button>
    </div>

    <textarea name="{{ $name }}" x-model="value" class="hidden" tabindex="-1" aria-hidden="true"></textarea>

    <div
        id="{{ $editorId }}"
        x-ref="editor"
        x-show="!sourceMode"
        x-on:input="syncFromEditor()"
        contenteditable="true"
        role="textbox"
        aria-multiline="true"
        aria-label="{{ $placeholder }}"
        data-placeholder="{{ $placeholder }}"
        class="min-h-[28rem] w-full resize-y overflow-auto px-3 py-3 text-sm leading-6 text-gray-900 outline-none empty:before:text-gray-400 empty:before:content-[attr(data-placeholder)] dark:text-white"
    ></div>

    <textarea
        id="{{ $sourceId }}"
        x-show="sourceMode"
        x-model="value"
        rows="{{ $rows }}"
        spellcheck="false"
        class="block min-h-[28rem] w-full resize-y border-0 bg-white px-3 py-3 font-mono text-xs leading-6 text-gray-900 outline-none focus:ring-0 dark:bg-gray-900 dark:text-white"
        placeholder="<p>HTML 소스를 입력하세요</p>"
    ></textarea>
</div>
