<?php


use Illuminate\Support\Facades\Route;

use Vibraniuum\Pamtechoga\Http\Livewire\AnnouncementsForm;
use Vibraniuum\Pamtechoga\Http\Livewire\AnnouncementsIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\BranchesForm;
use Vibraniuum\Pamtechoga\Http\Livewire\BranchesIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\DepotOrdersForm;
use Vibraniuum\Pamtechoga\Http\Livewire\DepotOrdersIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\DepotPickupsCreate;
use Vibraniuum\Pamtechoga\Http\Livewire\DepotPickupsForm;
use Vibraniuum\Pamtechoga\Http\Livewire\DepotPickupsIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\DepotsForm;
use Vibraniuum\Pamtechoga\Http\Livewire\DepotsIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\DriversForm;
use Vibraniuum\Pamtechoga\Http\Livewire\DriversIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\FuelPricesForm;
use Vibraniuum\Pamtechoga\Http\Livewire\FuelPricesIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\NewsForm;
use Vibraniuum\Pamtechoga\Http\Livewire\NewsIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\OldDriverTripsForm;
use Vibraniuum\Pamtechoga\Http\Livewire\OldDriverTripsIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\OrdersForm;
use Vibraniuum\Pamtechoga\Http\Livewire\OrdersIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\OrganizationOrdersIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\OrganizationPaymentsIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\OrganizationsForm;
use Vibraniuum\Pamtechoga\Http\Livewire\OrganizationsIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\PaymentDetailsForm;
use Vibraniuum\Pamtechoga\Http\Livewire\PaymentDetailsIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\PaymentsForm;
use Vibraniuum\Pamtechoga\Http\Livewire\PaymentsIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\ProductsForm;
use Vibraniuum\Pamtechoga\Http\Livewire\ProductsIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\SalesForm;
use Vibraniuum\Pamtechoga\Http\Livewire\SalesIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\TrucksForm;
use Vibraniuum\Pamtechoga\Http\Livewire\TrucksIndex;

Route::group([
    'as' => 'pamtechoga.',
    'prefix' => 'app/'
], function() {
    Route::group([
        'as' => 'organizations.',
        'prefix' => 'organizations/'
    ], function() {
        Route::get('/', OrganizationsIndex::class)->name('index');
        Route::get('/create', OrganizationsForm::class)->name('create');
        Route::get('/{organization}/edit', OrganizationsForm::class)->name('edit');
        Route::get('/{organization}/orders', OrganizationOrdersIndex::class)->name('organizationOrders');
        Route::get('/{organization}/payments', OrganizationPaymentsIndex::class)->name('organizationPayments');
    });

    Route::group([
        'as' => 'branches.',
        'prefix' => 'branches/'
    ], function() {
        Route::get('/', BranchesIndex::class)->name('index');
        Route::get('/create', BranchesForm::class)->name('create');
        Route::get('/{branch}/edit', BranchesForm::class)->name('edit');
    });

    Route::group([
        'as' => 'payment-details.',
        'prefix' => 'payment-details/'
    ], function() {
        Route::get('/', PaymentDetailsIndex::class)->name('index');
        Route::get('/create', PaymentDetailsForm::class)->name('create');
        Route::get('/{paymentDetail}/edit', PaymentDetailsForm::class)->name('edit');
    });

    Route::group([
        'as' => 'trucks.',
        'prefix' => 'trucks/'
    ], function() {
        Route::get('/', TrucksIndex::class)->name('index');
        Route::get('/create', TrucksForm::class)->name('create');
        Route::get('/{truck}/edit', TrucksForm::class)->name('edit');
    });

    Route::group([
        'as' => 'drivers.',
        'prefix' => 'drivers/'
    ], function() {
        Route::get('/', DriversIndex::class)->name('index');
        Route::get('/create', DriversForm::class)->name('create');
        Route::get('/{driver}/edit', DriversForm::class)->name('edit');
    });

    Route::group([
        'as' => 'products.',
        'prefix' => 'products/'
    ], function() {
        Route::get('/', ProductsIndex::class)->name('index');
        Route::get('/create', ProductsForm::class)->name('create');
        Route::get('/{product}/edit', ProductsForm::class)->name('edit');
    });

    Route::group([
        'as' => 'depots.',
        'prefix' => 'depots/'
    ], function() {
        Route::get('/', DepotsIndex::class)->name('index');
        Route::get('/create', DepotsForm::class)->name('create');
        Route::get('/{depot}/edit', DepotsForm::class)->name('edit');
    });

    Route::group([
        'as' => 'orders.',
        'prefix' => 'orders/'
    ], function() {
        Route::get('/', OrdersIndex::class)->name('index');
        Route::get('/create', OrdersForm::class)->name('create');
        Route::get('/{order}/edit', OrdersForm::class)->name('edit');
    });

    Route::group([
        'as' => 'sales.',
        'prefix' => 'sales/'
    ], function() {
        Route::get('/', SalesIndex::class)->name('index');
        Route::get('/create', SalesForm::class)->name('create');
        Route::get('/{order}/edit', SalesForm::class)->name('edit');
    });

    Route::group([
        'as' => 'depot-orders.',
        'prefix' => 'depot-orders/'
    ], function() {
        Route::get('/', DepotOrdersIndex::class)->name('index');
        Route::get('/create', DepotOrdersForm::class)->name('create');
        Route::get('/{depotOrder}/edit', DepotOrdersForm::class)->name('edit');
    });

    Route::group([
        'as' => 'depot-pickups.',
        'prefix' => 'depot-pickups/'
    ], function() {
        Route::get('/', DepotPickupsIndex::class)->name('index');
        Route::get('/bulk-create', DepotPickupsCreate::class)->name('bulk-create');
        Route::get('/create', DepotPickupsForm::class)->name('create');
        Route::get('/{depotPickup}/edit', DepotPickupsForm::class)->name('edit');
    });

    Route::group([
        'as' => 'old-driver-trips.',
        'prefix' => 'old-driver-trips/'
    ], function() {
        Route::get('/', OldDriverTripsIndex::class)->name('index');
        Route::get('/create', OldDriverTripsForm::class)->name('create');
        Route::get('/{oldDriverTrip}/edit', OldDriverTripsForm::class)->name('edit');
    });

    Route::group([
        'as' => 'payments.',
        'prefix' => 'payments/'
    ], function() {
        Route::get('/', PaymentsIndex::class)->name('index');
        Route::get('/create', PaymentsForm::class)->name('create');
        Route::get('/{payment}/edit', PaymentsForm::class)->name('edit');
    });

    // FUEL PRICES
    Route::group([
        'as' => 'fuel-prices.',
        'prefix' => 'fuel-prices/'
    ], function() {
        Route::get('/', FuelPricesIndex::class)->name('index');
        Route::get('/create', FuelPricesForm::class)->name('create');
        Route::get('/{fuelPrice}/edit', FuelPricesForm::class)->name('edit');
    });

    // NEWS
    Route::group([
        'as' => 'news.',
        'prefix' => 'news/'
    ], function() {
        Route::get('/', NewsIndex::class)->name('index');
        Route::get('/create', NewsForm::class)->name('create');
        Route::get('/{news}/edit', NewsForm::class)->name('edit');
    });

    // Announcements
    Route::group([
        'as' => 'announcements.',
        'prefix' => 'announcements/'
    ], function() {
        Route::get('/', AnnouncementsIndex::class)->name('index');
        Route::get('/create', AnnouncementsForm::class)->name('create');
        Route::get('/{announcement}/edit', AnnouncementsForm::class)->name('edit');
    });

});
