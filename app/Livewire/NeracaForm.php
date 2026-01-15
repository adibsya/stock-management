<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Neraca;
use App\Models\PosMasterData;

class NeracaForm extends Component
{
    public $tanggal;
    public $pos_id;
    public $jumlah;
    public $posList = [];

    protected $rules = [
        'tanggal' => 'required|date',
        'pos_id' => 'required|exists:pos_master_data,id',
        'jumlah' => 'required|numeric',
    ];

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        $this->posList = PosMasterData::all();
    }

    public function save()
    {
        $this->validate();
        Neraca::create([
            'tanggal' => $this->tanggal,
            'pos_id' => $this->pos_id,
            'jumlah' => $this->jumlah,
        ]);
        session()->flash('success', 'Data neraca berhasil disimpan');
        $this->reset(['pos_id', 'jumlah']);
        $this->dispatch('neraca-updated');
    }

    public function render()
    {
        return view('livewire.neraca-form');
    }
}
