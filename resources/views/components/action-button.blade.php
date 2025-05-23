<form method="POST" action="{{ $action }}" {{ $attributes }}>
    @csrf
    @if($method !== 'POST')
    @method($method)
    @endif
    <button type="submit" class="{{ $buttonClass }}">
        {{ $slot }}
    </button>
</form>