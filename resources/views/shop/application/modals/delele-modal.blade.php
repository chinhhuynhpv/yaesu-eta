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
                    <button type="submit" class="btn btn-delete btn-square">{{__("Delete")}}</button>
                </div>
            </div>
        </div>
    </form>
</div>
