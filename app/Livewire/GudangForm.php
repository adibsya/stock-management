<?php

namespace App\Livewire;

use App\Models\Gudang;
use Livewire\Component;

class GudangForm extends Component
{
    public ?Gudang $gudang = null;
    
    public string $nama_gudang = '';
    public string $lokasi = '';

    public bool $isEdit = false;

    protected function rules(): array
    {
        return [
            'nama_gudang' => 'required|string|max:255',
            'lokasi' => 'nullable|string',
        ];
    }

    public function mount(?Gudang $gudang = null): void
    {
        if ($gudang && $gudang->exists) {
            $this->isEdit = true;
            $this->gudang = $gudang;
            $this->nama_gudang = $gudang->nama_gudang;
            $this->lokasi = $gudang->lokasi ?? '';
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
            'nama_gudang' => $this->nama_gudang,
            'lokasi' => $this->lokasi ?: null,
        ];

        if ($this->isEdit) {
            $this->gudang->update($data);
            session()->flash('success', 'Gudang berhasil diperbarui!');
        } else {
            Gudang::create($data);
            session()->flash('success', 'Gudang berhasil ditambahkan!');
        }

        $this->redirect(route('gudang.index'));
    }

    public function render()
    {
        return view('livewire.gudang-form');
    }
}
