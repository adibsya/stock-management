<?php

namespace App\Livewire;

use App\Models\Pelanggan;
use Livewire\Component;

class PelangganForm extends Component
{
    public ?Pelanggan $pelanggan = null;
    
    public string $kode_pelanggan = '';
    public string $nama_pelanggan = '';
    public string $no_hp = '';
    public string $email = '';
    public string $alamat = '';
    public string $jenis_pelanggan = 'eceran';

    public bool $isEdit = false;

    protected function rules(): array
    {
        return [
            'kode_pelanggan' => 'nullable|string|max:50',
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'jenis_pelanggan' => 'required|in:eceran,grosir',
        ];
    }

    public function mount(?Pelanggan $pelanggan = null): void
    {
        if ($pelanggan && $pelanggan->exists) {
            $this->isEdit = true;
            $this->pelanggan = $pelanggan;
            $this->kode_pelanggan = $pelanggan->kode_pelanggan ?? '';
            $this->nama_pelanggan = $pelanggan->nama_pelanggan;
            $this->no_hp = $pelanggan->no_hp ?? '';
            $this->email = $pelanggan->email ?? '';
            $this->alamat = $pelanggan->alamat ?? '';
            $this->jenis_pelanggan = $pelanggan->jenis_pelanggan ?? 'eceran';
        } else {
            $this->kode_pelanggan = 'PLG-' . str_pad(Pelanggan::count() + 1, 5, '0', STR_PAD_LEFT);
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
            'kode_pelanggan' => $this->kode_pelanggan ?: null,
            'nama_pelanggan' => $this->nama_pelanggan,
            'no_hp' => $this->no_hp ?: null,
            'email' => $this->email ?: null,
            'alamat' => $this->alamat ?: null,
            'jenis_pelanggan' => $this->jenis_pelanggan,
        ];

        if ($this->isEdit) {
            $this->pelanggan->update($data);
            session()->flash('success', 'Pelanggan berhasil diperbarui!');
        } else {
            Pelanggan::create($data);
            session()->flash('success', 'Pelanggan berhasil ditambahkan!');
        }

        $this->redirect(route('pelanggan.index'));
    }

    public function render()
    {
        return view('livewire.pelanggan-form');
    }
}
