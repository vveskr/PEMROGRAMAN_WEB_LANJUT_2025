<?php

namespace App\DataTables;

use App\Models\KategoriModel;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KategoriDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                $btn  = '<a href="' . url('/kategori/' . $kategori->kategori_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">' . csrf_field() . method_field('DELETE') .
                        '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus?\')">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']);
    }

    public function query(KategoriModel $model)
    {
        return $model->newQuery()->select('kategori_id', 'kategori_kode', 'kategori_nama', 'kategori_slug');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('kategori-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->responsive(true)
            ->autoWidth(false);

    }

    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex')->title('No')->searchable(false)->orderable(false)->width(30),
            Column::make('kategori_kode')->title('Kode Kategori'),
            Column::make('kategori_nama')->title('Nama Kategori'),
            Column::make('kategori_slug')->title('Slug Kategori'),
            Column::computed('aksi')->title('Aksi')->exportable(false)->printable(false)->searchable(false)->orderable(false)
        ];
    }

    protected function filename(): string
    {
        return 'Kategori_' . date('YmdHis');
    }
}
