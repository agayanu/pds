<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;

class ViolationExport implements FromCollection, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $dateFirst;
    protected $dateLast;

    public function __construct($data,$dateFirst,$dateLast)
    {
        $this->data = $data;
        $this->dateFirst = $dateFirst;
        $this->dateLast = $dateLast;
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($data): array
    {
        return [
            $data->name,
            $data->class,
            $data->article,
            $data->remarks,
            $data->username,
            $data->createdAt,
        ];
    }

    public function headings(): array
    {
        if($this->dateFirst === $this->dateLast) {
            $dateShow = $this->dateFirst;
        } else {
            $dateShow = $this->dateFirst.' s.d. '.$this->dateLast;
        }
        return [
            [
                'DATA PELANGGARAN',
            ],
            [' '],
            [$dateShow],
            [' '],
            [
                'Nama Siswa',
                'Kelas',
                'Pasal Pelanggaran',
                'Keterangan',
                'Diinput Oleh',
                'Tanggal Input',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A5:F5')->getFont()->setBold(true);
    }
}
