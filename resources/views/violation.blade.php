@extends('layouts.app')

@section('header')
@include('layouts.header-datatable')
@include('layouts.datetimepicker-header')
@include('layouts.tooltips')
<link rel="stylesheet" href="{{asset('select2-4.1.0-rc.0/css/select2.min.css')}}">
<style>
.required {
    color: red;
    display: inline;
}
.spinner-border {
    display: none;
}
#alertCreate {
    display: none;
}
/* begin select2 */
span.select2 {
    width: 100% !important;
}
span.select2-selection {
    min-height: 38px !important;
}
span.select2-selection__rendered {
    padding: 0.275rem 2.25rem 0.375rem 0.75rem !important;
}
/* end select2 */
</style>
@endsection

@section('content')
@include('layouts.flash-message')
<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div>Pelanggaran</div>
            <button class="btn btn-sm btn-primary" type="button" data-coreui-toggle="modal" data-coreui-target="#create"><i class="cil-plus" style="font-weight:bold"></i> Tambah</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-10">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-sm-2 mb-3">
                            <label class="form-label">Dari Tanggal <div class="required">*</div></label>
                            <input type="text" class="form-control" name="date_first" id="date_first" placeholder="dd-mm-yyyy" value="{{$dateFirst}}" required>
                            <div class="invalid-feedback">Dari Tanggal Wajib Diisi!</div>
                        </div>
                        <div class="col-sm-2 mb-3">
                            <label class="form-label">Sampai Tanggal <div class="required">*</div></label>
                            <input type="text" class="form-control" name="date_last" id="date_last" placeholder="dd-mm-yyyy" value="{{$dateLast}}" required>
                            <div class="invalid-feedback">Sampai Tanggal Wajib Diisi!</div>
                        </div>
                        <div class="col-sm-2 mb-3 align-self-end">
                            <button class="btn btn-sm btn-primary">Tampil</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-2 align-self-end mb-3">
                <form action="{{ route('violation.download') }}" method="get" style="text-align: right">
                    <input type="hidden" name="date_first_d" value="{{$dateFirst}}">
                    <input type="hidden" name="date_last_d" value="{{$dateLast}}">
                    <button class="btn btn-sm btn-success tooltips" type="submit">
                        <i class="cil-cloud-download" style="font-weight: bold;font-size: 20px;"></i> Excel
                        <span class="tooltiptext">Download Excel</span>
                    </button>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="display nowrap" id="datatable" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Pasal</th>
                        <th>Catatan</th>
                        <th>User</th>
                        <th>Dibuat</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible" role="alert" id="alertCreate">
                    <div id="alertCreateMsg"></div>
                    <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
                </div>
                <form action="" method="post" id="formCreate" enctype="multipart/form-data" novalidate>
                @csrf
                    <div class="row">
                        <div class="col-sm-12 mb-3 col-name">
                            <label class="form-label">Nama <div class="required">*</div></label>
                            <select name="name" id="name" class="form-select" required></select>
                            <div class="invalid-feedback">Nama Wajib Diisi!</div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label class="form-label">Pasal <div class="required">*</div></label>
                            <select name="article[]" id="article" class="form-select" multiple="multiple" required></select>
                            <div class="invalid-feedback">Pasal Wajib Diisi!</div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="remarks" rows="3"></textarea>
                            <div class="invalid-feedback">Catatan Wajib Diisi!</div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label class="form-label">Bukti <div class="required">*</div></label>
                            <input class="form-control" type="file" name="evidence[]" id="evidence" multiple required>
                            <div class="invalid-feedback" id="evidenceInvalid">Bukti Wajib Diisi!</div>
                        </div>
                        <div class="col-sm-12">
                            <button class="btn btn-sm btn-primary" type="submit" id="submitCreate">Simpan</button>
                            <div class="spinner-border text-info" role="status" id="loadCreate">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="del" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="formDel" novalidate>
                @csrf
                @method('DELETE')
                    <h4 class="text-danger" id="delText"></h4>
                    <div class="row">
                        <div class="col-sm-12">
                            <button class="btn btn-sm btn-primary" type="submit" id="submitDel">Ya</button>
                            <div class="spinner-border text-info" role="status" id="loadDel">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="{{ asset('jquery/jquery-3.6.1.min.js') }}"></script>
@include('layouts.datetimepicker-footer')
<script>
$('#date_first').datetimepicker({
    locale: 'id',
    format: 'DD-MM-YYYY'
});
$('#date_last').datetimepicker({
    locale: 'id',
    format: 'DD-MM-YYYY'
});
</script>
@include('layouts.footer-datatable')
<script>
$(function () {
    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('violation.data', ['date_first'=>$dateFirst,'date_last'=>$dateLast]) }}".replace(/&amp;/g, "&"),
        columns: [
            {data: 'name', name: 'name'},
            {data: 'class', name: 'class'},
            {data: 'article', name: 'article'},
            {data: 'remarks', name: 'remarks'},
            {data: 'username', name: 'username'},
            {data: 'createdAt', name: 'createdAt'},
            {data: 'evidence', name: 'evidence', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        responsive: true
    });
});

