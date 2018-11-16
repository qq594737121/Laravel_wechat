<html>
@if(!empty($result))
    Error_id:{{$result->id}}<br/>
    Error_detail:<br/>
    {{$result->result}}
@endif
</html>
