@php
    use Carbon\Carbon;
@endphp
<x-fab::layouts.page
    :title="$model?->depot?->name ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Depot Orders', 'url' => route('lego.pamtechoga.depot-orders.index')],
            ['title' => $model?->depot?->name ?: 'Untitled'],
        ]"
    x-data=""
    x-on:keydown.meta.s.window.prevent="$wire.call('save')" {{-- For Mac --}}
    x-on:keydown.ctrl.s.window.prevent="$wire.call('save')" {{-- For PC  --}}
>
{{--    <x-slot name="actions">--}}
{{--        @include('lego::models._includes.forms.page-actions')--}}
{{--    </x-slot>--}}

    @if($this->model->id)
        <div>
            <dl class=" grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Unloaded Volume (Litres)</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($this->unloadedVolume()) }}</dd>
                </div>

                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Loaded Volume (Litres)</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($this->loadedVolume()) }}</dd>
                </div>
            </dl>
        </div>
        <div class="mt-5"></div>
    @endif

    <x-lego::feedback.errors class="sh-mb-4"/>

    <x-fab::layouts.main-with-aside>
        <x-fab::layouts.panel>

            <x-fab::forms.date-picker
                wire:model="model.order_date"
                label="Order Date"
                help="This is the date this order was placed."
                :options="[
                    'dateFormat' => 'Y-m-d H:i',
                    'altInput' => true,
                    'altFormat' => 'D, M J, Y | G:i K',
                    'enableTime' => true,
                    'maxDate' => Carbon::now()->format('Y-m-d')
                ]"
            />

            <x-fab::forms.select
                wire:model="model.product_id"
                label="Product"
                help="This is the product being ordered."
                :disabled="(bool) $this->model->id"
            >
                <option value="0">-- Choose the Product</option>
                @foreach($this->allProducts() as $data)
                    <option value="{{ $data->id }}"> {{ $data->type }} </option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.select
                wire:model="model.depot_id"
                label="Depot"
                help="This is the depot the product is purchased from."
                :disabled="(bool) $this->model->id"
            >
                <option value="0">-- Choose the Organization</option>
                @foreach($this->allDepots() as $data)
                    <option value="{{ $data->id }}"> {{ $data->name }} </option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.input
                wire:model="formattedVolume"
                label="Volume (Litre)"
                help="This is the volume of selected product to be loaded."
                :disabled="(bool) $this->model->id"
                x-data
                x-on:input="isNaN(parseFloat($event.target.value.replace(/,/g, ''))) ? $event.target.value = 0 : $event.target.value = parseFloat($event.target.value.replace(/,/g, '')).toLocaleString('en-US')"
            />

            <x-fab::forms.input
                wire:model="formattedUnitPrice"
                label="Price per litre"
                help="This is currently automatically set from the selected product's market price but can be edited after negotiations."
                :disabled="(bool) $this->model->id"
                x-data
                x-on:input="isNaN(parseFloat($event.target.value.replace(/,/g, ''))) ? $event.target.value = 0 : $event.target.value = parseFloat($event.target.value.replace(/,/g, '')).toLocaleString('en-US')"
            />

            <x-fab::forms.input
                wire:model="model.trucking_expense"
                label="Trucking Expense (NGN)"
                help="This is the cost per litre to transport the order volume."
                :disabled="(bool) $this->model->id"
            />

        </x-fab::layouts.panel>

        <x-fab::layouts.panel
            title="Pickups"
            description="Below are the pickups scheduled for this depot order."
            class="sh-mt-4"
        >
            <x-fab::lists.stacked
            >
                @foreach($this->model->depotPickup as $data)
                    <div
                        x-sortable.products.item="{{ $data->id }}"
                    >
                        <x-fab::lists.stacked.grouped-with-actions
                            :title="$data?->driver?->name . '(' . $data?->driver?->nickname . ')'"
                            description="{{ $data->updated_at->toFormattedDateString() }} | {{ $data->status }}"
                        >
                            <x-slot name="avatar">
                                <div class="flex">
                                    <x-fab::elements.icon icon="dots-vertical" x-sortable.products.handle
                                                          class="sh-h-5 sh-w-5 sh-text-gray-300 sh--mr-2"/>
                                    <x-fab::elements.icon icon="dots-vertical" x-sortable.products.handle
                                                          class="sh-h-5 sh-w-5 sh-text-gray-300 sh--ml-1.5"/>
                                </div>
                            </x-slot>
                            <x-slot name="actions">
                                <x-fab::elements.button
                                    size="xs"
                                    type="link"
                                    :url="route('lego.pamtechoga.depot-pickups.edit', $data)"
                                >
                                    View
                                </x-fab::elements.button>
                            </x-slot>
                        </x-fab::lists.stacked.grouped-with-actions>
                    </div>
                @endforeach
            </x-fab::lists.stacked>
        </x-fab::layouts.panel>

        <x-slot name="aside">
            <x-fab::forms.input
                wire:model="model.status"
                label="Status"
                help="This is the current status of this depot order."
                disabled
            />

            @include('pamtechoga::models.components.timestamp')
        </x-slot>

    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
