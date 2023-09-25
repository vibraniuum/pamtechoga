<?php

namespace Vibraniuum\Pamtechoga;

use Astrogoat\Storefront\Http\Livewire\StorefrontCollectionForm;
use Astrogoat\Storefront\Http\Livewire\StorefrontCollectionIndex;
use Astrogoat\Storefront\Http\Livewire\StorefrontOrderCreateForm;
use Astrogoat\Storefront\Http\Livewire\StorefrontOrderForm;
use Astrogoat\Storefront\Http\Livewire\StorefrontOrderIndex;
use Astrogoat\Storefront\Http\Livewire\StorefrontProductForm;
use Astrogoat\Storefront\Http\Livewire\StorefrontProductIndex;
use Astrogoat\Storefront\Http\Livewire\StorefrontSaleForm;
use Astrogoat\Storefront\Http\Livewire\StorefrontSaleIndex;
use Helix\Fabrick\Icon;
use Helix\Lego\Apps\App;
use Helix\Lego\LegoManager;
use Helix\Lego\Menus\Lego\Group;
use Helix\Lego\Menus\Lego\Link;
use Helix\Lego\Menus\Menu;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vibraniuum\Pamtechoga\Http\Livewire\AnnouncementsForm;
use Vibraniuum\Pamtechoga\Http\Livewire\AnnouncementsIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\BranchesForm;
use Vibraniuum\Pamtechoga\Http\Livewire\BranchesIndex;
use Vibraniuum\Pamtechoga\Http\Livewire\Dashboard;
use Vibraniuum\Pamtechoga\Http\Livewire\DateFilter;
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
use Vibraniuum\Pamtechoga\Http\Livewire\Timestamp;
use Vibraniuum\Pamtechoga\Http\Livewire\TrucksForm;
use Vibraniuum\Pamtechoga\Http\Livewire\TrucksIndex;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Settings\PamtechogaSettings;

class PamtechogaServiceProvider extends PackageServiceProvider
{
    public function registerApp(App $app)
    {
        return $app
            ->name('pamtechoga')
            ->settings(PamtechogaSettings::class)
            ->migrations([
                __DIR__ . '/../database/migrations',
                __DIR__ . '/../database/migrations/settings',
            ])
            ->models([
                Organization::class,
            ])
            ->menu(function (Menu $menu) {
                $menu->addToSection(
                    Menu::MAIN_SECTIONS['PRIMARY'],
                    Group::add(
                        'Orders',
                        [
                            Link::to(route('lego.pamtechoga.orders.index'), 'Customer Orders'),
                            Link::to(route('lego.pamtechoga.depot-orders.index'), 'Depot Orders'),
                            Link::to(route('lego.pamtechoga.depot-pickups.index'), 'Depot Pickups'),
                        ],
                        Icon::TRUCK
                    )->after('Pages')
                );
                $menu->addToSection(
                    Menu::MAIN_SECTIONS['PRIMARY'],
                    Group::add(
                        'Sales',
                        [
                            Link::to(route('lego.pamtechoga.sales.index'), 'Sales'),
                            Link::to(route('lego.pamtechoga.payments.index'), 'Payments'),
//                            Link::to(route('lego.pamtechoga.payments.index'), 'Sales Breakdown'),
                        ],
                        Icon::CHART_BAR
                    )->after('Orders')
                );
                $menu->addToSection(
                    Menu::MAIN_SECTIONS['PRIMARY'],
                    Group::add(
                        'Organizations',
                        [
                            Link::to(route('lego.pamtechoga.organizations.index'), 'Organizations'),
                            Link::to(route('lego.pamtechoga.branches.index'), 'Branches'),
                        ],
                        Icon::COLLECTION
                    )->after('Sales')
                );
                $menu->addToSection(
                    Menu::MAIN_SECTIONS['PRIMARY'],
                    Group::add(
                        'Models',
                        [
                            Link::to(route('lego.pamtechoga.products.index'), 'Products'),
                            Link::to(route('lego.pamtechoga.drivers.index'), 'Drivers'),
                            Link::to(route('lego.pamtechoga.old-driver-trips.index'), 'Old Driver Trips'),
                            Link::to(route('lego.pamtechoga.trucks.index'), 'Trucks'),
                            Link::to(route('lego.pamtechoga.depots.index'), 'Depots'),
                            Link::to(route('lego.pamtechoga.payment-details.index'), 'Payment Details'),
//                            Link::to(route('lego.pamtechoga.organizations.index'), 'Extra Expenses'),
//                            Link::to(route('lego.pamtechoga.organizations.index'), 'User Management'),
                        ],
                        Icon::FOLDER_OPEN
                    )->after('Organizations')
                );
                $menu->addToSection(
                    Menu::MAIN_SECTIONS['PRIMARY'],
                    Link::to(route('lego.pamtechoga.announcements.index'), 'Announcements')
                        ->icon(Icon::FOLDER_OPEN)
                        ->after('Models')
                );
                $menu->addToSection(
                    Menu::MAIN_SECTIONS['PRIMARY'],
                    Link::to(route('lego.pamtechoga.news.index'), 'News')
                        ->icon(Icon::FOLDER_OPEN)
                        ->after('Models')
                );
                $menu->addToSection(
                    Menu::MAIN_SECTIONS['PRIMARY'],
                    Link::to(route('lego.pamtechoga.fuel-prices.index'), 'Fuel Prices')
                        ->icon(Icon::FOLDER_OPEN)
                        ->after('Models')
                );
            })
            ->backendRoutes(__DIR__.'/../routes/backend.php')
            ->apiRoutes(__DIR__.'/../routes/api.php')
            ->frontendRoutes(__DIR__.'/../routes/frontend.php');
    }

