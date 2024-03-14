@section('script')
    <script>
        $('[data-btn-clear]').click(function(ev) {
            ev.preventDefault();
            $(this).closest('form').find('input, select').not('[type="submit"], [type="reset"]').val('').removeAttr('selected');
        });
    </script>
@append
