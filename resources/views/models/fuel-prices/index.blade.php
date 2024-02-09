<x-fab::layouts.page
    title="Fuel Prices"
    :breadcrumbs="[
        ['title' => 'Home', 'url' => route('lego.dashboard')],
        ['title' => 'Fuel Prices','url' => route('lego.pamtechoga.fuel-prices.index')],
    ]"
    x-data="{ showColumnFilters: false }"
>
    <x-slot name="actions">
        <x-fab::elements.button type="link" :url="route('lego.pamtechoga.fuel-prices.create')">Create</x-fab::elements.button>
    </x-slot>

        @include('lego::models._includes.indexes.filters')

    <x-fab::lists.table>
        <x-slot name="headers">
            @include('lego::models._includes.indexes.headers')
            <x-fab::lists.table.header :hidden="true">Edit</x-fab::lists.table.header>
        </x-slot>

        @include('lego::models._includes.indexes.header-filters')
        <x-fab::lists.table.header x-show="showColumnFilters" x-cloak class="bg-gray-100" />

        @foreach($models as $data)
            <x-fab::lists.table.row :odd="$loop->odd">
                @if($this->shouldShowColumn('company_name'))
                    <x-fab::lists.table.column primary full>
                        <a href="{{ route('lego.pamtechoga.fuel-prices.edit', $data) }}">{{ $data->company_name }}</a>
                    </x-fab::lists.table.column>
                @endisset

                @if($this->shouldShowColumn('zone'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.fuel-prices.edit', $data) }}">{{ $data->zone?->name }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('petrol'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.fuel-prices.edit', $data) }}">{{ $data->petrol }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('diesel'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.fuel-prices.edit', $data) }}">{{ $data->diesel }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('premium'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.fuel-prices.edit', $data) }}">{{ $data->premium }}</a>
                    </x-fab::lists.table.column>
                @endif


                @if($this->shouldShowColumn('updated_at'))
                    <x-fab::lists.table.column align="right">
                        {{ $data->updated_at->toFormattedDateString() }}
                    </x-fab::lists.table.column>
                @endisset

                <x-fab::lists.table.column align="right" slim>
                                    <a href="{{ route('lego.pamtechoga.fuel-prices.edit', $data) }}">Edit</a>
                </x-fab::lists.table.column>
            </x-fab::lists.table.row>
        @endforeach
    </x-fab::lists.table>

    @include('lego::models._includes.indexes.pagination')
</x-fab::layouts.page>
