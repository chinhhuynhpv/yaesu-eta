@extends('operator.body')

@section('container')
    <div class="mt-3">
        <h2 class="">{{__("Sim List")}}</h2>
        
        @include('alert.validate')
        <form method="get" action="{{route("{$prefixRouteName}List")}}" class="framed">
            <div class="row mt-3 mb-3">
                <div class="col-md-6 form-group">
                    <label>{{__("Sim num")}}</label>
                    <input class="form-control" name="sim_num" value="{{$simNum}}">
                </div>
                <div class="col-md-6">
                    <label>{{__("Status")}}</label>
                    <select class="form-control" name="status">
                        <option value="">{{__("All")}}</option>
                        @foreach( $possibleStatuses as $pStatus)
                            <option value="{{$pStatus}}" {{$pStatus == $status ? 'selected' : ''}}>{{__($pStatus)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mt-3 mb-3">
                <div class="col-md-6 form-group">
                    <label>{{__("SIM opening date")}}</label>
                    <input type="date" class="form-control" name="opening_date" value="{{$simOpeningDate}}">
                </div>
                <div class="col-md-6">
                    <label>{{__("Contractor name")}}</label>
                    <input type="text" class="form-control" name="sim_contractor" value="{{$simContractor}}">
                </div>
            </div>
            <div class="rht">
                <input type="reset" class="btn btn-default" value="{{__("Clear")}}" data-btn-clear/>
                <input type="submit" class="btn btn-primary" value="{{__("Search")}}"/>
            </div>
        </form>

        <div class="mt-3 text-right">
            <a class="btn btn-primary" data-btn-import>{{__("Import")}}</a>
            <a class="btn btn-create" href="{{route("{$prefixRouteName}Input")}}">{{__("Create")}}</a>
        </div>

        @if ($list->count() > 0)
            <table class="table table-borderless list list-m" data-delete-url="{{route("{$prefixRouteName}HandleDelete")}}">
                <thead>
                    <tr>
                        <th scope="col">{{__("Sim number")}}</th>
                        <th scope="col">{{__("Career")}}</th>
                        <th scope="col">{{__("Status")}}</th>
                        <th scope="col">{{__("SIM opening date")}}</th>
                        <th scope="col">{{__("SIM contractor")}}</th>
                        <th scope="col">{{__("Line ID")}}</th>
                        <th scope="col">{{__("Action")}}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($list as $key => $item)
                    <tr>
                        <td>{{$item->sim_num}}</td>
                        <td>{{$item->career}}</td>
                        <td>{{$item->status}}</td>
                        <td>{{$item->sim_opening_date}}</td>
                        <td>{{$item->sim_contractor}}</td>
                        <td>{{$item->line->voip_line_id}}</td>
                        <td>
                            <a class="btn btn-detail" href="{{route("{$prefixRouteName}Detail", ['id' => $item->id])}}">{{__("View")}}</a>
                            <a class="btn btn-update" href="{{route("{$prefixRouteName}Edit", ['id' => $item->id])}}">{{__("Update")}}</a>
                            <a class="btn btn-delete" href="" data-id="{{$item->id}}" data-name="{{$item->sim_num}}" data-btn-delete>{{__("Delete")}}</a>                            
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $list->links() }}
        @else
            <div class="mt-3">{{__("No result found")}}</div>
        @endif
    </div>
    <div class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" data-import-modal>
        <form method="post" enctype="multipart/form-data" action="{{route("{$prefixRouteName}Import")}}">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__("Choose file")}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input class="form-control" type="file" name="file" required accept=".csv">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__("Close")}}</button>
                        <button type="submit" class="btn btn-primary">{{__("Submit")}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('modals.modal-delete')
@stop

@section('script')
    <script>
        $('[data-btn-import]').click(function(e) {
            e.preventDefault();
            $('[data-import-modal]').modal('show');
        })
    </script>
@append

@include('common.scripts.clear-form')
