@if (Session::has('success'))
<div class="alert alert-success">
    <i class="fa fa-check-circle"></i>
    <strong>{{ Session::get('success') }}</strong>
</div>
@endif

@if (Session::has('warning'))
<div class="alert alert-warning">
    <i class="fa fa-fa"></i>
    <strong>{{ Session::get('warning') }}</strong>
</div>
@endif

@if (count($errors) > 0)
<div class="alert alert-danger">
    <i class="fa fa-frown-o"></i>
    <strong>De volgende fouten zijn opgetreden:</strong>
    <ul>
        @foreach ($errors->all() as $error)
        <li><h5 class="nomargin">{{ $error }}</h5></li>
        @endforeach
    </ul>
</div>
@endif
