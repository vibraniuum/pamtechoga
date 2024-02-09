@php
    use Carbon\Carbon;
@endphp
<x-fab::layouts.page
    title="Orders"
    :breadcrumbs="[
        ['title' => 'Home', 'url' => route('lego.dashboard')],
        ['title' => 'Orders','url' => route('lego.pamtechoga.orders.index')],
    ]"
    x-data="{ showColumnFilters: false }"
>
    <x-slot name="actions">
        <x-fab::elements.button type="link" :url="route('lego.pamtechoga.orders.create')">Create</x-fab::elements.button>
    </x-slot>

    <div>
        <livewire:pamtechoga-datefilter-form />
    </div>

    <div>
        <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Customer Orders Amount</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">₦{{ number_format($totalCustomerOrdersAmount) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Customer Orders</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalCustomerOrders) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Customer Orders Volume (Litres)</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalCustomerOrdersVolume) }}</dd>
            </div>

            @foreach($this->ordersBreakDownByProduct as $data)
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total {{ $data?->type ?? '' }} Orders Volume (Litres)</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($data?->total ?? 0) }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
    <div class="mt-4 flex justify-end">
        <x-fab::elements.button type="button" wire:click="exportAsCSV">Export data as CSV</x-fab::elements.button>
    </div>
    <div class="mt-8">
        @include('lego::models._includes.indexes.filters')
    </div>

    <x-fab::lists.table class="mt-8">
        <x-slot name="headers">
            @include('lego::models._includes.indexes.headers')
            <x-fab::lists.table.header :hidden="true">Edit</x-fab::lists.table.header>
        </x-slot>

        @include('lego::models._includes.indexes.header-filters')
        <x-fab::lists.table.header x-show="showColumnFilters" x-cloak class="bg-gray-100" />

{{--        @foreach($models->reject(function($item) { return $item->status === 'DELIVERED'; }) as $data)--}}
        @foreach($models as $data)
            <x-fab::lists.table.row :odd="$loop->odd">
                @if($this->shouldShowColumn('organization_name'))
                    <x-fab::lists.table.column primary full>
                        <a href="{{ route('lego.pamtechoga.orders.edit', $data) }}">{{ $data->organization->name }}</a>
                    </x-fab::lists.table.column>
                @endisset

                @if($this->shouldShowColumn('product'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.orders.edit', $data) }}">{{ $data?->product?->type ?? '' }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('volume'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.orders.edit', $data) }}">{{ number_format($data->volume ?? 0) }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('unit_price'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.orders.edit', $data) }}">{{ $data->unit_price }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('amount'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.orders.edit', $data) }}">{{ number_format($data->volume * $data->unit_price ?? 0) }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('status'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.orders.edit', $data) }}">{{ $data->status }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('order_date'))
                    <x-fab::lists.table.column align="right">
                        {{ Carbon::parse($data->order_date)->toFormattedDateString() }}
                    </x-fab::lists.table.column>
                @endisset

                <x-fab::lists.table.column align="right" slim>
                <a href="{{ route('lego.pamtechoga.orders.edit', $data) }}">Edit</a> •
                <a href="{{ route('lego.pamtechoga.organizations.organizationOrders', $data->organization) }}">Breakdown</a>
                </x-fab::lists.table.column>
            </x-fab::lists.table.row>
        @endforeach
    </x-fab::lists.table>

    @include('lego::models._includes.indexes.pagination')
</x-fab::layouts.page>
