@extends('admin.body')

@section('container')
    <div class="mt-3">
        <div>
            <a class="btn" href="{{route("{$prefixRouteName}List")}}">{{__("Back")}}</a>
        </div>
        <div class="row mt-3">
            <div class="col-md-8">
                <h3>{{$title}}</h3>
            </div>
            <div class="col-md-2">
                <a class="btn btn-update" href="{{route("{$prefixRouteName}Edit", ['id' => $item->id])}}">{{__("Edit")}}</a>
            </div>
        </div>
        <table class="table table-borderless">
            <thead>
            <tr>
                <th scope="col">{{__("Label")}}</th>
                <th scope="col">{{__("Information")}}</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($item->fieldNames() as $name => $title)
                    <tr>
                        <td>{{__($title)}}</td>
                        <td>{{$item->$name}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
