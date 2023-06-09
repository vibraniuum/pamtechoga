<x-fab::layouts.page
    title="Organizations"
    :breadcrumbs="[
        ['title' => 'Home', 'url' => route('lego.dashboard')],
        ['title' => 'Organizations','url' => route('lego.pamtechoga.organizations.index')],
    ]"
    x-data="{ showColumnFilters: false }"
>
    <x-slot name="actions">
        <x-fab::elements.button type="link" :url="route('lego.pamtechoga.branches.create')">Create</x-fab::elements.button>
    </x-slot>

        @include('lego::models._includes.indexes.filters')

    <x-fab::lists.table>
        <x-slot name="headers">
            @include('lego::models._includes.indexes.headers')
            <x-fab::lists.table.header :hidden="true">Edit</x-fab::lists.table.header>
        </x-slot>

        @include('lego::models._includes.indexes.header-filters')
        <x-fab::lists.table.header x-show="showColumnFilters" x-cloak class="bg-gray-100" />

        @foreach($models as $branch)
            <x-fab::lists.table.row :odd="$loop->odd">
                @if($this->shouldShowColumn('address'))
                    <x-fab::lists.table.column primary full>
                        <a href="{{ route('lego.pamtechoga.branches.edit', $branch) }}">{{ $branch->address }}</a>
                    </x-fab::lists.table.column>
                @endisset

                @if($this->shouldShowColumn('organization'))
                    <x-fab::lists.table.column>
                        <a href="{{ route('lego.pamtechoga.branches.edit', $branch) }}">{{ $branch->organization->name }}</a>
                    </x-fab::lists.table.column>
                @endif


                @if($this->shouldShowColumn('updated_at'))
                    <x-fab::lists.table.column align="right">
                        {{ $branch->updated_at->toFormattedDateString() }}
                    </x-fab::lists.table.column>
                @endisset

                <x-fab::lists.table.column align="right" slim>
                                    <a href="{{ route('lego.pamtechoga.branches.edit', $branch) }}">Edit</a>
                </x-fab::lists.table.column>
            </x-fab::lists.table.row>
        @endforeach
    </x-fab::lists.table>

    @include('lego::models._includes.indexes.pagination')
</x-fab::layouts.page>
