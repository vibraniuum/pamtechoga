@if($model->exists)
    <div class="grid grid-cols-2 gap-4 mt-6">
        <x-fab::forms.input
            value="{{ $model->created_at->toFormattedDateString() }}"
            label="CREATED ON"
            disabled="true"
        />
        <x-fab::forms.input
            value="{{ $model->updated_at->toFormattedDateString() }}"
            label="UPDATED ON"
            disabled="true"
        />
    </div>
@endif
