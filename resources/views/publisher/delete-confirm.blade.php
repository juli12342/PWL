@extends('layout.main')
@section('title', 'Delete Data Publisher')
@section('content')
<div class="card">
    <div class="card-header">
        <h2>Apakah Anda Yakin Ingin Menghapus Data Ini ?</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{route('publishers.delete')}}">
            @csrf
            <input type="hidden" name="id" value="{{ $publisher->id }}">
            <div class="form-group">
                <h5>Kode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : {{ $publisher->id }}</h5>
            </div>
            <div class="form-group">
                <h5>Judul&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : {{ $publisher->name }}</h5>
            </div>
            <button class="btn btn-warning" type="button" onclick="location.href='{{ route('publisher.index') }}'">
                <i class="fas fa-caret-left"></i> Kembali
            </button>
            <button class="btn btn-danger" type="submit">
                <i class="fa fa-trash"></i> Hapus
            </button>
        </form>
    </div>
</div>
@endsection
    
