<x-fab::layouts.page
    :title="$model?->name ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Zones', 'url' => route('lego.pamtechoga.zones.index')],
            ['title' => $model?->name ?: 'Untitled'],
        ]"
    x-data=""
    x-on:keydown.meta.s.window.prevent="$wire.call('save')" {{-- For Mac --}}
    x-on:keydown.ctrl.s.window.prevent="$wire.call('save')" {{-- For PC  --}}
>
    <x-slot name="actions">
        @include('lego::models._includes.forms.page-actions')
    </x-slot>
    <x-lego::feedback.errors class="sh-mb-4" />

    <x-fab::layouts.main-with-aside>
        <x-fab::layouts.panel>

            <x-fab::forms.input
                wire:model="model.name"
                label="Zone name"
                help="This is the name of the zone."
            />

        </x-fab::layouts.panel>

        <x-fab::layouts.panel
            title="Stations"
            description="Below are the stations in this zone."
            class="sh-mt-4"
        >
            <x-fab::lists.stacked
            >
                @foreach($this->model->stations as $data)
                    <div
                        x-sortable.products.item="{{ $data->id }}"
                    >
                        <x-fab::lists.stacked.grouped-with-actions
                            :title="$data?->company_name"
                            description="{{ $data->updated_at->toFormattedDateString() }}"
                        >
                            <x-slot name="avatar">
                                <div class="flex">
                                    <x-fab::elements.icon icon="dots-vertical" x-sortable.products.handle
                                                          class="sh-h-5 sh-w-5 sh-text-gray-300 sh--mr-2"/>
                                    <x-fab::elements.icon icon="dots-vertical" x-sortable.products.handle
                                                          class="sh-h-5 sh-w-5 sh-text-gray-300 sh--ml-1.5"/>
                                </div>
                            </x-slot>
                            <x-slot name="actions">
                                <x-fab::elements.button
                                    size="xs"
                                    type="link"
                                    :url="route('lego.pamtechoga.fuel-prices.edit', $data)"
                                >
                                    View
                                </x-fab::elements.button>
                            </x-slot>
                        </x-fab::lists.stacked.grouped-with-actions>
                    </div>
                @endforeach
            </x-fab::lists.stacked>
        </x-fab::layouts.panel>

        <x-slot name="aside">
            @include('pamtechoga::models.components.timestamp')
        </x-slot>

    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
