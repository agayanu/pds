@extends('layouts.app')

@section('header')
<style>
.required {
    color: red;
    display: inline;
}
.spinner-border {
    display: none;
}
</style>
@endsection

@section('content')
@include('layouts.flash-message')
@if($pass)
<div class="card mb-3">
    <div class="card-header">Password anda masih 123456! Update Password Anda!</div>
    <div class="card-body">
        <form action="{{route('home.customer-update-pass')}}" method="post" id="formUpdatePass" novalidate>
        @csrf
            <div class="row">
                <div class="col-sm-12 mb-3">
                    <label class="form-label">Password Baru <div class="required">*</div></label>
                    <div class="input-group has-validation">
                        <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>
                        <span class="input-group-text" id="span-lock">
                            <i class="icon cil-lock-locked" id="span-lock-i"></i>
                        </span>
                    </div>
                </div>
                <div class="col-sm-12 mb-3">
                    <label class="form-label">Ulangi Password Baru <div class="required">*</div></label>
                    <div class="input-group has-validation">
                        <input class="form-control" type="password" name="password_confirmation" id="passwordConfirm" placeholder="Konfirmasi Password" required>
                        <span class="input-group-text" id="span-lock-confirm">
                            <i class="icon cil-lock-locked" id="span-lock-i-confirm"></i>
                        </span>
                    </div>
                </div>
                <div class="col-sm-12">
                    <button class="btn btn-sm btn-primary" type="submit" id="submitUpdatePass">Simpan</button>
                    <div class="spinner-border text-info" role="status" id="loadUpdatePass">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<div class="card mb-3">
    <div class="card-body">
        Selamat datang {{ Auth::user()->name }}
        <div class="row mt-3">
            <div class="col">
                <a href="{{route('violation')}}" class="btn btn-primary">Pelanggaran</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="{{ asset('jquery/jquery-3.6.1.min.js') }}"></script>
<script>
(function () {
    'use strict';
    const formUpdatePass = document.querySelectorAll('#formUpdatePass');
    Array.prototype.slice.call(formUpdatePass)
    .forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        
        if (form.checkValidity() === true) {
            $('#submitUpdatePass').hide();
            $('#loadUpdatePass').show();
        }
        form.classList.add('was-validated')
      }, false)
    });
})();

$('#span-lock').click(function() {
    var input = $('#password').attr('type');
    if(input == 'text') {
        $('#password').attr('type', 'password');
        $('#span-lock-i').addClass('cil-lock-locked');
        $('#span-lock-i').removeClass('cil-lock-unlocked');
    } else {
        $('#password').attr('type', 'text');
        $('#span-lock-i').addClass('cil-lock-unlocked');
        $('#span-lock-i').removeClass('cil-lock-locked');
    }
});
$('#span-lock-confirm').click(function() {
    var input = $('#passwordConfirm').attr('type');
    if(input == 'text') {
        $('#passwordConfirm').attr('type', 'password');
        $('#span-lock-i-confirm').addClass('cil-lock-locked');
        $('#span-lock-i-confirm').removeClass('cil-lock-unlocked');
    } else {
        $('#passwordConfirm').attr('type', 'text');
        $('#span-lock-i-confirm').addClass('cil-lock-unlocked');
        $('#span-lock-i-confirm').removeClass('cil-lock-locked');
    }
});
</script>
@endsection