<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BukuBesar extends Component
{
    public $coaId = '';
    public $tanggalAwal;
    public $tanggalAkhir;
    public $filterDebug;

    public function mount()
    {
        $this->tanggalAwal  = now()->startOfMonth()->format('Y-m-d');
        $this->tanggalAkhir = now()->format('Y-m-d');
    }

    public function getCoasProperty()
    {
        return DB::table('pos_master_data')
            ->where('level', '>=', 2)
            ->orderBy('kode')
            ->get();
    }

    public function getCoaProperty()
    {
        if (!$this->coaId) return null;
        return DB::table('pos_master_data')->where('id', $this->coaId)->first();
    }

    // MENGHITUNG SALDO SEBELUM TANGGAL AWAL
    public function getSaldoAwalProperty()
    {
        if (!$this->coaId || !$this->coa) return 0;

        $mutasi = DB::table('jurnal_detail as jd')
            ->join('jurnal as j', 'j.id', '=', 'jd.jurnal_id')
            ->where('jd.coa_id', $this->coaId)
            ->where('j.tanggal', '<', $this->tanggalAwal)
            ->selectRaw('SUM(jd.debit) as total_debit, SUM(jd.kredit) as total_kredit')
            ->first();

        $normal = $this->coa->normal_saldo;
        
        if (strtolower($normal) === 'debit') {
            return ($mutasi->total_debit ?? 0) - ($mutasi->total_kredit ?? 0);
        } else {
            return ($mutasi->total_kredit ?? 0) - ($mutasi->total_debit ?? 0);
        }
    }

    public function getRowsProperty()
    {
        if (!$this->coaId) return collect();

        return DB::table('jurnal_detail as jd')
            ->join('jurnal as j', 'j.id', '=', 'jd.jurnal_id')
            ->where('jd.coa_id', $this->coaId)
            ->whereBetween('j.tanggal', [$this->tanggalAwal, $this->tanggalAkhir])
            ->orderBy('j.tanggal')
            ->orderBy('j.id')
            ->select('j.tanggal', 'j.id', 'j.kode', 'j.keterangan', 'jd.debit', 'jd.kredit')
            ->get();
    }

    public function getBukuBesarProperty()
    {
        if (!$this->coaId) return collect();

        $saldo = $this->saldoAwal; // Mulai dari saldo awal bulan/periode sebelumnya
        $normal = strtolower($this->coa->normal_saldo);

        return $this->rows->map(function ($row) use (&$saldo, $normal) {
            if ($normal === 'debit') {
                $saldo += ($row->debit - $row->kredit);
            } else {
                $saldo += ($row->kredit - $row->debit);
            }
            $row->saldo = $saldo;
            return $row;
        });
    }

    public function render()
    {
        return view('livewire.buku-besar', [
            'bukuBesar' => $this->bukuBesar,
            'saldoAwal' => $this->saldoAwal,
        ]);
    }
}