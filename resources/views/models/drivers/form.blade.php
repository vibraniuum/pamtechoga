<x-fab::layouts.page
    :title="$model?->name ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Drivers', 'url' => route('lego.pamtechoga.drivers.index')],
            ['title' => $model?->name ?: 'Untitled'],
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
                wire:model="model.name"
                label="Name"
                help="Name of the driver."
            />

            <x-fab::forms.input
                wire:model="model.phone"
                label="Phone"
                help="Phone number of the driver."
            />

            <x-fab::forms.input
                wire:model="model.email"
                label="Email"
                help="Email address of the driver. It can be left blank if doesn't exist."
            />

            <x-fab::forms.input
                wire:model="model.address"
                label="Address"
                help="Residential address of the driver"
            />

        </x-fab::layouts.panel>

        <x-fab::layouts.panel>

            <x-fab::forms.select
                wire:model="model.truck_id"
                label="Truck"
                help="This is the truck assigned to the driver, it can be ignored and assigned later."
            >
                <option value="0">-- Choose Truck (can be assigned later)</option>
                @foreach($this->allTrucks() as $truck)
                    <option value="{{ $truck->id }}"> {{ $truck->plate_number }} </option>
                @endforeach
            </x-fab::forms.select>

        </x-fab::layouts.panel>

        <x-slot name="aside">
            @include('pamtechoga::models.components.timestamp')
            <x-lego::media-panel :model="$model" />
            <div class="mt-8">
                @if($this->model->photo)
                    <x-fab::elements.button type="link" :url="$this->model->photo" target="_blank">Preview Photo</x-fab::elements.button>
                @endif
            </div>
        </x-slot>

    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
