@if ($message = Session::get('errorx'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Gagal Menyimpan!!!</strong>
    <ul>
        @foreach ($message->all() as $m)
            <li>{{ $m }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
</div>
@endif