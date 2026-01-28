<?php

namespace App\Livewire;

use App\Models\Pengeluaran;
use App\Models\Gudang;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\TransaksiService;


class PengeluaranForm extends Component
{
    public string $tanggal = '';
    public string $jenis_pengeluaran = '';
    public float|int $jumlah_biaya = 0;
    public ?string $keterangan = null;
    public int $gudang_id;

    public bool $isSuperadmin = false;
    public $gudangs;

    public function mount(): void
    {
        $user = Auth::user();

        $this->tanggal = now()->format('Y-m-d');
        $this->isSuperadmin = $user->isSuperAdmin();

        $this->gudangs = Gudang::all();

        // 🔒 Paksa gudang untuk non-superadmin
        if (!$this->isSuperadmin) {
            $this->gudang_id = $user->gudang_id;
        }
    }

    protected function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'jenis_pengeluaran' => ['required', 'string', 'max:100'],
            'jumlah_biaya' => ['required', 'numeric', 'min:1'],
            'gudang_id' => ['required', 'exists:gudang,id'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        app(TransaksiService::class)
        ->pengeluaranOperasional([
            'tanggal' => $this->tanggal,
            'jenis_pengeluaran' => $this->jenis_pengeluaran,
            'jumlah_biaya' => $this->jumlah_biaya,
            'keterangan' => $this->keterangan,
            'gudang_id' => $this->gudang_id,
            'user_id' => Auth::id(),
        ]);


        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Pengeluaran berhasil ditambahkan',
        ]);

        $this->reset(['jenis_pengeluaran', 'jumlah_biaya', 'keterangan']);
        $this->tanggal = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.pengeluaran-form');
    }
}
