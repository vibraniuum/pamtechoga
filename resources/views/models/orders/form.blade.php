@php
    use Carbon\Carbon;
@endphp
<x-fab::layouts.page
    :title="$model?->organization?->name ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Orders', 'url' => route('lego.pamtechoga.orders.index')],
            ['title' => $model?->organization?->name ?: 'Untitled'],
        ]"
    x-data=""
    x-on:keydown.meta.s.window.prevent="$wire.call('save')" {{-- For Mac --}}
    x-on:keydown.ctrl.s.window.prevent="$wire.call('save')" {{-- For PC  --}}
>
    <x-slot name="actions">
        @include('lego::models._includes.forms.page-actions')
    </x-slot>

    <div>
        <dl class=" grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Profit</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">NGN{{ $this->calculateValue('profit') }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Cost Price</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">NGN{{ $this->calculateValue('costPrice') }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Selling Price</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">NGN{{ $this->calculateValue('sellingPrice') }}</dd>
            </div>

            @php
                $orderDebt = \Vibraniuum\Pamtechoga\Models\OrderDebt::where('order_id', $this->model->id)->first();
            @endphp
            @if($this->model->id && $orderDebt)
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Debt Owed on this Order</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">NGN{{ number_format($orderDebt->balance) }}</dd>
                </div>
            @endif
        </dl>
    </div>
    <div class="mt-5"></div>

    <x-lego::feedback.errors class="sh-mb-4" />

    <x-fab::layouts.main-with-aside>
        <x-fab::layouts.panel>

            <x-fab::forms.date-picker
                wire:model="model.order_date"
                label="Date of Order"
                help="This is the date this order was placed."
                :options="[
                    'dateFormat' => 'Y-m-d',
                    'altInput' => true,
                    'altFormat' => 'D, M J, Y',
                    'enableTime' => false,
                    'maxDate' => Carbon::now()->format('Y-m-d')
                ]"
            />

            <x-fab::forms.select
                wire:model="model.depot_order_id"
                label="Depot Order"
                help="This is the product price to fulfill this order."
                :disabled="$model->depot_order_id ? true : false"
                wire:change="setProduct"
            >
                <option value="0">-- Choose Depot Order --</option>
                @foreach($this->allDepotOrders() as $data)
                    <option value="{{ $data->id }}"> {{ $data->status }} | {{ Carbon::parse($data->order_date)->toFormattedDateString() }} - {{ $data->product->type }} - {{ $data->depot->name }} - {{ number_format($data->volume) }}(LITRES - NGN{{ number_format($data->unit_price) }} / LITRE - Trucking EXP: NGN{{ number_format($data->trucking_expense) }}) | Balance: {{ number_format($this->balance($data->id)) }} LITRES</option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.select
                wire:model="model.depot_pickup_id"
                label="Driver/Pickup to Fulfill the Order"
                help="This is the driver and pickup from the depot."
                :disabled="$model->depot_pickup_id ? true : false"
                wire:change="setDriver"
            >
                <option value="0">-- Choose Depot Pickup --</option>
                @foreach($this->allDepotPickups as $data)
                    <option
                        value="{{ $data->id }}"
                        {{ $this->isAssignedToAnotherOrder($data->id) ? 'disabled' : ''}}
                    > - {{ Carbon::parse($data->date_loaded)->toFormattedDateString() }} - {{ $data->driver?->name }} ({{ $data->driver?->nickname }}) - Balance: {{ number_format($data->volume_balance) }} Litres {{ $this->isAssignedToAnotherOrder($data->id) }}</option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.select
                wire:model="model.product_id"
                label="Product"
                help="This is the product being ordered."
                :disabled="$model->product_id ? true : false"
            >
                <option value="0">-- Choose the Product</option>
                @foreach($this->allProducts() as $data)
                    <option value="{{ $data->id }}"> {{ $data->type }} </option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.select
                wire:model="model.organization_id"
                label="Organization"
                help="This is the organization placing the order or for which this order is being recorded."
                :disabled="$model->organization_id ? true : false"
            >
                <option value="0">-- Choose the Organization</option>
                @foreach($this->allOrganizations() as $data)
                    <option value="{{ $data->id }}"> {{ $data->name }} </option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.select
                wire:model="model.branch_id"
                label="Branch"
                help="This is the selected organization's branch for delivery."
                :disabled="$model->branch_id ? true : false"
            >
                <option value="0">-- Choose branch --</option>
                @foreach($this->branchesOfSelectedOrganization() as $data)
                    <option value="{{ $data->id }}"> {{ $data->address }} </option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.input
                wire:model="model.volume"
                label="Volume (Litre)"
                help="This is the volume of selected product to be delivered."
                :disabled="$model->status != 'PENDING' ? true : false"
            />

            <x-fab::forms.input
                wire:model="model.unit_price"
                label="Price per litre (NGN)"
                help="This is automatically set from the selected product's market price but can be edited after negotiations."
                :disabled="$model->status != 'PENDING' ? true : false"
            />

            <x-fab::forms.date-picker
                wire:model="model.payment_deadline"
                label="Payment Deadline Date"
                help="Complete payment should be made on or before this date."
                :options="[
                    'dateFormat' => 'Y-m-d H:i',
                    'altInput' => true,
                    'altFormat' => 'D, M J, Y | G:i K',
                    'enableTime' => true
                ]"
            />

