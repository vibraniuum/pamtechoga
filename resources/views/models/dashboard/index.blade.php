<div>
{{--    @php--}}
{{--        use Illuminate\Support\Facades\DB;--}}
{{--        use Vibraniuum\Pamtechoga\Models\Order;--}}
{{--        use Vibraniuum\Pamtechoga\Models\DepotOrder;--}}
{{--        use Vibraniuum\Pamtechoga\Models\DepotPickup;--}}
{{--        use Vibraniuum\Pamtechoga\Models\Product;--}}
{{--        use Vibraniuum\Pamtechoga\Models\Organization;--}}
{{--        use Vibraniuum\Pamtechoga\Models\Driver;--}}
{{--        use Vibraniuum\Pamtechoga\Models\Truck;--}}
{{--        use Vibraniuum\Pamtechoga\Models\Payment;--}}

{{--        $totalCustomerOrdersAmount = Order::where('status', '<>', 'CANCELED')--}}
{{--            //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])--}}
{{--            ->select(DB::raw('SUM(volume * unit_price) AS total'))--}}
{{--            ->first();--}}
{{--        $totalCustomerOrders = Order::where('status', '<>', 'CANCELED')->count();--}}
{{--        $totalCustomerOrdersVolume = Order::where('status', '<>', 'CANCELED')->sum('volume');--}}

{{--        $totalConfirmedPayment = Payment::where('status', '=', 'CONFIRMED')->sum('amount');--}}

{{--        $totalDebt = $totalCustomerOrdersAmount->total - $totalConfirmedPayment;--}}

{{--        $totalCustomerOrdersDeliveredAmount = Order::where('status', '=', 'DELIVERED')--}}
{{--            //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])--}}
{{--            ->select(DB::raw('SUM(volume * unit_price) AS total'))--}}
{{--            ->first();--}}
{{--        $totalCustomerOrdersDelivered = Order::where('status', '=', 'DELIVERED')->count();--}}
{{--        $totalCustomerOrdersDeliveredVolume = Order::where('status', '=', 'DELIVERED')->sum('volume');--}}
{{--        $deliveredOrdersBreakDownByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))--}}
{{--            ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')--}}
{{--            ->where('status', '=', 'DELIVERED')--}}
{{--            //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])--}}
{{--            ->groupBy('product_id')--}}
{{--            ->get();--}}

{{--        $totalDepotOrdersAmount = DepotOrder::where('status', '<>', 'CANCELED')--}}
{{--            //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])--}}
{{--            ->select(DB::raw('SUM(volume * unit_price) AS total'))--}}
{{--            ->first();--}}
{{--        $totalDepotOrders = DepotOrder::where('status', '<>', 'CANCELED')->count();--}}
{{--        $totalDepotOrdersVolume = DepotOrder::where('status', '<>', 'CANCELED')->sum('volume');--}}

{{--        $ordersBreakDownByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))--}}
{{--            ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')--}}
{{--            ->where('status', '<>', 'CANCELED')--}}
{{--            //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])--}}
{{--            ->groupBy('product_id')--}}
{{--            ->get();--}}

{{--        $totalProducts = Product::count();--}}
{{--        $totalOrganizations = Organization::count();--}}
{{--        $totalDrivers = Driver::count();--}}
{{--        $totalTrucks = Truck::count();--}}

{{--        $totalPMSDepotOrdersVolume = DepotOrder::join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_depot_orders.product_id')--}}
{{--            ->where('pamtechoga_depot_orders.status', '<>', 'CANCELED')--}}
{{--            ->where('pamtechoga_products.type', 'PMS')--}}
{{--            ->sum('pamtechoga_depot_orders.volume');--}}

{{--        $totalPMSDepotPickupsVolume = DepotPickup::join('pamtechoga_customer_orders','pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')--}}
{{--            ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')--}}
{{--            ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')--}}
{{--            ->where('pamtechoga_products.type', 'PMS')--}}
{{--            ->sum('pamtechoga_depot_pickups.volume_assigned');--}}

{{--        $totalVolumeOfPMSAtDepot = $totalPMSDepotOrdersVolume - $totalPMSDepotPickupsVolume;--}}

{{--        $totalAGODepotOrdersVolume = DepotOrder::join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_depot_orders.product_id')--}}
{{--            ->where('pamtechoga_depot_orders.status', '<>', 'CANCELED')--}}
{{--            ->where('pamtechoga_products.type', 'AGO')--}}
{{--            ->sum('pamtechoga_depot_orders.volume');--}}

{{--        $totalAGODepotPickupsVolume = DepotPickup::join('pamtechoga_customer_orders','pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')--}}
{{--            ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')--}}
{{--            ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')--}}
{{--            ->where('pamtechoga_products.type', 'AGO')--}}
{{--            ->sum('pamtechoga_depot_pickups.volume_assigned');--}}

{{--        $totalVolumeOfAGOAtDepot = $totalAGODepotOrdersVolume - $totalAGODepotPickupsVolume;--}}

{{--        $totalVolumeOfPMSInTrucks = $totalAGODepotOrdersVolume - $totalAGODepotPickupsVolume;--}}

{{--        $deliveredOrdersVolumeByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))--}}
{{--            ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')--}}
{{--            ->where('status', '=', 'DELIVERED')--}}
{{--            ->orWhere('status', '=', 'DISPATCHED')--}}
{{--            //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])--}}
{{--            ->groupBy('product_id')--}}
{{--            ->get();--}}

{{--        $totalVolumeOfLoadedProductPickup = function ($type) {--}}
{{--            DepotPickup::join('pamtechoga_customer_orders','pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')--}}
{{--            ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')--}}
{{--            ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')--}}
{{--            ->where('pamtechoga_depot_pickups.status', '=', 'LOADED') // Ask Pamtech if LOADed means it is already in the truck or still at the depot.--}}
{{--            ->where('pamtechoga_products.type', $type)--}}
{{--            ->sum('pamtechoga_depot_pickups.volume_assigned');--}}
{{--        }--}}

{{--    @endphp--}}

    <div>
        <livewire:pamtechoga-datefilter-form />
    </div>

    <h2 class="mt-8 text-lg font-semibold leading-6 text-gray-900">
        Sales
    </h2>

    <div>
        <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Customer Orders Delivered Amount</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">₦{{ number_format($totalCustomerOrdersDeliveredAmount?->total) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Customer Orders Delivered</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalCustomerOrdersDelivered) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Customer Orders Delivered Volume (Litres)</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalCustomerOrdersDeliveredVolume) }}</dd>
            </div>

            {{--        @foreach($deliveredOrdersBreakDownByProduct as $data)--}}
            {{--            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">--}}
            {{--                <dt class="truncate text-sm font-medium text-gray-500">Total {{ $data->type }} Orders Volume (Litres)</dt>--}}
            {{--                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ number_format($data->total) }}</dd>--}}
            {{--            </div>--}}
            {{--        @endforeach--}}
        </dl>
    </div>

    <h2 class="mt-8 text-lg font-semibold leading-6 text-gray-900">
        Payments
    </h2>

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

    <h2 class="mt-8 text-lg font-semibold leading-6 text-gray-900">
        Customer Orders
    </h2>

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

            @foreach($ordersBreakDownByProduct as $data)
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total {{ $data->type }} Orders Volume (Litres)</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($data->total) }}</dd>
                </div>
            @endforeach
        </dl>
    </div>

    <h2 class="mt-8 text-lg font-semibold leading-6 text-gray-900">
        Products
    </h2>

    <div>
        <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Products</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalProducts) }}</dd>
            </div>

            @foreach($ordersBreakDownByProduct as $data)
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total {{ $data->type }} Orders Volume (Litres)</dt>
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($data->total) }}</dd>
                </div>
            @endforeach
        </dl>
    </div>

    <h2 class="mt-8 text-lg font-semibold leading-6 text-gray-900">
        Trucks
    </h2>

    <div>
        <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Trucks</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalTrucks) }}</dd>
            </div>

            @foreach($deliveredOrdersVolumeByProduct as $data)
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total {{ $data->type }} Volume in Trucks (Litres)</dt>
{{--                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalVolumeOfLoadedProductPickup($data->type) -  $data->total) }}</dd>--}}
                    <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($this->totalVolumeOfLoadedProductPickupFunction($data->type) -  $data->total) }}</dd>
                </div>
            @endforeach

            {{--        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">--}}
            {{--            <dt class="truncate text-sm font-medium text-gray-500">Total Volume of PMS in Trucks (Litres)</dt>--}}
            {{--            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ number_format($totalVolumeOfPMSAtDepot) }}</dd>--}}
            {{--        </div>--}}

            {{--        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">--}}
            {{--            <dt class="truncate text-sm font-medium text-gray-500">Total Volume of AGO in Trucks (Litres)</dt>--}}
            {{--            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ number_format($totalVolumeOfAGOAtDepot) }}</dd>--}}
            {{--        </div>--}}
        </dl>
    </div>

    <h2 class="mt-8 text-lg font-semibold leading-6 text-gray-900">
        Depot Orders
    </h2>

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


    <h2 class="mt-8 text-lg font-semibold leading-6 text-gray-900">
        Others
    </h2>

    <div>
        <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Organizations</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalOrganizations) }}</dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Drivers</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalDrivers) }}</dd>
            </div>
        </dl>
    </div>

</div>
