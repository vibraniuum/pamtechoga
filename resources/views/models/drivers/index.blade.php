<x-fab::layouts.page
    title="Drivers"
    :breadcrumbs="[
        ['title' => 'Home', 'url' => route('lego.dashboard')],
        ['title' => 'Drivers','url' => route('lego.pamtechoga.drivers.index')],
    ]"
    x-data="{ showColumnFilters: false }"
>
    <x-slot name="actions">
        <x-fab::elements.button type="link" :url="route('lego.pamtechoga.drivers.create')">Create</x-fab::elements.button>
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
                @if($this->shouldShowColumn('name'))
                    <x-fab::lists.table.column primary full>
                        <a href="{{ route('lego.pamtechoga.drivers.edit', $data) }}">{{ $data->name }} ({{ $data->nickname ?? '' }})</a>
                    </x-fab::lists.table.column>
                @endisset

                @if($this->shouldShowColumn('phone'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.drivers.edit', $data) }}">{{ $data->phone }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('email'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.drivers.edit', $data) }}">{{ $data->email }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('truck'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.drivers.edit', $data) }}">{{ $data->truck?->plate_number }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('updated_at'))
                    <x-fab::lists.table.column align="right">
                        {{ $data->updated_at->toFormattedDateString() }}
                    </x-fab::lists.table.column>
                @endisset

                <x-fab::lists.table.column align="right" slim>
                <a href="{{ route('lego.pamtechoga.drivers.edit', $data) }}">Edit</a>
                </x-fab::lists.table.column>
            </x-fab::lists.table.row>
        @endforeach
    </x-fab::lists.table>

    @include('lego::models._includes.indexes.pagination')
</x-fab::layouts.page>
