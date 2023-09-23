<x-fab::layouts.page
    title="Detailed Sales with {{ \Vibraniuum\Pamtechoga\Models\Organization::where('id', $this->organization)->first()->name }}"
    :breadcrumbs="[
        ['title' => 'Home', 'url' => route('lego.dashboard')],
        ['title' => 'Organizations','url' => route('lego.pamtechoga.organizations.index')],
    ]"
    x-data="{ showColumnFilters: false }"
>

{{--        @include('lego::models._includes.indexes.filters')--}}

    <div>
        <livewire:pamtechoga-datefilter-form />
    </div>

    <div>
        <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">All Time Debt Owed</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalDebtOwed) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">All time Payment Made</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">₦{{ number_format($totalPaymentsMade) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Balance Brought Forward</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">₦{{ number_format($bfDebt) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">
                    Payments for: {{ \Illuminate\Support\Carbon::make($startDate)->toFormattedDateString() }} - {{ \Illuminate\Support\Carbon::make($endDate)->toFormattedDateString() }}
                </dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalPaymentsWithinRange) }}</dd>
            </div>
        </dl>
    </div>

    <div class="mt-8 text-xl font-semibold tracking-tight text-gray-900">{{ \Vibraniuum\Pamtechoga\Models\Organization::where('id', $this->organization)->first()->name }}'s Orders for: {{ \Illuminate\Support\Carbon::make($startDate)->toFormattedDateString() }} - {{ \Illuminate\Support\Carbon::make($endDate)->toFormattedDateString() }}</div>

    <x-fab::lists.table class="mt-8">
        <x-slot name="headers">
            @include('lego::models._includes.indexes.headers')
            <x-fab::lists.table.header :hidden="true">Edit</x-fab::lists.table.header>
        </x-slot>

        @include('lego::models._includes.indexes.header-filters')
        <x-fab::lists.table.header x-show="showColumnFilters" x-cloak class="bg-gray-100" />

        <x-fab::lists.table.row>

            @if($this->shouldShowColumn('date'))
                <x-fab::lists.table.column align="right">

                </x-fab::lists.table.column>
            @endisset

            @if($this->shouldShowColumn('product'))
                <x-fab::lists.table.column>

                </x-fab::lists.table.column>
            @endif

            @if($this->shouldShowColumn('volume'))
                <x-fab::lists.table.column>

                </x-fab::lists.table.column>
            @endif

            @if($this->shouldShowColumn('unit_price'))
                <x-fab::lists.table.column>
                    <span class="font-bold">B / F</span>
                </x-fab::lists.table.column>
            @endif

            @if($this->shouldShowColumn('amount'))
                <x-fab::lists.table.column>
                    <span class="font-bold">{{ number_format($bfDebt) }}</span>
                </x-fab::lists.table.column>
            @endif

            @if($this->shouldShowColumn('driver'))
                <x-fab::lists.table.column>

                </x-fab::lists.table.column>
            @endif

        </x-fab::lists.table.row>

        @foreach($orders as $data)
            <x-fab::lists.table.row :odd="$loop->odd">
{{--                @if($this->shouldShowColumn('product'))--}}
{{--                    <x-fab::lists.table.column primary full>--}}
{{--                        <span>{{ $data->organization->name }}</span>--}}
{{--                    </x-fab::lists.table.column>--}}
{{--                @endisset--}}

                @if($this->shouldShowColumn('date'))
                    <x-fab::lists.table.column align="right">
                        {{ $data->created_at->toFormattedDateString() }}
                    </x-fab::lists.table.column>
                @endisset

                @if($this->shouldShowColumn('product'))
                    <x-fab::lists.table.column>
                        <span>{{ $data->product->type }}</span>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('volume'))
                    <x-fab::lists.table.column>
                        <span>{{ number_format($data->volume) }}</span>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('unit_price'))
                    <x-fab::lists.table.column>
                        <span>{{ number_format($data->unit_price) }}</span>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('amount'))
                    <x-fab::lists.table.column>
                        <span>{{ number_format($data->volume * $data->unit_price) }}</span>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('driver'))
                    <x-fab::lists.table.column>
                        <span>{{ $data->driver->name }}</span>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('status'))
                    <x-fab::lists.table.column>
                        <span>{{ $data->status }}</span>
                    </x-fab::lists.table.column>
                @endif


