<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
      @media screen, print {
          * {
              margin: 0;
              padding: 0;
  
          }
  
          body {
              margin: 2.6cm 1.9cm 1.4cm 2.7cm;
              font-family: 'Calibri', 'Helvetica', 'Arial', sans-serif;
              font-size: 12pt;
          }
  
  
          table.data {
              border: 1px solid;
              width: 100%;
              border-collapse: collapse;
          }
  
          table.data > tbody > tr > td {
              border: 1px solid;
              padding: 1px 2px;
          }
  
          table.data > thead > tr > th {
              border: 1px solid;
              background-color: #eaeaea;
              padding: 1px 2px;
          }
  
  
          .left {
              text-align: left;
          }
  
          .right {
              text-align: right;
          }
  
          .center {
              text-align: center;
          }
  
          .bg-gray {
              background-color: #eaeaea;
              padding: 10px !important;
              font-weight: bold;
          }
      }
  </style>
</head>

<body>
    <h1>Data Master Buku</h1>
    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Judul Buku</th>
                <th>Penerbit</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($books as $book)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $book->code }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->publisher->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
