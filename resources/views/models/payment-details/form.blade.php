<x-fab::layouts.page
    :title="$model?->bank ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Branches', 'url' => route('lego.pamtechoga.payment-details.index')],
            ['title' => $model?->bank ?: 'Untitled'],
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
                wire:model="model.account_name"
                label="Account Name"
            />

            <x-fab::forms.input
                wire:model="model.bank"
                label="Bank"
            />

            <x-fab::forms.input
                wire:model="model.account_number"
                label="Account Number"
            />

            <x-fab::forms.select
                wire:model="model.account_type"
                label="Account Type"
            >
               <option>-- Choose account --</option>
               <option value="CURRENT">CURRENT</option>
               <option value="SAVINGS">SAVINGS</option>
               <option value="INVESTMENT">INVESTMENT</option>
            </x-fab::forms.select>

        </x-fab::layouts.panel>

        <x-slot name="aside">
            @include('pamtechoga::models.components.timestamp')
        </x-slot>

    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
