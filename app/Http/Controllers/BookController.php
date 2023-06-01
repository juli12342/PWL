<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\BookAuthor;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Export\ExportBooks;
use Maatwebsite\Excel\Facades\Excel;
// use Barryvdh\DomPDF\PDF as DomPDFPDF;

class BookController extends BaseController
{
    /**
     * Fungsi untuk menampilkan semua data books
     */
    public function index()
    {
        $books = Book::query()
            ->with(['publisher', 'authors'])
            ->when(request('search'), function ($query) {
                $searchTerm = '%' . request('search') . '%';
                $query->where('title', 'like', $searchTerm)
                    ->orWhere('code', 'like', $searchTerm)
                    ->orWhereHas('publisher', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('authors', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', $searchTerm);
                    });
            })->paginate(10);
        session()->flashInput(request()->input());
        return view('books/index', [
            'books' => $books
        ]);
    }

    public function print()
    {
        $books = Book::query()
            ->with(['publisher', 'authors'])
            ->when(request('search'), function ($query) {
                $searchTerm = '%' . request('search') . '%';
                $query->where('title', 'like', $searchTerm)
                    ->orWhere('code', 'like', $searchTerm)
                    ->orWhereHas('publisher', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('authors', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', $searchTerm);
                    });
            })->get();
        $filename = "books_" . date('Y-m-d-H-i-s') . ".pdf";
        $pdf = Pdf::loadView('books/print', ['books' => $books]);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream($filename);
    }

    public function printDetail($bookId)
    {
        $book = Book::findOrFail($bookId);
        $filname = "book_" . $book->code . "_" . date('Y-m-d H:i:s') . ".pdf";
        $pdf = Pdf::loadView('books/printDetail', ['book' => $book]);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream($filname);
    }

    public function excel()
    {
        return Excel::download(new ExportBooks, 'books.xlsx');
    }

    /**
     * Function untuk menampilkan form tambah buku
     */
    public function create()
    {
        $this->superadminOnly();
        $authors = Author::all();
        $publishers = Publisher::all();
        return view('books/form', [
            'publishers' => $publishers,
            'authors' => $authors,
        ]);
    }

    /**
     * Function untuk memproses data buku ke database
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'code' => 'required | max:4 | unique:books,code',
                'title' => 'required | max:255',
                'id_publisher' => 'required'
            ]);

            $code = $request->code;
            $title = $request->title;
            $idPublisher = $request->id_publisher;
            $book = Book::create([
                'code' => $code,
                'title' => $title,
                'id_publisher' => $idPublisher
            ]);
            foreach ($request->author as $authorId) {
                BookAuthor::create([
                    'id_book' => $book->id,
                    'id_author' => $authorId,
                ]);
            }
            DB::commit();
            return redirect(route('books.index'))->with('success', 'Buku berhasil ditambah ');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect(route('books.index'))->with('errors', 'Buku Gagal ditambah');
        }
    }

    public function confirmDelete($bookId)
    {
        #ambill data dari Id
        $book = Book::findOrFail($bookId);
        return view('books/delete-confirm', [
            'book' => $book,
        ]);
    }

    public function delete(Request $request)
    {
        $bookId = $request->id;
        $book = Book::findOrFail($bookId);
        $book->delete();
        return redirect(route('books.index'))->with('success', 'Buku Berhasil Dihapus');
    }

    public function edit($bookId)
    {
        #ambil data buku by Id
        $book = Book::findOrFail($bookId);
        $publishers = Publisher::all();
        return view('books/form-update', [
            'book' => $book,
            'publishers' => $publishers
        ]);
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required | max:255',
            'id_publisher' => 'required'
        ]);

        $bookId = $request->id;
        $book = Book::findOrFail($bookId)->update([
            'title' => $request->title,
        ]);
        // $book->update([
        //     'title' => $request->title,
        // ]);
        return redirect(route('books.index'))->with('success', 'Buku Berhasil Diubah');
    }
}
