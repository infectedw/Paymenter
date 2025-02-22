@php
    if(!isset($config->value)) {
        $config->value = '';
    }
@endphp
@if ($config->type == 'text' || $config->type == 'number' || $config->type == 'email' || $config->type == 'password')
    <x-input type="{{ $config->type }}" placeholder="{{ $config->placeholder ?? ucfirst($config->name) }}"
        name="{{ $config->name }}" id="{{ $config->name }}"
        value="{{ old($config->name) ?? $config->value }}"
        label="{{ $config->friendlyName ?? ucfirst($config->name) }}" required />
@elseif($config->type == 'textarea')
    <x-input type="textarea" placeholder="{{ $config->placeholder ?? ucfirst($config->name) }}"
        name="{{ $config->name }}" id="{{ $config->name }}"
        value="{{ old($config->name) ?? $config->value }}"
        label="{{ $config->friendlyName ?? ucfirst($config->name) }}" required />
@elseif($config->type == 'dropdown')
    <x-input type="select" label="{{ ucfirst($config->name) }}"
        name="{{ $config->name }}" id="{{ $config->name }}" required>
        @foreach ($config->options as $option)
            <option value="{{ $option->value }}" @if (old($config->name) == $option || $config->value == $option) selected @endif>
                {{ $option->name }}
            </option>
        @endforeach
    </x-input>
@elseif($config->type == 'boolean')
    <x-input type="checkbox" label="{{ ucfirst($config->name) }}"
        value="1"
        name="{{ $config->name }}" id="{{ $config->name }}" required
        @if (old($config->name) || $config->value) checked @endif />
@endif

