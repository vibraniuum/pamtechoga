<x-fab::layouts.page
    :title="$model?->driver?->name ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Old Driver Trips', 'url' => route('lego.pamtechoga.old-driver-trips.index')],
            ['title' => $model?->driver?->name ?: 'Untitled'],
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

            <x-fab::forms.select
                wire:model="model.driver_id"
                help="This is the driver for which old trips would be set."
                >
                <option>-- choose Driver --</option>
                @foreach($this->allDrivers() as $data)
                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.input
                wire:model="model.number_of_trips"
                label="Number of Trips"
                help="Total past trips embarked on by the selected driver."
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
