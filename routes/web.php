<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Mail\TestMail;
use App\models\Book;
use App\models\Publisher;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Row;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

#Route untuk lupa password
Route::group(['prefix' => 'forgot-password'], function (){
    Route::get('/',[ResetPasswordController::class, 'index'])->name('fp');
    Route::post('/reset', [ResetPasswordController::class, 'reset'])->name('fp.reset');
    Route::get('/new-password',[ResetPasswordController::class, 'newPasswordForm'])->name('fp.new.form');
    Route::get('/new-password',[ResetPasswordController::class, 'newPasswordProses'])->name('fp.new.proses');
});


#Route Login
Route::get('register', ['register', function(){
    return view('login.register');
}])->name('register');
Route::post('register', [LoginController::class, 'prosesRegister'])->name('register.proses');
Route::get('register/verify', [LoginController::class, 'registerVerify'])->name('register.verify');
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login/verify', [LoginController::class, 'verify'])->name('login.verify');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

#Route harus login
Route::group(['middleware' => 'pwl.auth'], function () {
    Route::get('/', function () {
        return view('layout.main');
    });
    #Route books
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books/store', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{bookId}/delete-confirm', [BookController::class, 'confirmDelete'])->name('books.del.confirm');
    Route::post('/books/delete', [BookController::class, 'delete'])->name('books.delete');
    Route::get('/books/{bookId}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::post('/books/update', [BookController::class, 'update'])->name('books.update');

    #Route publishers
    Route::get('/publisher', [PublisherController::class, 'index'])->name('publisher.index');
    Route::get('/publisher/create', [PublisherController::class, 'create'])->name('publishers.create');
    Route::post('/publisher/posts', [PublisherController::class, 'posts'])->name('publishers.posts');
    Route::get('/publisher/{publisherId}/delete-confirm', [PublisherController::class, 'confirmDelete'])->name('publishers.del.confirm');
    Route::post('/publisher/delete', [PublisherController::class, 'delete'])->name('publishers.delete');
    Route::get('/publisher/{publisherId}/edit', [PublisherController::class, 'edit'])->name('publishers.edit');
    Route::post('/publisher/update', [PublisherController::class, 'update'])->name('publishers.update');

    #Route authors
    Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
    Route::get('/authors/create', [AuthorController::class, 'create'])->name('authors.create');
    Route::post('/authors/posts', [AuthorController::class, 'posts'])->name('authors.posts');
    Route::get('/authors/{authorId}/delete-confirm', [AuthorController::class, 'confirmDelete'])->name('authors.del.confirm');
    Route::post('/authors/delete', [AuthorController::class, 'delete'])->name('authors.delete');
    Route::get('/authors/{authorId}/edit', [AuthorController::class, 'edit'])->name('authors.edit');
    Route::post('/authors/update', [AuthorController::class, 'update'])->name('authors.update');

    #Route print
    Route::get('/books/print', [BookController::class, 'print'])->name('books.print');
    Route::get('/books/export/excel', [BookController::class, 'excel'])->name('books.export.excel');
    Route::get('/books/print/{bookId}', [BookController::class, 'printDetail'])->name('books.print.detail');
    // Route::get('/books/export/excel', [BookController::class, 'excel'])->name('books.export.excel');

    Route::get('/authors/print', [AuthorController::class, 'print'])->name('authors.print');
    Route::get('/authors/export/excel', [AuthorController::class, 'excel'])->name('authors.export.excel');
    Route::get('/publishers/print', [PublisherController::class, 'print'])->name('publishers.print');
    Route::get('/publishers/export/excel', [PublisherController::class, 'excel'])->name('publishers.export.excel');

    Route::get('/mail/test', function () {
        Mail::to('xodabi7530@in2reach.com')->send(new TestMail());
    });
});



Route::get('/test', function () {
    echo "Hello World";
});
Route::get('/test/{nama}/{umur}', function ($nama, $umur) {
    echo "Hello World " . $nama . ' ' . $umur;
});

Route::get('/produk/baru', function () {
    echo "Ini adalah halaman Produk";
});

Route::get('/coba', [CobaController::class, 'index']);
Route::get('/coba/lagi', [CobaController::class, 'testing']);
Route::get('/coba/view', [CobaController::class, 'cobaView']);
Route::get('/coba/model', [CobaController::class, 'cobaModel']);
Route::get('/coba/mvc', [CobaController::class, 'cobaMVC']);



// Route::get('/coba-model', function () {
//     $books = Book::with('publisher')->get();
//     dd($books);
//     foreach ($books as $book) {
//         echo $book->code . ' - ' . $book->publisher->id . '<br/>';
//     }
//     dd();
// });

// Route::get('/coba-pub', function () {
//     $publishers = Publisher::with('books')->get();
//     foreach ($publishers as $p) {
//         echo $p->name . ' (';
//         foreach ($p->books as $b) {
//             echo $b->title . ', ';
//         }
//         echo ')<br>';
//     }
// });

// Route::get('/coba-model')