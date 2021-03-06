@extends('layouts.template')
@section('title', 'Halaman Salah 403 Forbiden')
@section('content')


    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Maaf Ada Kesalahan</h2>
                    <h5 class="text-white op-7 mb-2"> Halaman Sementara tidak dapat di tampilkan</h5>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="#" class="btn btn-white btn-border btn-round mr-2"></a>
                    <a href="#" to="#" class="tambah btn btn-secondary btn-round"> </a>
                </div>
            </div>
        </div>
    </div>


    <div class="page bg-light">
        <div class="container-fluid my-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-group form-show-validation row">
                        <h1>Maaf Saat ini server sedang error , Silahkan hubungi administrator untuk perbaikan .</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
