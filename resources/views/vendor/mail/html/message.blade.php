<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
    <img
    src="{{ config('app.url') }}/assets/NewsMaker-23-logo.png"
    alt="Newsmaker23"
    style="height:40px; display:block; margin:0 auto;"
    >
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{!! $subcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} Newsmaker Indonesia. All rights reserved.<br>
<a href="https://newsmaker.id">newsmaker.id</a>
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
