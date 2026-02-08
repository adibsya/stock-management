<?php

namespace App\Exports;

use App\Models\Neraca;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class NeracaExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    public function __construct(
        public string $tanggal
    ) {}

    public function collection()
    {
        $data = Neraca::with('pos')
            ->where('tanggal', $this->tanggal)
            ->orderBy('pos_id')
            ->get();

        return $data->map(function ($item) {
            $jenis = $item->pos->normal_saldo ?? ($item->pos->jenis ?? '-');
            return [
                $item->pos->kode ?? '-',
                $item->pos->nama ?? '-',
                ucfirst(strtolower($jenis)),
                $item->jumlah,
            ];
        });
    }

    public function headings(): array
    {
        return ['Kode Pos', 'Nama Pos', 'Jenis', 'Jumlah (Rp)'];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
