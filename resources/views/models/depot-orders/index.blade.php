
{{--@php--}}
{{--    use Illuminate\Support\Facades\DB;--}}
{{--    use Vibraniuum\Pamtechoga\Models\Order;--}}
{{--    use Vibraniuum\Pamtechoga\Models\DepotOrder;--}}
{{--    use Vibraniuum\Pamtechoga\Models\DepotPickup;--}}
{{--    use Vibraniuum\Pamtechoga\Models\Product;--}}
{{--    use Vibraniuum\Pamtechoga\Models\Organization;--}}

{{--    $totalDepotOrdersAmount = DepotOrder::where('status', '<>', 'CANCELED')--}}
{{--        //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])--}}
{{--        ->select(DB::raw('SUM(volume * unit_price) AS total'))--}}
{{--        ->first();--}}
{{--    $totalDepotOrders = DepotOrder::where('status', '<>', 'CANCELED')->count();--}}
{{--    $totalDepotOrdersVolume = DepotOrder::where('status', '<>', 'CANCELED')->sum('volume');--}}

{{--    $ordersBreakDownByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))--}}
{{--        ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')--}}
{{--        ->where('status', '<>', 'CANCELED')--}}
{{--        //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])--}}
{{--        ->groupBy('product_id')--}}
{{--        ->get();--}}

{{--    $totalPMSDepotOrdersVolume = DepotOrder::join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_depot_orders.product_id')--}}
{{--        ->where('pamtechoga_depot_orders.status', '<>', 'CANCELED')--}}
{{--        ->where('pamtechoga_products.type', 'PMS')--}}
{{--        ->sum('pamtechoga_depot_orders.volume');--}}

{{--    $totalPMSDepotPickupsVolume = DepotPickup::join('pamtechoga_customer_orders','pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')--}}
{{--        ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')--}}
{{--        ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')--}}
{{--        ->where('pamtechoga_products.type', 'PMS')--}}
{{--        ->sum('pamtechoga_depot_pickups.volume_assigned');--}}

{{--    $totalVolumeOfPMSAtDepot = $totalPMSDepotOrdersVolume - $totalPMSDepotPickupsVolume;--}}

{{--    $totalAGODepotOrdersVolume = DepotOrder::join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_depot_orders.product_id')--}}
{{--        ->where('pamtechoga_depot_orders.status', '<>', 'CANCELED')--}}
{{--        ->where('pamtechoga_products.type', 'AGO')--}}
{{--        ->sum('pamtechoga_depot_orders.volume');--}}

{{--    $totalAGODepotPickupsVolume = DepotPickup::join('pamtechoga_customer_orders','pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')--}}
{{--        ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')--}}
{{--        ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')--}}
{{--        ->where('pamtechoga_products.type', 'AGO')--}}
{{--        ->sum('pamtechoga_depot_pickups.volume_assigned');--}}

{{--    $totalVolumeOfAGOAtDepot = $totalAGODepotOrdersVolume - $totalAGODepotPickupsVolume;--}}

{{--    $totalVolumeOfPMSInTrucks = $totalAGODepotOrdersVolume - $totalAGODepotPickupsVolume;--}}

{{--    $deliveredOrdersVolumeByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))--}}
{{--        ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')--}}
{{--        ->where('status', '=', 'DELIVERED')--}}
{{--        ->orWhere('status', '=', 'DISPATCHED')--}}
{{--        //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])--}}
{{--        ->groupBy('product_id')--}}
{{--        ->get();--}}

{{--    $totalVolumeOfLoadedProductPickup = function ($type) {--}}
{{--        DepotPickup::join('pamtechoga_customer_orders','pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')--}}
{{--        ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')--}}
{{--        ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')--}}
{{--        ->where('pamtechoga_depot_pickups.status', '=', 'LOADED') // Ask Pamtech if LOADed means it is already in the truck or still at the depot.--}}
{{--        ->where('pamtechoga_products.type', $type)--}}
{{--        ->sum('pamtechoga_depot_pickups.volume_assigned');--}}
{{--    }--}}

{{--@endphp--}}

<x-fab::layouts.page
    title="Depot Orders"
    :breadcrumbs="[
        ['title' => 'Home', 'url' => route('lego.dashboard')],
        ['title' => 'Depot Orders','url' => route('lego.pamtechoga.depot-orders.index')],
    ]"
    x-data="{ showColumnFilters: false }"
>
    <x-slot name="actions">
        <x-fab::elements.button type="link" :url="route('lego.pamtechoga.depot-orders.create')">Create</x-fab::elements.button>
    </x-slot>

    <div>
        <livewire:pamtechoga-datefilter-form />
    </div>

    <div>
        <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Depot Orders Amount</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">₦{{ number_format($totalDepotOrdersAmount?->total) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Depot Orders</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalDepotOrders) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Depot Orders Volume (Litres)</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalDepotOrdersVolume) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Volume of PMS at Depot (Litres)</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalVolumeOfPMSAtDepot) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Volume of AGO at Depot (Litres)</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalVolumeOfAGOAtDepot) }}</dd>
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
                @if($this->shouldShowColumn('depot_name'))
                    <x-fab::lists.table.column primary full>
                        <a href="{{ route('lego.pamtechoga.depot-orders.edit', $data) }}">{{ $data->depot->name }}</a>
                    </x-fab::lists.table.column>
                @endisset

                @if($this->shouldShowColumn('product'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.depot-orders.edit', $data) }}">{{ $data->product->type }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('volume'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.depot-orders.edit', $data) }}">{{ $data->volume }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('status'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.depot-orders.edit', $data) }}">{{ $data->status }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('unit_price'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.depot-orders.edit', $data) }}">{{ $data->unit_price }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('updated_at'))
                    <x-fab::lists.table.column align="right">
                        {{ $data->updated_at->toFormattedDateString() }}
                    </x-fab::lists.table.column>
                @endisset

                <x-fab::lists.table.column align="right" slim>
                <a href="{{ route('lego.pamtechoga.depot-orders.edit', $data) }}">Edit</a>
                </x-fab::lists.table.column>
            </x-fab::lists.table.row>
        @endforeach
    </x-fab::lists.table>

    @include('lego::models._includes.indexes.pagination')
</x-fab::layouts.page>
