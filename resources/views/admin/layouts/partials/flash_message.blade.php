@if ($message = Session::get('success'))
<script>showAlert('{!! $message !!}', 'success');</script>
@endif

@if ($message = Session::get('error'))
<script>showAlert('{!! $message !!}', 'error');</script>
@endif

@if ($message = Session::get('warning'))
<script>showAlert('{!! $message !!}', 'warning');</script>
@endif

@if ($message = Session::get('info'))
<script>showAlert('{!! $message !!}', 'info');</script>
@endif

@if ($errors->any())
    @if ($message = $errors->get('throw'))
    <div class="log-throw" style="display: none;">
        <ul class='text-left'><li>{!! implode("</li><li>", $message) !!}</li></ul>
    </div>
    @endif
<script>showAlert("{!! '<ul class=\'text-left\'><li>' . implode('</li><li>', $errors->all()) . '</li></ul>' !!}", 'warning');</script>
@endif
