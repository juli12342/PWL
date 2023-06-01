@extends('layout.main')
@section('title', 'Data Authors')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Success!</h5>
            {{ $message }}
        </div>
    @endif
    <a class="btn btn-primary mb-2" href="{{ route('authors.create') }}">Tambah Author</a>
    <a target="_blank" class="btn btn-danger mb-2" href="{{ route('authors.print') }}">Export PDF</a>
    <a target="_blank" class="btn btn-success mb-2" href="{{ route('authors.export.excel') }}">Export Excel</a>
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <form action="">
                    <input value="{{ old('search') }}" placeholder="Search Book" type="text" name="search"
                        class="from-control">
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table" width='100%'>
                <thead>
                    <tr>
                        <th class="col-1">No</th>
                        <th class="col-2">id</th>
                        <th class="col-6">Nama Author</th>
                        <th class="col-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @forelse ($authors as $author)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $author->id }}</td>
                            <td>{{ $author->name }}</td>
                            <td>
                                <a class="btn btn-warning" href="{{ route('authors.edit', [$author->id]) }}">
                                    <i class="fa fa-pencil-alt"></i> 
                                </a> |
                                <a class="btn btn-danger" href="{{ route('authors.del.confirm',[$author->id]) }}">
                                    <i class="fa fa-trash"></i> 
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td style="text-align: center;" colspan="4"><b>Data Kosong</b></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $authors->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>

@endsection
