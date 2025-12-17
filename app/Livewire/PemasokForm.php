<?php

namespace App\Livewire;

use App\Models\Pemasok;
use Livewire\Component;

class PemasokForm extends Component
{
    public ?Pemasok $pemasok = null;
    
    public string $nama_supplier = '';
    public string $kontak = '';
    public string $alamat = '';
    public string $catatan_termin_pembayaran = '';

    public bool $isEdit = false;

    protected function rules(): array
    {
        return [
            'nama_supplier' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'catatan_termin_pembayaran' => 'nullable|string',
        ];
    }

    public function mount(?Pemasok $pemasok = null): void
    {
        if ($pemasok && $pemasok->exists) {
            $this->isEdit = true;
            $this->pemasok = $pemasok;
            $this->nama_supplier = $pemasok->nama_supplier;
            $this->kontak = $pemasok->kontak ?? '';
            $this->alamat = $pemasok->alamat ?? '';
            $this->catatan_termin_pembayaran = $pemasok->catatan_termin_pembayaran ?? '';
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
            'nama_supplier' => $this->nama_supplier,
            'kontak' => $this->kontak ?: null,
            'alamat' => $this->alamat ?: null,
            'catatan_termin_pembayaran' => $this->catatan_termin_pembayaran ?: null,
        ];

        if ($this->isEdit) {
            $this->pemasok->update($data);
            session()->flash('success', 'Pemasok berhasil diperbarui!');
        } else {
            Pemasok::create($data);
            session()->flash('success', 'Pemasok berhasil ditambahkan!');
        }

        $this->redirect(route('pemasok.index'));
    }

    public function render()
    {
        return view('livewire.pemasok-form');
    }
}
