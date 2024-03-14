<div class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" data-comfirm-modal>
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
                <div data-title>{{__("Are you use that you want to comfirmed billing :name?")}}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-squrare" data-dismiss="modal">{{__("Close")}}</button>
                <a class="btn btn-update" href="">{{__("Status Update")}}</a>
            </div>
        </div>
    </div>
</div>

@section('script')
    <script>
        $('[data-btn-comfirm]').click(function(e) {
            e.preventDefault();

            const $this = $(this);
            const href = $this.data('href');
            const name = $this.data('name');
            const $modal = $('[data-comfirm-modal]');
            $modal.find('a').attr('href', href);
            const $title = $modal.find('[data-title]');
            $title.text($title.text().replace(':name', name));
            $('[data-comfirm-modal]').modal('show');
        })
    </script>
@stop

