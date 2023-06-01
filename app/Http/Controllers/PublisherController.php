<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Export\ExportPublishers;
use Maatwebsite\Excel\Facades\Excel;

class PublisherController extends Controller
{
  /**
   * Fungsi untuk menampilkan semua data books
   */
  public function index()
  {
    $publishers = Publisher::query()
      ->when(request('search'), function ($query) {
        $searchTerm = '%' . request('search') . '%';
        $query->where('name', 'like', $searchTerm);
      })->paginate(5);
    return view('publisher/index', [
      'publishers' => $publishers
    ]);
  }

  public function print()
    {
        $publishers = Publisher::all();
        $filename = "publishers_" . date('Y-m-d-H-i-s') . ".pdf";
        $pdf = Pdf::loadView('publisher/print', ['publishers' => $publishers]);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream($filename);
    }

    public function excel()
    {
        return Excel::download(new ExportPublishers, 'publishers.xlsx');
    }

  public function create()
  {
    return view('publisher/form');
  }

  public function posts(Request $request)
  {
    $validate = $request->validate([
      'name' => 'required | max:255'
    ]);
    $name = $request->name;
    Publisher::create([
      'name' => $name,
    ]);
    return redirect(route('publisher.index'))->with('success', 'Publisher berhasil ditambah ');
  }

  public function confirmDelete($publisherId)
  {
    $publisher = Publisher::FindOrFail($publisherId);
    return view('publisher/delete-confirm', [
      'publisher' => $publisher
    ]);
  }

  public function delete(Request $request)
  {
    $publisherId = $request->id;
    $publisher = Publisher::FindOrFail($publisherId);
    $publisher->delete();
    return redirect(route('publisher.index'))->with('success', 'Publisher Berhasil Dihapus');
  }

  public function edit($publisherId)
  {
    $publisher = Publisher::FindOrFail($publisherId);
    return view('publisher/form-update', [
      'publisher' => $publisher
    ]);
  }

  public function update(Request $request)
  {
    $validate = $request->validate([
      'name' => 'required | max:255',
    ]);

    $publisherId = $request->id;
    $publisher = Publisher::FindOrFail($publisherId);
    $publisher->update([
      'name' => $request->name,
    ]);
    return redirect(route('publisher.index'))->with('success', 'Publishser Berhasil Diupdate');
  }
}
