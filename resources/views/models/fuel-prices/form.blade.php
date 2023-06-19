<x-fab::layouts.page
    :title="$model?->company_name ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Fuel Prices', 'url' => route('lego.pamtechoga.fuel-prices.index')],
            ['title' => $model?->company_name ?: 'Untitled'],
        ]"
    x-data=""
    x-on:keydown.meta.s.window.prevent="$wire.call('save')" {{-- For Mac --}}
    x-on:keydown.ctrl.s.window.prevent="$wire.call('save')" {{-- For PC  --}}
>
    <x-slot name="actions">
        @include('lego::models._includes.forms.page-actions')
    </x-slot>
    <x-lego::feedback.errors class="sh-mb-4" />

    <x-fab::layouts.main-with-aside>
        <x-fab::layouts.panel>

            <x-fab::forms.input
                wire:model="model.company_name"
                label="Company Name"
            />

            <x-fab::forms.input
                wire:model="model.petrol"
                label="Petrol Price (NGN)"
            />

            <x-fab::forms.input
                wire:model="model.diesel"
                label="Diesel Price (NGN)"
            />

            <x-fab::forms.input
                wire:model="model.premium"
                label="Premium Fuel Price (NGN)"
            />

            <x-fab::forms.input
                wire:model="model.logo"
                label="Logo url (will be added automatically on media upload)"
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
