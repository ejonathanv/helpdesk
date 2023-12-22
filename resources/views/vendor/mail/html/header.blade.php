@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('img/dynacom_logo.svg') }}" alt="Dynacom" class="logo" />
@else
<img src="{{ asset('img/dynacom_logo.svg') }}" alt="Dynacom" class="logo" />
@endif
</a>
</td>
</tr>
