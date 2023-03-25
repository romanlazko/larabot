@props(['tag', 'color'])

<select {{ $attributes->merge(['class' => "border-2 border-collapse border-".$color."-500 rounded-lg text-center items-center text-sm bg-".$color."-100 text-".$color."-800 inline-block max-w-max px-1"]) }}>
    {{ $slot }}

</select>