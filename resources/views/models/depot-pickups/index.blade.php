@php
    use Carbon\Carbon;
@endphp
<x-fab::layouts.page
    title="Depot Pickups"
    :breadcrumbs="[
        ['title' => 'Home', 'url' => route('lego.dashboard')],
        ['title' => 'Depot Pickups','url' => route('lego.pamtechoga.depot-pickups.index')],
    ]"
    x-data="{ showColumnFilters: false }"
>
    <x-slot name="actions">
        <x-fab::elements.button type="link" :url="route('lego.pamtechoga.depot-pickups.bulk-create')">Create</x-fab::elements.button>
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
                @if($this->shouldShowColumn('depot_name'))
                    <x-fab::lists.table.column primary full>
                        <a href="{{ route('lego.pamtechoga.depot-pickups.edit', $data) }}">{{ $data->depotOrder?->depot->name }}</a>
                    </x-fab::lists.table.column>
                @endisset

                @if($this->shouldShowColumn('volume'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.depot-pickups.edit', $data) }}">{{ number_format($data->volume_assigned) }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('status'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.depot-pickups.edit', $data) }}">{{ $data->status }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('unit_price'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.depot-pickups.edit', $data) }}">{{ number_format($data->depotOrder?->unit_price) }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('driver'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.depot-pickups.edit', $data) }}">{{ $data->driver?->name ?? 'no name set'}}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('loaded_datetime'))
                    <x-fab::lists.table.column align="right">
                        {{ Carbon::parse($data->loaded_datetime)->toFormattedDateString() }}
                    </x-fab::lists.table.column>
                @endisset

                <x-fab::lists.table.column align="right" slim>
                <a href="{{ route('lego.pamtechoga.depot-pickups.edit', $data) }}">Edit</a>
                </x-fab::lists.table.column>
            </x-fab::lists.table.row>
        @endforeach
    </x-fab::lists.table>

    @include('lego::models._includes.indexes.pagination')
</x-fab::layouts.page>
