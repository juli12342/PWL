@extends('layout.main')
@section('title', 'Tambah Publisher')
@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('publishers.posts') }}">
                @csrf
                <div class="form-group">
                    <label for="">Nama Publisher</label>
                    <input class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                     type="text" name="name" />
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button class="btn btn-success mt-2" type="submit">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>
@endsection