{{--                <x-fab::lists.table.column align="right" slim>--}}
{{--                <a href="{{ route('lego.pamtechoga.orders.edit', $data) }}">Edit</a>--}}
{{--                </x-fab::lists.table.column>--}}
            </x-fab::lists.table.row>
        @endforeach
        <x-fab::lists.table.row>
            {{--                @if($this->shouldShowColumn('product'))--}}
            {{--                    <x-fab::lists.table.column primary full>--}}
            {{--                        <span>{{ $data->organization->name }}</span>--}}
            {{--                    </x-fab::lists.table.column>--}}
            {{--                @endisset--}}

            @if($this->shouldShowColumn('date'))
                <x-fab::lists.table.column align="right">

                </x-fab::lists.table.column>
            @endisset

            @if($this->shouldShowColumn('product'))
                <x-fab::lists.table.column>

                </x-fab::lists.table.column>
            @endif

            @if($this->shouldShowColumn('volume'))
                <x-fab::lists.table.column>
                    <span class="font-bold">{{ number_format($ordersVolumeTotal) }}</span>
                </x-fab::lists.table.column>
            @endif

            @if($this->shouldShowColumn('unit_price'))
                <x-fab::lists.table.column>
                    <span class="font-bold">Total</span>
                </x-fab::lists.table.column>
            @endif

            @if($this->shouldShowColumn('amount'))
                <x-fab::lists.table.column>
                    <span class="font-bold">{{ number_format($ordersAmountTotal?->total + $bfDebt) }}</span>
                </x-fab::lists.table.column>
            @endif

            @if($this->shouldShowColumn('driver'))
                <x-fab::lists.table.column>

                </x-fab::lists.table.column>
            @endif

        </x-fab::lists.table.row>
        <x-fab::lists.table.row>

            @if($this->shouldShowColumn('date'))
                <x-fab::lists.table.column align="right">

                </x-fab::lists.table.column>
            @endisset

            @if($this->shouldShowColumn('product'))
                <x-fab::lists.table.column>

                </x-fab::lists.table.column>
            @endif

            @if($this->shouldShowColumn('volume'))
                <x-fab::lists.table.column>

                </x-fab::lists.table.column>
            @endif

            @if($this->shouldShowColumn('unit_price'))
                <x-fab::lists.table.column>
                    <span class="font-bold">Overall Balance</span>
                </x-fab::lists.table.column>
            @endif

            @if($this->shouldShowColumn('amount'))
                <x-fab::lists.table.column>
{{--                    <span class="font-bold">{{ number_format($ordersAmountTotal?->total - $totalPaymentsWithinRange) }}</span>--}}
                    <span class="font-bold">{{ number_format($totalDebtOwed) }}</span>
                </x-fab::lists.table.column>
            @endif

            @if($this->shouldShowColumn('driver'))
                <x-fab::lists.table.column>

                </x-fab::lists.table.column>
            @endif

        </x-fab::lists.table.row>
    </x-fab::lists.table>

{{--    @include('lego::models._includes.indexes.pagination')--}}

    <div class="mt-16">
        <div class="mt-8 text-xl font-semibold tracking-tight text-gray-900">{{ \Vibraniuum\Pamtechoga\Models\Organization::where('id', $this->organization)->first()->name }}'s Payments for: {{ \Illuminate\Support\Carbon::make($startDate)->toFormattedDateString() }} - {{ \Illuminate\Support\Carbon::make($endDate)->toFormattedDateString() }}</div>

        <div class="mt-8 relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Payment Made On
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Amount
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                </tr>
                </thead>
                <tbody>
{{--                @dd($payments)--}}
                @foreach($payments as $data)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">
                            {{ \Illuminate\Support\Carbon::make($data->payment_date)->toFormattedDateString() }}
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ number_format($data->amount) }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $data->status }}
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-6 py-4 font-bold">
                        Total
                    </td>
                    <th scope="row" class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                        {{ number_format($totalPaymentsWithinRange) }}
                    </th>
                    <td class="px-6 py-4">

                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</x-fab::layouts.page>