{{--            <x-fab::forms.checkbox--}}
{{--                wire:model="model.made_down_payment"--}}
{{--                label="Was there a down payment?"--}}
{{--                help="Check this if there is an associated down payment. Payment details can be added after the order is created."--}}
{{--            />--}}

        </x-fab::layouts.panel>

        <x-fab::layouts.panel>

            <x-fab::forms.input
                wire:model="model.driver_id"
                label="Driver"
                help="This is the assigned driver to deliver the order. You can change the driver by changing the depot pickup order."
                :disabled="true"
            />

        </x-fab::layouts.panel>

        <x-slot name="aside">
{{--            <x-fab::forms.select--}}
{{--                wire:model="model.status"--}}
{{--                label="Status"--}}
{{--                help="This is the current status of this order."--}}
{{--            >--}}
{{--                <option value="PENDING">-- Choose Status</option>--}}
{{--                <option value="PENDING">PENDING</option>--}}
{{--                <option value="PROCESSING">PROCESSING</option>--}}
{{--                <option value="DISPATCHED">DISPATCHED</option>--}}
{{--                <option value="DELIVERED">DELIVERED</option>--}}
{{--                <option value="CANCELED">CANCELED</option>--}}
{{--            </x-fab::forms.select>--}}
            @if($this->model->id)
                <x-fab::forms.input
                    wire:model="model.status"
                    label="Order Status"
                    help="This is the current status of this order."
                    disabled
                />
            @endif

            <div class="mt-4">
                @if($this->model->status === 'PENDING' && $this->model->id)
                    <x-fab::elements.button type="button" wire:click="markAsProcessing">Mark as PROCESSING</x-fab::elements.button>
                @endif

                @if($this->model->status === 'PROCESSING' && $this->model->id)
                    <x-fab::elements.button type="button" wire:click="markAsDispatched">Mark as DISPATCHED</x-fab::elements.button>
                    <x-fab::elements.button type="button" wire:click="markAsDelivered">Mark as DELIVERED</x-fab::elements.button>
                @endif

                @if($this->model->status === 'DISPATCHED' && $this->model->id && $this->model->depot_pickup_id)
                    <x-fab::elements.button type="button" wire:click="markAsDelivered">Mark as DELIVERED</x-fab::elements.button>
                @endif
            </div>

            @include('pamtechoga::models.components.timestamp')
        </x-slot>

    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
