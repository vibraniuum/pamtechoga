<x-fab::layouts.page
    :title="$model?->organization?->name ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Sales', 'url' => route('lego.pamtechoga.sales.index')],
            ['title' => $model?->organization?->name ?: 'Untitled'],
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
                wire:model="model.product_id"
                label="Product"
                help="This is the product ordered."
                disabled="true"
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
                disabled="true"
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
                disabled="true"
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
                disabled="true"
            />

            <x-fab::forms.input
                wire:model="model.unit_price"
                label="Price per litre"
                help="This is automatically set from the selected product's market price but can be edited after negotiations."
                disabled="true"
            />

            <x-fab::forms.checkbox
                wire:model="model.made_down_payment"
                label="Was there a down payment?"
                help="Check this if there is an associated down payment. Payment details can be added after the order is created."
                disabled="true"
            />

        </x-fab::layouts.panel>

        <x-fab::layouts.panel>

            <x-fab::forms.select
                wire:model="model.driver_id"
                label="Driver"
                help="This is the assigned driver to deliver the order. It can be ignored and assigned later."
                disabled="true"
            >
                <option value="0">-- Choose Truck (can be assigned later)</option>
                @foreach($this->allDrivers() as $data)
                    <option value="{{ $data->id }}"> {{ $data->name }} </option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.input
                wire:model="model.trucking_expense"
                label="Trucking Expense (NGN)"
                help="This is the cost per litre to transport the order volume."
                disabled="true"
            />

        </x-fab::layouts.panel>

        <x-slot name="aside">
            <x-fab::forms.select
                wire:model="model.status"
                label="Status"
                help="This is the current status of this order."
            >
                <option value="PENDING">-- Choose Status</option>
                <option value="PENDING">PENDING</option>
                <option value="PROCESSING">PROCESSING</option>
                <option value="DISPATCHED">DISPATCHED</option>
                <option value="DELIVERED">DELIVERED</option>
                <option value="CANCELED">CANCELED</option>
            </x-fab::forms.select>

                @include('pamtechoga::models.components.timestamp')
        </x-slot>

    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
