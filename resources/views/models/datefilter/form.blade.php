<div>
    <div class="grid grid-cols-3 gap-4">
        <x-fab::forms.datepicker
            wire:model="startDate"
            label="Start Date"
        />
        <x-fab::forms.datepicker
            wire:model="endDate"
            label="End Date"
        />
        <div class="flex flex-col justify-end">
            <div>
                <x-fab::elements.button type="button" wire:click="applyFilter">Apply filter</x-fab::elements.button>
            </div>
        </div>
    </div>

</div>
