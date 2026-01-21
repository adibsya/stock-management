<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TrialBalance extends Component
{
    public $tanggalAwal;
    public $tanggalAkhir;

    public function mount()
    {
        $this->tanggalAwal  = now()->startOfMonth()->format('Y-m-d');
        $this->tanggalAkhir = now()->format('Y-m-d');
    }

    public function getTrialBalanceProperty()
    {
        $rows = DB::table('pos_master_data as coa')
            ->leftJoin('jurnal_detail as jd', 'jd.coa_id', '=', 'coa.id')
            ->leftJoin('jurnal as j', 'j.id', '=', 'jd.jurnal_id')
            ->where(function ($q) {
                $q->whereBetween('j.tanggal', [
                    $this->tanggalAwal,
                    $this->tanggalAkhir
                ])
                ->orWhereNull('j.tanggal');
            })

            ->where('coa.level', '>=', 2) // hanya akun posting
            ->select(
                'coa.id',
                'coa.kode',
                'coa.nama',
                'coa.normal_saldo',
                DB::raw('COALESCE(SUM(jd.debit),0) as total_debit'),
                DB::raw('COALESCE(SUM(jd.kredit),0) as total_kredit')
            )
            ->groupBy(
                'coa.id',
                'coa.kode',
                'coa.nama',
                'coa.normal_saldo'
            )
            ->orderBy('coa.kode')
            ->get();


        return $rows->map(function ($row) {

            if ($row->normal_saldo === 'debit') {
                // ASET & BEBAN
                $saldo = $row->total_debit - $row->total_kredit;

                $row->debit  = max($saldo, 0);
                $row->kredit = max(-$saldo, 0);

            } else {
                // LIABILITAS, EKUITAS, PENDAPATAN
                $saldo = $row->total_kredit - $row->total_debit;

                $row->kredit = max($saldo, 0);
                $row->debit  = max(-$saldo, 0);
            }

            return $row;
        });

    }

    public function getTotalDebitProperty()
    {
        return $this->trialBalance->sum('debit');
    }

    public function getTotalKreditProperty()
    {
        return $this->trialBalance->sum('kredit');
    }

    public function render()
    {
        return view('livewire.trial-balance');
    }
}
