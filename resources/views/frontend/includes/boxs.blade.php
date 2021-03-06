@if (!empty($warning))
  <div class="alert alert-info">{{$warning}}</div>
@endif
@if (Session::has('msg'))
  <div class="alert alert-success">{{Session::get('msg')}}</div>
@endif
@if (Session::has('success'))
  <div class="alert alert-success">{{Session::get('success')}}</div>
@endif
@if (Session::has('fail'))
  <div class="alert alert-danger">{{Session::get('fail')}}</div>
@endif
@if (Session::has('status'))
  <div class="alert alert-info">{{Session::get('status')}}</div>
@endif