    public function registeringPackage()
    {
        $this->callAfterResolving('lego', function (LegoManager $lego) {
            $lego->registerApp(fn (App $app) => $this->registerApp($app));
        });
    }

    public function configurePackage(Package $package): void
    {
        $package->name('pamtechoga')->hasConfigFile()->hasViews();
    }

    public function bootingPackage()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/pamtechoga'),
            ], 'pamtechoga-assets');
        }

        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-organizations-index', OrganizationsIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-organization-payments-index', OrganizationPaymentsIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-organization-orders-index', OrganizationOrdersIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-organizations-form', OrganizationsForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-branches-index', BranchesIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-branches-form', BranchesForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-payment-details-index', PaymentDetailsIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-payment-details-form', PaymentDetailsForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-trucks-index', TrucksIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-trucks-form', TrucksForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-drivers-index', DriversIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-drivers-form', DriversForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-products-index', ProductsIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-products-form', ProductsForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-depots-index', DepotsIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-depots-form', DepotsForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-orders-index', OrdersIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-orders-form', OrdersForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-sales-index', SalesIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-sales-form', SalesForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-depot-orders-index', DepotOrdersIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-depot-orders-form', DepotOrdersForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-depot-pickups-index', DepotPickupsIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-depot-pickups-form', DepotPickupsForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-depot-pickups-create', DepotPickupsCreate::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-old-driver-trips-index', OldDriverTripsIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-old-driver-trips-form', OldDriverTripsForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-payments-index', PaymentsIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-payments-form', PaymentsForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-fuel-prices-index', FuelPricesIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-fuel-prices-form', FuelPricesForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-news-index', NewsIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-news-form', NewsForm::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-announcements-index', AnnouncementsIndex::class);
        Livewire::component('astrogoat.pamtechoga.http.livewire.pamtechoga-announcements-form', AnnouncementsForm::class);
        Livewire::component('pamtechoga-datefilter-form', DateFilter::class);
        Livewire::component('pamtechoga-dashboard', Dashboard::class);
    }
}
