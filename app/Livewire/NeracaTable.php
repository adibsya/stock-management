<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Neraca;
use App\Models\PosMasterData;

class NeracaTable extends Component
{
    public $tanggal;
    public $data = [];

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        $this->loadData();
    }

    public function loadData()
    {
        $this->data = Neraca::with('pos')->where('tanggal', $this->tanggal)->get();
    }

    public function render()
    {
        return view('livewire.neraca-table');
    }
}
