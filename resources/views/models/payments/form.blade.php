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
        @if($this->model->status === 'PENDING')
            @include('lego::models._includes.forms.page-actions')
        @endif
    </x-slot>
    <x-lego::feedback.errors class="sh-mb-4" />

    <x-fab::layouts.main-with-aside>
        <x-fab::layouts.panel>

            <x-fab::forms.select
                wire:model="model.organization_id"
                label="Organizations Order"
                help="This is the organization this payment is for."
                :disabled="$model->id ? true : false"
            >
                <option value="0">-- Choose Organization --</option>
                @foreach($this->allOrganizations() as $data)
                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
            </x-fab::forms.select>

        </x-fab::layouts.panel>

        <x-fab::layouts.panel>
            <x-fab::forms.input
                wire:model="model.amount"
                label="Amount (NGN)"
                help="Total value of this payment."
                :disabled="$model->id ? true : false"
            />

            <x-fab::forms.date-picker
                wire:model="model.payment_date"
                label="Payment Date"
                help="This is the date payment was made."
                :options="[
                    'dateFormat' => 'Y-m-d H:i',
                    'altInput' => true,
                    'altFormat' => 'D, M J, Y | G:i K',
                    'enableTime' => true,
                ]"
            />

            <x-fab::forms.textarea
                wire:model="model.reference_description"
                label="Reference Description"
                help="Any reference note attached to this payment."
            />
        </x-fab::layouts.panel>

        <x-slot name="aside">
{{--            <x-fab::forms.select--}}
{{--                wire:model="model.status"--}}
{{--                label="Payment Status"--}}
{{--                help="This is the current status of this payment."--}}
{{--                :disabled="true"--}}
{{--            >--}}
{{--                <option value="PENDING">-- Choose Status</option>--}}
{{--                <option value="PENDING">PENDING</option>--}}
{{--                <option value="CONFIRMED">CONFIRMED</option>--}}
{{--                <option value="CANCELED">CANCELED</option>--}}
{{--            </x-fab::forms.select>--}}

            @if($this->model->id)
                <x-fab::forms.input
                    wire:model="model.status"
                    label="Payment Status"
                    help="This is the current status of this payment."
                    disabled
                />
            @endif

            <div class="mt-4">
                @if($this->model->status === 'PENDING')
                    <x-fab::elements.button type="button" wire:click="markAsConfirmed">Mark as CONFIRMED</x-fab::elements.button>
                @endif

                @if($this->model->status === 'CONFIRMED')
                    <x-fab::elements.button type="button" wire:click="markAsRefunded">Mark as REFUNDED</x-fab::elements.button>
                @endif
            </div>

{{--            <x-fab::forms.select--}}
{{--                wire:model="model.type"--}}
{{--                label="Payment Type"--}}
{{--                help="This is the current status of this pickup."--}}
{{--                disabled--}}
{{--            >--}}
{{--                <option value="OTHER">-- Choose Status</option>--}}
{{--                <option value="DOWN PAYMENT">DOWN PAYMENT</option>--}}
{{--                <option value="DEBT">DEBT</option>--}}
{{--            </x-fab::forms.select>--}}

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
