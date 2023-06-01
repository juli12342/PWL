@extends('layout.main')
@section('title', 'Update Data Buku')
@section('content')
{{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{route('books.update')}}">
            @csrf
            <input type="hidden" name="id" value="{{ $book->id }}">
            <div class="form-group">
                <label for="">Kode</label>
                <input class="form-control" type="text" value="{{ $book->code }}" name="code" required readonly disabled />
            </div>
            <div class="form-group">
                <label for="">Judul</label>
                <input class="form-control @error('title') is-invalid @enderror" type="text" value="{{ $book->title }}" name="title"  />
                @error('title')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Publisher</label>
                <select name="id_publisher" class="form-control @error('id_publisher') is-invalid @enderror">
                    @foreach ($publishers as $p)
                        <option {{ $p -> id == $book->id_publisher ? 'selected' : '' }} value="{{ $p -> id }}">
                            {{ $p -> name }}
                        </option>
                    @endforeach
                </select>
                @error('id_publisher')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <button class="btn btn-warning" type="button" onclick="location.href='{{ route('books.index') }}'">
                <i class="fas fa-caret-left"></i> Kembali
            </button>
            <button class="btn btn-success" type="submit">
                <i class="fa fa-save"></i> Update
            </button>
        </form>
    </div>
</div>

@endsection
    
