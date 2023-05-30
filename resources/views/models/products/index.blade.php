<x-fab::layouts.page
    title="Products"
    :breadcrumbs="[
        ['title' => 'Home', 'url' => route('lego.dashboard')],
        ['title' => 'Products','url' => route('lego.pamtechoga.products.index')],
    ]"
    x-data="{ showColumnFilters: false }"
>
    <x-slot name="actions">
        <x-fab::elements.button type="link" :url="route('lego.pamtechoga.products.create')">Create</x-fab::elements.button>
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
                @if($this->shouldShowColumn('type'))
                    <x-fab::lists.table.column primary full>
                        <a href="{{ route('lego.pamtechoga.products.edit', $data) }}">{{ $data->type }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('market_price'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.products.edit', $data) }}">{{ $data->market_price }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('instock'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.products.edit', $data) }}">{{ $data->instock ? 'YES' : 'NO' }}</a>
                    </x-fab::lists.table.column>
                @endif

                @if($this->shouldShowColumn('updated_at'))
                    <x-fab::lists.table.column align="right">
                        {{ $data->updated_at->toFormattedDateString() }}
                    </x-fab::lists.table.column>
                @endisset

                <x-fab::lists.table.column align="right" slim>
                <a href="{{ route('lego.pamtechoga.products.edit', $data) }}">Edit</a>
                </x-fab::lists.table.column>
            </x-fab::lists.table.row>
        @endforeach
    </x-fab::lists.table>

    @include('lego::models._includes.indexes.pagination')
</x-fab::layouts.page>
