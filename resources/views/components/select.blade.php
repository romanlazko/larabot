@props(['options'])

<select {!! $attributes->merge(['class' => 'border-gray-300 p-2 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
    @foreach($options as $label => $value)
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>