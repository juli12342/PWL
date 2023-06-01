<?php 
namespace App\Export;
use App\Models\Publisher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPublishers implements FromCollection, WithHeadings
{
  public function collection()
  {
    return Publisher::all()->map(function ($item) {
      return [
          'ID' => $item->id,
          'Nama Penerbit' => $item->name
      ];
    });
  }
  public function headings(): array
  {
    return [
      'ID',
      'Nama Penerbit'
    ];
  }
}
