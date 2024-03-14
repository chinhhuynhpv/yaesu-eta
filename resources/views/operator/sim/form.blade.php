<form method="post" action="{{route($actionRoute)}}">
    @csrf
    {{method_field($method ?? "POST")}}
    @if ($item->id)
        <input {{$readonly}} type="hidden" name="id" class="form-control col-md-9" value="{{$item->id}}" required>
    @endif
    <div class="form-group row row">
        <label class="col-md-3 mt-1">{{__("Sim num")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="sim_num" class="form-control col-md-9" value="{{$item->sim_num}}" required pattern="[0-9]+">
    </div>
    <div class="form-group row row">
        <label class="col-md-3 mt-1">{{__("Career")}}<span class="asta">*</span></label>
        <select type="text" name="career" class="form-control col-md-9">
            <option value="" {{!$item->career ? "selected" : (($readonly ? 'disabled' : ''))}}>{{__("Choose one")}}</option>
            <option value="Docomo" {{$item->career == "Docomo" ? "selected" : (($readonly ? 'disabled' : ''))}}>Docomo</option>
            <option value="Soft Bank" {{$item->career == "Soft Bank" ? "selected" : (($readonly ? 'disabled' : ''))}}>Soft Bank</option>
            <option value="au" {{$item->career == "au" ? "selected" : (($readonly ? 'disabled' : ''))}}>au</option>
        </select>
    </div>
    <div class="form-group row row">
        <label class="col-md-3 mt-1">{{__("Sim contractor")}}</label>
        <input {{$readonly}} type="text" name="sim_contractor" class="form-control col-md-9" value="{{$item->sim_contractor}}">
    </div>
    <div class="form-group row row">
        <label class="col-md-3 mt-1">{{__("Status")}}<span class="asta">*</span></label>
        <select type="text" name="status" class="form-control col-md-9">
            @php
                $statusReadonly = !empty($statusReadonly) ?? false;
                $status =  $item->getRawValue('status');
            @endphp
            <option value="" {{$readonly || $statusReadonly ? 'disabled' : ""}}>{{__("Choose one")}}</option>
            <option value="1" {{($status == "1" || $statusReadonly) ? "selected" : (($readonly ? 'disabled' : ''))}}>{{__("new")}}</option>
            <option value="2" {{$status == "2" ? "selected" : (($readonly || $statusReadonly ? 'disabled' : ''))}}>{{__("in_use")}}</option>
            <option value="3" {{$status == "3" ? "selected" : (($readonly || $statusReadonly ? 'disabled' : ''))}}>{{__("in_pause")}}</option>
            <option value="4" {{$status == "4" ? "selected" : (($readonly || $statusReadonly ? 'disabled' : ''))}}>{{__("abolition")}}</option>
            <option value="5" {{$status == "5" ? "selected" : (($readonly || $statusReadonly ? 'disabled' : ''))}}>{{__("in_reissue")}}</option>
        </select>
    </div>
    <div class="form-group row row">
        <label class="col-md-3 mt-1">{{__("Sim opening date")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="date" name="sim_opening_date" class="form-control col-md-9" value="{{$item->getRawValue('sim_opening_date')}}" required>
    </div>
    <div class="form-group row row">
        <label class="col-md-3 mt-1">{{__("Remark")}}</label>
        <textarea {{$readonly}} name="remark" class="form-control col-md-9" rows="3">{{$item->remark}}</textarea>
    </div>
    <div class="submit-buttons">
        @if ($readonly)
            <input type="button" class="btn btn-square" value="{{__("Fix")}}" onclick="history.back()">
        @else
            <a class="btn btn-square" href="{{route($cancelRoute)}}">{{__("Cancel")}}</a>
        @endif
        <input {{$readonly}} type="submit" class="btn btn-submit" value="{{$submitText}}">
    </div>
</form>
