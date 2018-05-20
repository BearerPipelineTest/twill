@php
    $options = method_exists($options, 'map') ? $options->map(function($label, $value) {
        return [
            'value' => $value,
            'label' => $label
        ];
    })->values()->toArray() : $options;

    $note = $note ?? false;
    $placeholder = $placeholder ?? false;
    $required = $required ?? false;
    $default = $default ?? false;

    // do not use for now, but this will allow you to create a new option directly from the form
    $addNew = $addNew ?? false;
    $moduleName = $moduleName ?? null;
    $storeUrl = $storeUrl ?? '';
    $inModal = $fieldsInModal ?? false;
@endphp

@if ($unpack ?? false)
    <a17-singleselect
        label="{{ $label }}"
        @include('cms-toolkit::partials.form.utils._field_name')
        :options='{{ json_encode($options) }}'
        @if ($default) selected="{{ $default }}" @endif
        @if ($required) :required="true" @endif
        @if ($inModal) :in-modal="true" @endif
        @if ($addNew) add-new='{{ $storeUrl }}' @elseif ($note) note='{{ $note }}' @endif
        :has-default-store="true"
        in-store="value"
    >
        @if($addNew)
            <div slot="addModal">
                @php
                    unset($note, $placeholder, $emptyText, $default, $required, $inModal, $addNew, $options);
                @endphp
                @partialView(($moduleName ?? null), 'create', ['renderForModal' => true, 'fieldsInModal' => true])
            </div>
        @endif
    </a17-singleselect>
@elseif ($native ?? false)
    <a17-select
        label="{{ $label }}"
        @include('cms-toolkit::partials.form.utils._field_name')
        :options='{{ json_encode($options) }}'
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        @if ($default) selected="{{ $default }}" @endif
        @if ($required) :required="true" @endif
        @if ($inModal) :in-modal="true" @endif
        @if ($addNew) add-new='{{ $storeUrl }}' @elseif ($note) note='{{ $note }}' @endif
        :has-default-store="true"
        size="large"
        in-store="value"
    >
        @if($addNew)
            <div slot="addModal">
                @php
                    unset($note, $placeholder, $emptyText, $default, $required, $inModal, $addNew, $options);
                @endphp
                @partialView(($moduleName ?? null), 'create', ['renderForModal' => true, 'fieldsInModal' => true])
            </div>
        @endif
    </a17-select>
@else
    <a17-vselect
        label="{{ $label }}"
        @include('cms-toolkit::partials.form.utils._field_name')
        :options='{{ json_encode($options) }}'
        @if ($emptyText ?? false) empty-text="{{ $emptyText }}" @endif
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        @if ($default) :selected="{{ json_encode(collect($options)->first(function ($option) use ($default) {
            return $option['value'] === $default;
        })) }}" @endif
        @if ($required) :required="true" @endif
        @if ($inModal) :in-modal="true" @endif
        @if ($addNew) add-new='{{ $storeUrl }}' @elseif ($note) note='{{ $note }}' @endif
        :has-default-store="true"
        size="large"
        in-store="inputValue"
    >
        @if($addNew)
            <div slot="addModal">
                @php
                    unset($note, $placeholder, $emptyText, $default, $required, $inModal, $addNew, $options);
                @endphp
                @partialView(($moduleName ?? null), 'create', ['renderForModal' => true, 'fieldsInModal' => true])
            </div>
        @endif
    </a17-vselect>
@endif

@unless($renderForBlocks || $renderForModal || (!isset($item->$name) && null == $formFieldsValue = getFormFieldsValue($form_fields, $name)))
@push('vuexStore')
    window.STORE.form.fields.push({
        name: '{{ $name }}',
        value: @if(isset($item) && is_numeric($item->$name)) {{ $item->$name }} @else {!! json_encode($item->$name ?? $formFieldsValue) !!} @endif
    })
@endpush
@endunless
