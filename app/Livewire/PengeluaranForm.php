<?php

namespace App\Livewire;

use App\Models\Pengeluaran;
use Livewire\Component;

class PengeluaranForm extends Component
{
    public ?Pengeluaran $pengeluaran = null;
    
    public string $tanggal = '';
    public string $jenis_pengeluaran = '';
    public string $keterangan = '';
    public string $jumlah_biaya = '';

    public bool $isEdit = false;

    protected function rules(): array
    {
        return [
            'tanggal' => 'required|date',
            'jenis_pengeluaran' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
            'jumlah_biaya' => 'required|numeric|min:0',
        ];
    }

    public function mount(?Pengeluaran $pengeluaran = null): void
    {
        if ($pengeluaran && $pengeluaran->exists) {
            $this->isEdit = true;
            $this->pengeluaran = $pengeluaran;
            $this->tanggal = $pengeluaran->tanggal->format('Y-m-d');
            $this->jenis_pengeluaran = $pengeluaran->jenis_pengeluaran;
            $this->keterangan = $pengeluaran->keterangan ?? '';
            $this->jumlah_biaya = $pengeluaran->jumlah_biaya;
        } else {
            $this->tanggal = now()->format('Y-m-d');
        }
    }

    public function save(): void
    {
        if (!auth()->user()->canModify()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menyimpan data!');
            return;
        }

        $validated = $this->validate();

        $data = [
            'tanggal' => $this->tanggal,
            'jenis_pengeluaran' => $this->jenis_pengeluaran,
            'keterangan' => $this->keterangan ?: null,
            'jumlah_biaya' => $this->jumlah_biaya,
        ];

        if ($this->isEdit) {
            $this->pengeluaran->update($data);
            session()->flash('success', 'Pengeluaran berhasil diperbarui!');
        } else {
            Pengeluaran::create($data);
            session()->flash('success', 'Pengeluaran berhasil ditambahkan!');
        }

        $this->redirect(route('pengeluaran.index'));
    }

    public function render()
    {
        $kategoris = ['Operasional', 'Gaji', 'Listrik', 'Air', 'Internet', 'Sewa', 'Transportasi', 'Lainnya'];
        
        return view('livewire.pengeluaran-form', [
            'kategoris' => $kategoris,
        ]);
    }
}
