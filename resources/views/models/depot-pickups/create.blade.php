@php
    use Carbon\Carbon;
@endphp
<x-fab::layouts.page
    :title="'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Depot Pickups', 'url' => route('lego.pamtechoga.depot-pickups.index')],
            ['title' => 'Untitled'],
        ]"
    x-data=""
    x-on:keydown.meta.s.window.prevent="$wire.call('save')" {{-- For Mac --}}
    x-on:keydown.ctrl.s.window.prevent="$wire.call('save')" {{-- For PC  --}}
>

    @if(isset($this->model['depot_order_id']))
        <div>
            <dl class=" grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Unloaded Volume (Litres)</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($this->unloadedVolume()) }}</dd>
                </div>

                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">New Loaded Volume (Litres)</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($this->newloadedVolume()) }}</dd>
                </div>
            </dl>
        </div>
        <div class="mt-5"></div>
    @endif

    <x-lego::feedback.errors class="sh-mb-4" />

    <x-fab::layouts.main-with-aside>
        <x-fab::layouts.panel>

            <x-fab::forms.select
                wire:model="model.depot_order_id"
                label="Depot Order"
                help="This is the depot order."
            >
                <option value="0">-- Choose Depot Order --</option>
                @foreach($this->allDepotOrders() as $data)
                    <option value="{{ $data->id }}"> {{ $data->order_date }} | {{ $data->depot->name }} - {{ number_format($data->volume) }}(LITRES) | NGN{{ number_format($data->unit_price + $data->trucking_expense) }}/LITRE </option>
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

        <x-fab::layouts.panel title="Assign Drivers">

            @foreach($extraData as $data)

                <div class="grid grid-cols-5">
                    <div class="col-span-4">
                        <x-fab::forms.select
                            wire:model="extraData.{{ $loop->index }}.driver_id"
                            label="Driver {{ $loop->index + 1 }}."
                            help="This is the assigned driver to pickup the product."
                        >
                            <option value="0">-- Choose Truck (can be assigned later)</option>
                            @foreach($this->allDrivers($this->extraData[$loop->index]['driver_id']) as $data)
                                <option value="{{ $data->id }}"> {{ $data->name }} </option>
                            @endforeach
                        </x-fab::forms.select>

                        <x-fab::forms.input
                            wire:model="extraData.{{ $loop->index }}.volume_assigned"
                            wire:keyUp="checkThatVolumeIsNotOutOfRange({{ $loop->index }})"
                            label="Volume Assigned"
                            help="This is the volume assigned to the driver to pickup."
                            :disabled="$this->extraData[$loop->index]['driver_id'] == 0"
                        />
                        <div>
                            @if (session()->has('message'))
                                <div class="bg-pink-400">
                                    {{ session('message') }}
                                </div>
                            @endif
                        </div>
                    </div>
{{--                    <div class="mt-1 col-span-1">--}}
{{--                        <x-fab::elements.button wire:click="removeDataRow" type="button">Remove</x-fab::elements.button>--}}
{{--                    </div>--}}

                </div>

            @endforeach

            @if($this->model)
                @if(isset($this->model['depot_order_id']) && !$this->loadedVolumeIsOutOfRange())
                    <x-fab::elements.button wire:click="addExtraData" type="button" class="mt-2">Add New</x-fab::elements.button>
                @endif
            @endif
        </x-fab::layouts.panel>

        <x-slot name="aside">
        </x-slot>

    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
