<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Traits\DateFilter;

class OrdersIndex extends BaseIndex
{
    use DateFilter;

    protected $listeners = ['filterApplied'];

    public function filterApplied($filterData)
    {
        $this->startDate = $filterData['startDate'];
        $this->endDate = $filterData['endDate'];
        $this->mount();
    }

    public function model(): string
    {
        return Order::class;
    }

    public function columns(): array
    {
        return [
            'organization_name' => 'Organization Name',
            'product' => 'Product',
            'volume' => 'Volume (Litres)',
            'unit_price' => 'Unit Price (NGN)',
            'amount' => 'Amount (NGN)',
            'status' => 'Status',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'organization_name';
    }

    function flattenArray($array) {
        $result = [];
        foreach ($array as $item) {
            if (is_array($item)) {
                $result = array_merge($result, $this->flattenArray($item));
            } else {
                $result[] = $item;
            }
        }
        return $result;
    }

    public function exportAsCSV()
    {
//        $ordersFromDB = Order::with(['product', 'organization', 'branch', 'driver'])->get()->toArray();
//        $orders = Order::with(['product', 'organization', 'branch', 'driver'])->get()->toArray();
        $orders = Order::with(['product', 'organization', 'branch', 'driver'])->get()->toArray();


//        dd($orders);

        // Generate your data to export (e.g., from a database query)
        $data = [
            ['Organization', 'Product', 'Volume', 'Unit Price', 'Amount', 'status', 'Driver'],
        ];

        $filename = 'export.csv';

        $handle = fopen($filename, 'w');

        // Write the header row
        fputcsv($handle, $data[0]);

        // Write the data rows
        for ($i = 0; $i < count($orders); $i++) {
            $flattenedOrder = $this->flattenArray($orders[$i]);

//            dd($flattenedOrder);

//            fputcsv($handle, $orders[$i]);
            fputcsv($handle, $flattenedOrder);
        }

        fclose($handle);

        return response()->stream(
            function () use ($filename) {
                readfile($filename);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }

    public function render()
    {
        if($this->canResetDate) {
            $this->resetDates();
        }
        $this->applyFilter();
        return view('pamtechoga::models.orders.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
