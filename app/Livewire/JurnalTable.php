<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use App\Models\PosMasterData;

class JurnalTable extends Component
{
    use WithPagination;
    public $search = '';
    public $sumber = '';
    public $tanggal = '';

    public function render()
    {
        $query = Jurnal::query();
        if ($this->search) {
            $query->where('keterangan', 'like', "%{$this->search}%")
                  ->orWhere('kode', 'like', "%{$this->search}%");
        }
        if ($this->sumber) {
            $query->where('sumber', $this->sumber);
        }
        if ($this->tanggal) {
            $query->whereDate('tanggal', $this->tanggal);
        }
        $jurnals = $query->orderByDesc('tanggal')->paginate(15);
        return view('livewire.jurnal-table', compact('jurnals'));
    }
}
