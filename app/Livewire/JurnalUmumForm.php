<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PosMasterData;
use App\Livewire\BukuBesar;
use App\Services\JurnalService;
use Illuminate\Support\Facades\DB;

class JurnalUmumForm extends Component
{
    public $tanggal;
    public $keterangan;
    public $tipe = 'penyesuaian'; // Default ke penyesuaian
    public $items = []; // Baris jurnal

    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d');
        // Tambahkan 2 baris kosong di awal
        $this->addRow();
        $this->addRow();
    }

    public function addRow()
    {
        $this->items[] = ['coa_id' => '', 'debit' => 0, 'kredit' => 0];
    }

    public function removeRow($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $totalDebit = collect($this->items)->sum('debit');
        $totalKredit = collect($this->items)->sum('kredit');

        if ($totalDebit != $totalKredit) {
            session()->flash('error', 'Total Debit dan Kredit harus seimbang!');
            return;
        }

        DB::transaction(function () {
            // Menggunakan JurnalService yang sudah Anda buat
            app(JurnalService::class)->create(
                $this->tanggal,
                $this->keterangan,
                $this->tipe,
                null, // ref_id null karena manual
                $this->items
            );
        });

        return redirect()->route('buku-besar.index');
    }

    public function render()
    {
        return view('livewire.jurnal-umum-form', [
            'coas' => PosMasterData::orderBy('kode')->get()
        ]);
    }
}