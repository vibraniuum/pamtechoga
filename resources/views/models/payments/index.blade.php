@php
    use Illuminate\Support\Facades\DB;
    use Vibraniuum\Pamtechoga\Models\Order;
    use Vibraniuum\Pamtechoga\Models\DepotOrder;
    use Vibraniuum\Pamtechoga\Models\DepotPickup;
    use Vibraniuum\Pamtechoga\Models\Product;
    use Vibraniuum\Pamtechoga\Models\Organization;
    use Vibraniuum\Pamtechoga\Models\Driver;
    use Vibraniuum\Pamtechoga\Models\Truck;
    use Vibraniuum\Pamtechoga\Models\Payment;

    $totalCustomerOrdersAmount = Order::where('status', '<>', 'CANCELED')
        //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])
        ->select(DB::raw('SUM(volume * unit_price) AS total'))
        ->first();
    $totalCustomerOrders = Order::where('status', '<>', 'CANCELED')->count();
    $totalCustomerOrdersVolume = Order::where('status', '<>', 'CANCELED')->sum('volume');

    $totalConfirmedPayment = Payment::where('status', '=', 'CONFIRMED')->sum('amount');

    $totalDebt = $totalCustomerOrdersAmount->total - $totalConfirmedPayment;

    $totalCustomerOrdersDeliveredAmount = Order::where('status', '=', 'DELIVERED')
        //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])
        ->select(DB::raw('SUM(volume * unit_price) AS total'))
        ->first();
    $totalCustomerOrdersDelivered = Order::where('status', '=', 'DELIVERED')->count();
    $totalCustomerOrdersDeliveredVolume = Order::where('status', '=', 'DELIVERED')->sum('volume');
    $deliveredOrdersBreakDownByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))
        ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
        ->where('status', '=', 'DELIVERED')
        //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])
        ->groupBy('product_id')
        ->get();

    $totalDepotOrdersAmount = DepotOrder::where('status', '<>', 'CANCELED')
        //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])
        ->select(DB::raw('SUM(volume * unit_price) AS total'))
        ->first();
    $totalDepotOrders = DepotOrder::where('status', '<>', 'CANCELED')->count();
    $totalDepotOrdersVolume = DepotOrder::where('status', '<>', 'CANCELED')->sum('volume');

    $ordersBreakDownByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))
        ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
        ->where('status', '<>', 'CANCELED')
        //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])
        ->groupBy('product_id')
        ->get();

    $totalProducts = Product::count();
    $totalOrganizations = Organization::count();
    $totalDrivers = Driver::count();
    $totalTrucks = Truck::count();

    $totalPMSDepotOrdersVolume = DepotOrder::join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_depot_orders.product_id')
        ->where('pamtechoga_depot_orders.status', '<>', 'CANCELED')
        ->where('pamtechoga_products.type', 'PMS')
        ->sum('pamtechoga_depot_orders.volume');

    $totalPMSDepotPickupsVolume = DepotPickup::join('pamtechoga_customer_orders','pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')
        ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
        ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')
        ->where('pamtechoga_products.type', 'PMS')
        ->sum('pamtechoga_depot_pickups.volume_assigned');

    $totalVolumeOfPMSAtDepot = $totalPMSDepotOrdersVolume - $totalPMSDepotPickupsVolume;

    $totalAGODepotOrdersVolume = DepotOrder::join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_depot_orders.product_id')
        ->where('pamtechoga_depot_orders.status', '<>', 'CANCELED')
        ->where('pamtechoga_products.type', 'AGO')
        ->sum('pamtechoga_depot_orders.volume');

    $totalAGODepotPickupsVolume = DepotPickup::join('pamtechoga_customer_orders','pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')
        ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
        ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')
        ->where('pamtechoga_products.type', 'AGO')
        ->sum('pamtechoga_depot_pickups.volume_assigned');

    $totalVolumeOfAGOAtDepot = $totalAGODepotOrdersVolume - $totalAGODepotPickupsVolume;

    $totalVolumeOfPMSInTrucks = $totalAGODepotOrdersVolume - $totalAGODepotPickupsVolume;

    $deliveredOrdersVolumeByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))
        ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
        ->where('status', '=', 'DELIVERED')
        ->orWhere('status', '=', 'DISPATCHED')
        //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])
        ->groupBy('product_id')
        ->get();

    $totalVolumeOfLoadedProductPickup = function ($type) {
        DepotPickup::join('pamtechoga_customer_orders','pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')
        ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
        ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')
        ->where('pamtechoga_depot_pickups.status', '=', 'LOADED') // Ask Pamtech if LOADed means it is already in the truck or still at the depot.
        ->where('pamtechoga_products.type', $type)
        ->sum('pamtechoga_depot_pickups.volume_assigned');
    }

@endphp
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
        <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Organizational Debts Amount</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">₦{{ number_format($totalDebt) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Organizational Amount Paid</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">₦{{ number_format($totalConfirmedPayment) }}</dd>
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
                        <a href="{{ route('lego.pamtechoga.payments.edit', $data) }}">{{ $data->amount }}</a>
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
                        <a href="{{ route('lego.pamtechoga.payments.edit', $data) }}">{{ $data->organization->name }}</a>
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
