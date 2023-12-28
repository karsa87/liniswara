@if ($message = Session::get('success'))
<script>
    toastr.success("{!! $message !!}");
</script>
@endif

@if ($message = Session::get('error'))
<script>
    toastr.error("{!! $message !!}");
</script>
@endif

@if ($message = Session::get('warning'))
<script>
    toastr.warning("{!! $message !!}");
</script>
@endif

@if ($message = Session::get('info'))
<script>
    toastr.info("{!! $message !!}");
</script>
@endif

@if ($errors->any())
    @if ($message = $errors->get('throw'))
    <div class="log-throw" style="display: none;">
        <ul class='text-left'><li>{!! implode("</li><li>", $message) !!}</li></ul>
    </div>
    @endif
<script>
    toastr.warning("{!! '<ul class=\'text-left\'><li>' . implode('</li><li>', $errors->all()) . '</li></ul>' !!}");
</script>
@endif
