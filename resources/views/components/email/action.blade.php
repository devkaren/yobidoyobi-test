<div class="action-container">
<a href="{{ $url }}" class="action">
{{ $slot }}
</a>
</div>
<div class="paragraph">
{{ trans('messages.mail.button_issues') }}
<div class="fallback-link-container">
<a href="{{ $url }}" target="__blank" class="fallback-link">
{{ $url }}
</a>
</div>
</div>
