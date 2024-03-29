<x-fab::layouts.page
    :title="$model?->plate_number ?: 'Untitled'"
    :breadcrumbs="[
            ['title' => 'Home', 'url' => route('lego.dashboard')],
            ['title' => 'Trucks', 'url' => route('lego.pamtechoga.trucks.index')],
            ['title' => $model?->plate_number ?: 'Untitled'],
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

{{--            <x-fab::forms.select--}}
{{--                wire:model="model.product_id"--}}
{{--                label="Product"--}}
{{--                help="This is the product being ordered."--}}
{{--            >--}}
{{--                <option value="0">-- Choose the Product</option>--}}
{{--                @foreach($this->allProducts() as $data)--}}
{{--                    <option value="{{ $data->id }}"> {{ $data->type }} </option>--}}
{{--                @endforeach--}}
{{--            </x-fab::forms.select>--}}

            <x-fab::forms.input
                wire:model="model.plate_number"
                label="Plate Number"
                help="This is the licensed plate number of the truck, this also be used to identify the assigned driver."
            />

            <x-fab::forms.input
                wire:model="model.volume_capacity"
                label="Volume Capacity (Litres)"
                help="The full capacity of the truck."
            />

        </x-fab::layouts.panel>

        <x-slot name="aside">
            @include('pamtechoga::models.components.timestamp')
            <x-lego::media-panel :model="$model" />
            <div class="mt-8">
                @if($this->model->chart)
                    <x-fab::elements.button type="link" :url="$this->model->chart" target="_blank">Preview Chart</x-fab::elements.button>
                @endif
            </div>
        </x-slot>

    </x-fab::layouts.main-with-aside>
</x-fab::layouts.page>

@push('styles')
    <link href="{{ asset('vendor/pamtechoga/css/pamtechoga.css') }}" rel="stylesheet">
@endpush
