@props(['active'=> false])
<button {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 text-sm font-medium transition-colors rounded-md ' . ($active ? 'text-indigo-600 bg-indigo-50 hover:bg-indigo-100' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50')]) }} aria-current="{{ $active ? 'page' : 'false' }}">
    {{ $slot }}
</button>