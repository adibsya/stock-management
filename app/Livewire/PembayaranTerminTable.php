<?php

namespace App\Livewire;

use App\Models\PembayaranPembelian;
use Livewire\Component;
use Livewire\WithPagination;


class PembayaranTerminTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showModal = false;
    public $modalTerminId = null;
    protected $listeners = ['closeModalBayar' => 'closeModalBayar'];

    public function openModalBayar($id)
    {
        $this->modalTerminId = $id;
        $this->showModal = true;
    }

    public function closeModalBayar()
    {
        $this->showModal = false;
        $this->modalTerminId = null;
    }
    public function render()
    {
        $termins = PembayaranPembelian::with(['pembelian.pemasok'])
            ->where('status', '!=', 'lunas')
            ->whereHas('pembelian', function($q) {
                $q->where('status_bayar', 'belum_lunas')
                  ->whereNotNull('jatuh_tempo');
            })
            ->when($this->search, function($q) {
                $q->whereHas('pembelian', function($q2) {
                    $q2->where('no_faktur_supplier', 'like', '%'.$this->search.'%')
                        ->orWhereHas('pemasok', function($q3) {
                            $q3->where('nama_supplier', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate($this->perPage);

        return view('livewire.pembayaran-termin-table', [
            'termins' => $termins,
            'showModal' => $this->showModal,
            'modalTerminId' => $this->modalTerminId,
        ]);
    }
}
