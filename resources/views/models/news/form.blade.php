<x-fab::layouts.page
    :title="$model?->title ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'News', 'url' => route('lego.pamtechoga.news.index')],
            ['title' => $model?->title ?: 'Untitled'],
        ]"
    x-data=""
    x-on:keydown.meta.s.window.prevent="$wire.call('save')" {{-- For Mac --}}
    x-on:keydown.ctrl.s.window.prevent="$wire.call('save')" {{-- For PC  --}}
>
    <x-slot name="actions">
        <button wire:click="sendAnnouncement" class="flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto">
            Broadcast as Announcement
        </button>
        @include('lego::models._includes.forms.page-actions')
    </x-slot>
    <x-lego::feedback.errors class="sh-mb-4" />

    <x-fab::layouts.main-with-aside>
        <x-fab::layouts.panel>

            <x-fab::forms.input
                wire:model="model.title"
                label="Article Title"
            />

            <x-fab::forms.input
                wire:model="model.author"
                label="Article Author"
            />

            <x-fab::forms.editor
                wire:model="model.content"
                label="Article Content"
            />

            <x-fab::forms.input
                wire:model="model.image"
                label="Article Image url (will be added automatically on media upload)"
                disabled="true"
            />

        </x-fab::layouts.panel>

        <x-slot name="aside">
            @include('pamtechoga::models.components.timestamp')
            <x-lego::media-panel :model="$model" />
        </x-slot>

    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
