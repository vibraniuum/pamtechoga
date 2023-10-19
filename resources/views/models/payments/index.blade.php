
<x-fab::layouts.page
    title="Payments"
    :breadcrumbs="[
        ['title' => 'Home', 'url' => route('lego.dashboard')],
        ['title' => 'Payments','url' => route('lego.pamtechoga.payments.index')],
    ]"
    x-data="{ showColumnFilters: false }"
>
    <x-slot name="actions">
        <x-fab::elements.button type="link" :url="route('lego.pamtechoga.payments.create')">Create</x-fab::elements.button>
    </x-slot>

    <div>
        <livewire:pamtechoga-datefilter-form />
    </div>


    <div>
        <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Organizational Debts Amount</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">₦{{ number_format($totalDebt) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Organizational Amount Paid</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">₦{{ number_format($totalConfirmedPayment) }}</dd>
            </div>
        </dl>
    </div>
    <div class="mt-8">
        @include('lego::models._includes.indexes.filters')
    </div>

    <x-fab::lists.table>
        <x-slot name="headers">
            @include('lego::models._includes.indexes.headers')
            <x-fab::lists.table.header :hidden="true">Edit</x-fab::lists.table.header>
        </x-slot>

        @include('lego::models._includes.indexes.header-filters')
        <x-fab::lists.table.header x-show="showColumnFilters" x-cloak class="bg-gray-100" />

        @foreach($models as $data)
            <x-fab::lists.table.row :odd="$loop->odd">
                @if($this->shouldShowColumn('amount'))
                    <x-fab::lists.table.column primary full>
                        <a href="{{ route('lego.pamtechoga.payments.edit', $data) }}">{{ number_format($data->amount) }}</a>
                    </x-fab::lists.table.column>
                @endisset

                @if($this->shouldShowColumn('status'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.payments.edit', $data) }}">{{ $data->status }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('type'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.payments.edit', $data) }}">{{ $data->type }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('organization'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.payments.edit', $data) }}">{{ $data->organization?->name }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('payment_date'))
                    <x-fab::lists.table.column align="right">
                        {{ $data->payment_date }}
                    </x-fab::lists.table.column>
                @endisset

                @if($this->shouldShowColumn('updated_at'))
                    <x-fab::lists.table.column align="right">
                        {{ $data->updated_at->toFormattedDateString() }}
                    </x-fab::lists.table.column>
                @endisset

                <x-fab::lists.table.column align="right" slim>
                <a href="{{ route('lego.pamtechoga.payments.edit', $data) }}">Edit</a>
                </x-fab::lists.table.column>
            </x-fab::lists.table.row>
        @endforeach
    </x-fab::lists.table>

    @include('lego::models._includes.indexes.pagination')
</x-fab::layouts.page>
