<x-fab::layouts.page
    :title="$model?->type ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Products', 'url' => route('lego.pamtechoga.products.index')],
            ['title' => $model?->type ?: 'Untitled'],
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
                wire:model="model.type"
                label="Product Type"
                help="This would also display on the mobile application. e.g PMS, AGO, etc."
            />

            <x-fab::forms.input
                wire:model="model.market_price"
                label="Market Price (NGN)"
                help="Current market price for this product (in Nigerian Naira)."
            />

            <x-fab::forms.checkbox
                wire:model="model.instock"
                label="Instock"
                help="Uncheck this if product is out-of-stock."
            />

        </x-fab::layouts.panel>
        <x-slot name="aside">
            @include('pamtechoga::models.components.timestamp')
        </x-slot>
    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