(function () {
    'use strict';
    const forms = document.querySelectorAll('#formCreate');
    const formsDel = document.querySelectorAll('#formDel');
    Array.prototype.slice.call(forms)
    .forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        
        if (form.checkValidity() === true) {
            $('#submitCreate').hide();
            $('#loadCreate').show();
        }
        form.classList.add('was-validated')
      }, false)
    });
    Array.prototype.slice.call(formsDel)
    .forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        
        if (form.checkValidity() === true) {
            $('#submitDel').hide();
            $('#loadDel').show();
        }
        form.classList.add('was-validated')
      }, false)
    });
})();
</script>
<script src="{{asset('select2-4.1.0-rc.0/js/select2.min.js')}}"></script>
<script>
$("#name").select2({
    dropdownParent: $("#create"),
    ajax: {
        url: "{{route('violation.name-search')}}",
        type: 'GET',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term,
                page: params.current_page
            };
        },
        processResults: function(data, params) {
            params.current_page = params.current_page || 1;
            return {
                results: data[0].data,
                pagination: {
                    more: (params.current_page * 30) < data[0].total
                }
            };
        },
        error: function(xhr, status, error) {
            $('#alertCreateMsg').text(xhr.responseJSON);
            $('#alertCreate').show();
        },
        autoWidth: true,
        cache: true
    },
    minimumInputLength: 1,
    templateResult: formatName,
    templateSelection: formatNameSelection,
    placeholder: 'Cari Nama...'
});

$("#article").select2({
    dropdownParent: $("#create"),
    ajax: {
        url: "{{route('violation.article-search')}}",
        type: 'GET',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term,
                page: params.current_page
            };
        },
        processResults: function(data, params) {
            params.current_page = params.current_page || 1;
            return {
                results: data[0].data,
                pagination: {
                    more: (params.current_page * 30) < data[0].total
                }
            };
        },
        error: function(xhr, status, error) {
            $('#alertCreateMsg').text(xhr.responseJSON);
            $('#alertCreate').show();
        },
        autoWidth: true,
        cache: true
    },
    minimumInputLength: 1,
    templateResult: formatNameArticle,
    templateSelection: formatNameArticleSelection,
    allowClear: true,
    placeholder: 'Cari Pasal...'
});

function formatName(name) {
    if (name.loading) {
        return name.text;
    }

    var $container = $(
        "<div class='select2-result-name clearfix'>" +
        "<div class='select2-result-name__name'></div>" +
        "<div class='select2-result-name__position'></div>" +
        "</div>" +
        "</div>" +
        "</div>"
    );

    $container.find(".select2-result-name__name").text(name.name);
    $container.find(".select2-result-name__position").text(name.Class);

    return $container;
}

function formatNameArticle(name) {
    if (name.loading) {
        return name.text;
    }

    var $container = $(
        "<div class='select2-result-name clearfix'>" +
        "<div class='select2-result-name__position'></div>" +
        "<div class='select2-result-name__name'></div>" +
        "</div>" +
        "</div>" +
        "</div>"
    );

    $container.find(".select2-result-name__position").text(name.ItemDesc);
    $container.find(".select2-result-name__name").text(name.Group + ' - Pasal ' + name.Article);

    return $container;
}

function formatNameSelection(name) {
    return name.name || name.text;
}

function formatNameArticleSelection(name) {
    return name.ItemDesc || name.text;
}

$(document).on('select2:open', () => {
    let allFound = document.querySelectorAll('.select2-container--open .select2-search__field');
    $(this).one('mouseup keyup',()=>{
        setTimeout(()=>{
            allFound[allFound.length - 1].focus();
        },0);
    });
});

$('#evidence').change(function(){
    const allowedTypes = ['image/jpeg','image/png','video/quicktime','video/mp4','application/pdf','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    let hasError = false;
    let errorMsg = '';
    const maxSize = 30097152; // 30MB
    
    if(this.files.length === 0) {
        $('#evidenceInvalid').text('Bukti Wajib Diisi!');
        $('#evidence').addClass('is-invalid');
        return;
    }
    
    for(let i = 0; i < this.files.length; i++) {
        const file = this.files[i];
        
        if($.inArray(file.type, allowedTypes) == -1) {
            errorMsg = 'File tidak termasuk: .jpg, .png, .pdf, .xlsx, .docx!';
            hasError = true;
            break;
        }
        
        if(file.size > maxSize) {
            errorMsg = 'Ada file yang melebihi 30MB!';
            hasError = true;
            break;
        }
    }
    
    if(hasError) {
        $('#evidenceInvalid').text(errorMsg);
        $('#evidence').addClass('is-invalid');
        $('#evidence').val('');
    } else {
        $('#evidenceInvalid').text('Bukti Wajib Diisi!');
        $('#evidence').removeClass('is-invalid');
    }
});
</script>
@endsection