<?php 
namespace App\Export;
use App\Models\Author;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportAuthors implements FromCollection, WithHeadings
{
  public function collection()
  {
    return Author::all()->map(function ($item) {
      return [
          'ID' => $item->id,
          'Nama Penulis' => $item->name
      ];
    });
  }
  public function headings(): array
  {
    return [
      'ID',
      'Nama Penulis'
    ];
  }
}
