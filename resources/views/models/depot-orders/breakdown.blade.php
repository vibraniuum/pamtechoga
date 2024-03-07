@php
    use Carbon\Carbon;
@endphp
<x-fab::layouts.page
    title="Depot Order Breakdown: {{ number_format(\Vibraniuum\Pamtechoga\Models\DepotOrder::where('id', $this->depotOrder)->first()->volume) }} Order date: {{ \Illuminate\Support\Carbon::make(\Vibraniuum\Pamtechoga\Models\DepotOrder::where('id', $this->depotOrder)->first()->order_date)->toFormattedDateString() }}"
    :breadcrumbs="[
        ['title' => 'Home', 'url' => route('lego.dashboard')],
        ['title' => 'Depot Orders','url' => route('lego.pamtechoga.depot-orders.index')],
    ]"
    x-data="{ showColumnFilters: false }"
>

    <div>
        <div>
            <dl class=" grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Unloaded Volume (Litres)</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($this->unloadedVolume()) }}</dd>
                </div>

                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Loaded Volume (Products in trucks)</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($this->loadedVolume()) }}</dd>
                </div>

                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Delivered Volume (Litres)</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($this->deliveredVolume()) }}</dd>
                </div>

                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Number of Loaded Trucks</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($this->numberOfLoadedTrucks()) }}</dd>
                </div>
            </dl>
        </div>
        <div class="mt-5"></div>
    </div>

    <div class="mt-16">
        <div class="mt-8 text-xl font-semibold tracking-tight text-gray-900">Pickups</div>
        <div class="mt-4 text-sm tracking-tight text-gray-500">Below are pickups for this depot order and data showing who loaded, and where it went to</div>

        <div class="mt-8 relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Date Loaded
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Driver & Truck
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Volume Assigned
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Volume Balance
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Delivered to
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Order Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
{{--                    <th scope="col" class="px-6 py-3">--}}
{{--                        Action--}}
{{--                    </th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($models as $data)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">
                            {{ \Illuminate\Support\Carbon::make($data->loaded_datetime)->toFormattedDateString() }}
                        </td>
                        <td class="px-6 py-4">
                            <b>{{ $data->driver->name }}</b> ({{ $data->driver->nickname }}) <br /> Truck: {{ $data->driver?->truck?->plate_number }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($data->volume_assigned) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($data->volume_balance) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $data->order?->organization?->name }},
                            {{ $data->order?->branch?->address }}
                        </td>
                        <td class="px-6 py-4">
                            @if($data->order)
                                {{ \Illuminate\Support\Carbon::make($data->order?->order_date)->toFormattedDateString() }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {{ $data->status == 'COMPLETED' ? 'DELIVERED' : $data->status }}
                        </td>
{{--                        <td class="px-6 py-4">--}}
{{--                            <a href="{{ route('lego.pamtechoga.payments.edit', $data) }}">View</a>--}}
{{--                        </td>--}}
                    </tr>
                @endforeach
              </table>
        </div>

    </div>
</x-fab::layouts.page>
