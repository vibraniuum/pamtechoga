@php
    use Vibraniuum\Pamtechoga\Models\Organization;

    $totalOrganizations = Organization::count();

@endphp

<x-fab::layouts.page
    title="Organizations"
    :breadcrumbs="[
        ['title' => 'Home', 'url' => route('lego.dashboard')],
        ['title' => 'Organizations','url' => route('lego.pamtechoga.organizations.index')],
    ]"
    x-data="{ showColumnFilters: false }"
>
    <x-slot name="actions">
        <x-fab::elements.button type="link" :url="route('lego.pamtechoga.organizations.create')">Create</x-fab::elements.button>
    </x-slot>

    <div>
        <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Organizations</dt>
                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ number_format($totalOrganizations) }}</dd>
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

        @foreach($models as $organization)
            <x-fab::lists.table.row :odd="$loop->odd">
                @if($this->shouldShowColumn('name'))
                    <x-fab::lists.table.column primary full>
                        <a href="{{ route('lego.pamtechoga.organizations.edit', $organization) }}">{{ $organization->name }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('phone'))
                    <x-fab::lists.table.column align="right">
                        {{ $organization->phone }}
                    </x-fab::lists.table.column>
                @endisset

                @if($this->shouldShowColumn('email'))
                    <x-fab::lists.table.column align="right">
                        {{ $organization->email }}
                    </x-fab::lists.table.column>
                @endisset

                @if($this->shouldShowColumn('updated_at'))
                    <x-fab::lists.table.column align="right">
                        {{ $organization->updated_at->toFormattedDateString() }}
                    </x-fab::lists.table.column>
                @endisset

                <x-fab::lists.table.column align="right" slim>
                    <a href="{{ route('lego.pamtechoga.organizations.edit', $organization) }}">Edit</a> •
                    <a href="{{ route('lego.pamtechoga.organizations.organizationOrders', $organization) }}">Orders</a> •
                    <a href="{{ route('lego.pamtechoga.organizations.organizationPayments', $organization) }}">Payments</a>
                </x-fab::lists.table.column>
            </x-fab::lists.table.row>
        @endforeach
    </x-fab::lists.table>

    @include('lego::models._includes.indexes.pagination')
</x-fab::layouts.page>
