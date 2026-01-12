<?php
namespace App\Livewire;

use App\Models\PembayaranPenjualan;
use Livewire\Component;
use Livewire\WithPagination;

class PembayaranPenjualanTerminTable extends Component
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
        $termins = PembayaranPenjualan::with(['penjualan.pelanggan'])
            ->where('status', '!=', 'lunas')
            ->whereHas('penjualan', function($q) {
                $q->where('mode_termin', 'termin');
            })
            ->when($this->search, function($q) {
                $q->whereHas('penjualan', function($q2) {
                    $q2->where('no_faktur', 'like', '%'.$this->search.'%')
                        ->orWhereHas('pelanggan', function($q3) {
                            $q3->where('nama', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate($this->perPage);

        return view('livewire.pembayaran-penjualan-termin-table', [
            'termins' => $termins,
            'showModal' => $this->showModal,
            'modalTerminId' => $this->modalTerminId,
        ]);
    }
}
