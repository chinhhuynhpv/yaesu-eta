<div class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" data-delete-modal>
    <form method="post" action="">
        @csrf
        @method("DELETE")
        <input name="id" type="hidden" value="">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Confirmation")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div data-title>{{__("Are you use that you want to delete :name?")}}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-squrare" data-dismiss="modal">{{__("Close")}}</button>
                    <button type="submit" class="btn btn-delete btn-submit">{{__("Delete")}}</button>
                </div>
            </div>
        </div>
    </form>
</div>

@section('script')
    <script>
        $('[data-btn-delete]').click(function(e) {
            e.preventDefault();

            const $this = $(this);
            const action = $this.closest('[data-delete-url]').data('delete-url');
            const id = $this.data('id');
            const name = $this.data('name');

            const $modal = $('[data-delete-modal]');
            $modal.find('form').attr('action', action);
            $modal.find('input[name="id"]').val(id);

            const $title = $modal.find('[data-title]');
            $title.text($title.text().replace(':name', name));

            $('[data-delete-modal]').modal('show');
        })
    </script>
@stop

