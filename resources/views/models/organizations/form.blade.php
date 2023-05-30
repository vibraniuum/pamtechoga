<x-fab::layouts.page
    :title="$model->name ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Organizations', 'url' => route('lego.pamtechoga.organizations.index')],
            ['title' => $model->name ?: 'Untitled'],
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
        <x-fab::layouts.panel title="Organization info">
            <x-fab::forms.input
                label="Name"
                wire:model="model.name"
                help="Name of the organization."
            />

            <x-fab::forms.input
                wire:model="model.slug"
                label="URL and handle (slug)"
{{--                addon="{{ url('') . Route::getRoutes()->getByName('products.show')->getPrefix() . '/' }}"--}}
                help="The URL where this collection can be viewed. Changing this will break any existing links users may have bookmarked."
                :disabled="! $model->exists"
            />

            <x-fab::forms.input
                label="Phone"
                wire:model="model.phone"
                help="Contact phone of the organization"
            />

            <x-fab::forms.input
                label="Email"
                wire:model="model.email"
                help="Contact email address of the organization"
            />

        </x-fab::layouts.panel>

        <x-fab::layouts.panel
            title="Branches"
            description="Below are the branches created and assigned to this organization."
            class="sh-mt-4"
            allow-overflow
            x-on:fab-added="$wire.call('selectProduct', $event.detail[1].key)"
            x-on:fab-removed="$wire.call('unselectProduct', $event.detail[1].key)"
        >

            <x-fab::lists.stacked
{{--                    x-sortable="updateProductsOrder"--}}
{{--                    x-sortable.group="products"--}}
            >
                @foreach($this->model->branches as $data)
                    <div
                        x-sortable.products.item="{{ $data->id }}"
                    >
                        <x-fab::lists.stacked.grouped-with-actions
                            :title="$data->address"
                        >
                            <x-slot name="avatar">
                                <div class="flex">
                                    <x-fab::elements.icon icon="dots-vertical" x-sortable.products.handle class="sh-h-5 sh-w-5 sh-text-gray-300 sh--mr-2" />
                                    <x-fab::elements.icon icon="dots-vertical" x-sortable.products.handle class="sh-h-5 sh-w-5 sh-text-gray-300 sh--ml-1.5" />
                                </div>
                            </x-slot>
                            <x-slot name="actions">
                                <x-fab::elements.button
                                    size="xs"
                                    type="link"
                                    :url="route('lego.pamtechoga.branches.edit', $data)"
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

            <x-lego::media-panel :model="$model" />
        </x-slot>
    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
