@php
    use Carbon\Carbon;
@endphp
<x-fab::layouts.page
    :title="$model?->depot?->name ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Depot Pickups', 'url' => route('lego.pamtechoga.depot-pickups.index')],
            ['title' => $model?->depot?->name ?: 'Untitled'],
        ]"
    x-data=""
    x-on:keydown.meta.s.window.prevent="$wire.call('save')" {{-- For Mac --}}
    x-on:keydown.ctrl.s.window.prevent="$wire.call('save')" {{-- For PC  --}}
>
{{--    <x-slot name="actions">--}}
{{--        @include('lego::models._includes.forms.page-actions')--}}
{{--    </x-slot>--}}

    <x-lego::feedback.errors class="sh-mb-4" />

    <x-fab::layouts.main-with-aside>
        <x-fab::layouts.panel>

            <x-fab::forms.select
                wire:model="model.depot_order_id"
                label="Depot Order"
                help="This is the depot order."
                :disabled="(bool) $this->model->id"
            >
                <option value="0">-- Choose Depot Order --</option>
                @foreach($this->allDepotOrders() as $data)
                    <option value="{{ $data->id }}"> {{ Carbon::parse($data->order_date)->toFormattedDateString() }} - {{ $data->depot->name }} - {{ number_format($data->volume) }}(LITRES) </option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.date-picker
                wire:model="model.loaded_datetime"
                label="Date Loaded"
                help="This is the datetime product arrived at the garage."
                :options="[
                    'dateFormat' => 'Y-m-d H:i',
                    'altInput' => true,
                    'altFormat' => 'D, M J, Y | G:i K',
                    'enableTime' => true,
                    'maxDate' => Carbon::now()->format('Y-m-d')
                ]"
            />

        </x-fab::layouts.panel>

        <x-fab::layouts.panel>

            <x-fab::forms.select
                wire:model="model.driver_id"
                label="Driver"
                help="This is the assigned driver to pickup the product."
                :disabled="(bool) $this->model->id"
            >
                <option value="0">-- Choose Truck (can be assigned later)</option>
                @foreach($this->allDrivers() as $data)
                    <option value="{{ $data->id }}"> {{ $data->name }} ({{ $data->nickname ?? '' }}) </option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.input
                wire:model="model.volume_assigned"
                label="Volume Assigned"
                help="This is the volume assigned to the driver to pickup."
                :disabled="(bool) $this->model->id"
            />

        </x-fab::layouts.panel>

        <x-slot name="aside">
            <x-fab::forms.input
                wire:model="model.status"
                label="Status"
                help="This the status of this pickup."
                :disabled="(bool) $this->model->id"
            />

                @include('pamtechoga::models.components.timestamp')
        </x-slot>

    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
