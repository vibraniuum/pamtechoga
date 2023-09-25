<x-fab::layouts.page
    :title="$model?->organization?->name ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Payments', 'url' => route('lego.pamtechoga.payments.index')],
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
                wire:model="model.customer_order_id"
                label="Organizations Order"
                help="This is the order from an organization."
            >
                <option value="0">-- Choose Organization Order --</option>
                @foreach($this->allCustomerOrders() as $data)
                    <option value="{{ $data->id }}"> {{ $data->id }} - {{ $data->organization->name }} - {{ $data->volume }}(LITRES) - {{ $data->created_at->toFormattedDateString() }} </option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.select
                wire:model="model.organization_id"
                label="Organization"
                help="This is the organization making payment."
            >
                <option value="0">-- Choose Organization Order --</option>
                @foreach($this->allOrganizations() as $data)
                    <option value="{{ $data->id }}"> {{ $data->id }} - {{ $data->name }} </option>
                @endforeach
            </x-fab::forms.select>

            <x-fab::forms.select
                wire:model="model.depot_order_id"
                label="Depot Order"
                help="This is the order placed at the depot."
            >
                <option value="0">-- Choose Depot Order --</option>
                @foreach($this->allDepotOrders() as $data)
                    <option value="{{ $data->id }}"> {{ $data->id }} - {{ $data->depot->name }} - {{ $data->volume }}(LITRES) - {{ $data->created_at->toFormattedDateString() }} </option>
                @endforeach
            </x-fab::forms.select>

        </x-fab::layouts.panel>

        <x-fab::layouts.panel>
            <x-fab::forms.input
                wire:model="model.amount"
                label="Amount (NGN)"
                help="Total value of this payment."
            />

            <x-fab::forms.date-picker
                wire:model="model.payment_date"
                label="Payment Date"
                help="This is the date payment was made."
                :options="[
                    'dateFormat' => 'Y-m-d H:i',
                    'altInput' => true,
                    'altFormat' => 'D, M J, Y | G:i K',
                    'enableTime' => true
                ]"
            />

            <x-fab::forms.textarea
                wire:model="model.reference_description"
                label="Reference Description"
                help="Any reference note attached to this payment."
            />
        </x-fab::layouts.panel>

        <x-slot name="aside">
            <x-fab::forms.select
                wire:model="model.status"
                label="Payment Status"
                help="This is the current status of this payment."
            >
                <option value="PENDING">-- Choose Status</option>
                <option value="PENDING">PENDING</option>
                <option value="CONFIRMED">CONFIRMED</option>
                <option value="CANCELED">CANCELED</option>
            </x-fab::forms.select>

            <x-fab::forms.select
                wire:model="model.type"
                label="Payment Type"
                help="This is the current status of this pickup."
            >
                <option value="OTHER">-- Choose Status</option>
                <option value="DOWN PAYMENT">DOWN PAYMENT</option>
                <option value="DEBT">DEBT</option>
                <option value="DEPOT">DEPOT</option>
            </x-fab::forms.select>

            @include('pamtechoga::models.components.timestamp')

            <div class="mt-8">
                @if($this->model->reference_photo)
                    <x-fab::elements.button type="link" :url="$this->model->reference_photo" target="_blank">Preview proof of payment</x-fab::elements.button>
                @endif
            </div>
        </x-slot>

    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
