@props(['label', 'value', 'icon'])

<div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
    <p class="text-gray-500">
        <i class="fa-solid {{ $icon }} me-2"></i>{{ $label }}
    </p>
    <p class="text-lg font-medium">{{ $value }}</p>
</div>
