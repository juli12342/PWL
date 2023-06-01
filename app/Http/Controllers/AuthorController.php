<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Export\ExportAuthors;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::query()
            ->when(request('search'), function ($query) {
                $searchTerm = '%' . request('search') . '%';
                $query->where('name', 'like', $searchTerm);
            })->paginate(5);
        return view('author/index', [
            'authors' => $authors
        ]);
    }

    public function print()
    {
        $authors = Author::all();
        $filename = "authors_" . date('Y-m-d-H-i-s') . ".pdf";
        $pdf = Pdf::loadView('author/print', ['authors' => $authors]);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream($filename);
    }

    public function excel()
    {
        return Excel::download(new ExportAuthors, 'authors.xlsx');
    }

    public function create()
    {
        return view('author/form');
    }

    public function posts(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required | max:255'
        ]);

        $name = $request->name;
        Author::create([
            'name' => $name
        ]);
        return redirect(route('authors.index'))->with('success', 'Author Berhasil Ditambah');
    }

    public function confirmDelete($authorId)
    {
        $author = Author::FindOrFail($authorId);
        return view('/author/delete-confirm', [
            'author' => $author,
        ]);
    }

    public function delete(Request $request)
    {
        $authorId = $request->id;
        $author = Author::FindOrFail($authorId);
        $author->delete();
        return redirect(route('authors.index'))->with('success', 'Author Berhasi Dihapus');
    }

    public function edit($authorId)
    {
        $author = Author::FindOrFail($authorId);
        return view('/author/form-update', [
            'author' => $author,
        ]);
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required | max:255',
        ]);

        $authorId = $request->id;
        $author = Author::FindOrFail($authorId)->update([
            'name' => $request->name
        ]);
        return redirect(route('authors.index'))->with('success', 'Author Berhasi Diupdate');
    }
}
