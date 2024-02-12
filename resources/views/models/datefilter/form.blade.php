<div>
    <div class="grid grid-cols-3 gap-4">
        <input type="date" wire:model="startDate" class="rounded-lg border-2 border-gray-300" />
        <input type="date" wire:model="endDate" class="rounded-lg border-2 border-gray-300" />

        <div class="flex flex-col justify-end">
            <div>
                <x-fab::elements.button type="button" wire:click="applyFilter">Apply filter</x-fab::elements.button>
            </div>
        </div>
    </div>

</div>
