@extends('layout.main')
@section('title', 'Update Data Author')
@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{route('authors.update')}}">
            @csrf
            <input type="hidden" name="id" value="{{ $author->id }}">
            <div class="form-group">
                <label for="">Nama Author</label>
                <input class="form-control @error('name') is-invalid @enderror"
                 type="text" value="{{ $author->name }}" name="name"  />
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <button class="btn btn-warning" type="button"
             onclick="location.href='{{ route('authors.index') }}'">
                <i class="fas fa-caret-left"></i> Kembali
            </button>
            <button class="btn btn-success" type="submit">
                <i class="fa fa-save"></i> Update
            </button>
        </form>
    </div>
</div>

@endsection
    
