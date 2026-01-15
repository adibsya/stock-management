<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PosMasterData;
use Illuminate\Validation\Rule;


class PosMasterIndex extends Component
{
    public $pos;

    // FORM STATE
    public $showForm = false;
    public $editId = null;

    public $parent_id;
    public $nama;
    public $previewKode;

    protected function rules()
    {
        return [
            'parent_id' => [
                'required',
                Rule::exists('pos_master_data', 'id')->where('level', 1),
            ],
            'nama' => 'required|string|max:100',
        ];
    }

    public function mount()
    {
        $this->loadPos();
    }

    public function loadPos()
    {
        $this->pos = PosMasterData::with('children')
            ->orderBy('kode')
            ->get();
    }

    // =========================
    // FORM
    // =========================

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $pos = PosMasterData::findOrFail($id);

        // 🔒 HANYA AKUN DETAIL BOLEH DIEDIT
        if ($pos->level < 2) {
            return;
        }

        $this->editId     = $pos->id;
        $this->parent_id  = $pos->parent_id;
        $this->nama       = $pos->nama;
        $this->previewKode = $pos->kode;

        $this->showForm = true;
    }

    // =========================
    // AUTO PREVIEW KODE
    // =========================
    public function updatedParentId()
    {
        $parent = PosMasterData::where('id', $this->parent_id)
            ->where('level', 1)
            ->first();

        if (! $parent) {
            $this->previewKode = null;
            return;
        }

        $lastUrutan = PosMasterData::where('parent_id', $parent->id)
            ->max('urutan') ?? 0;

        $this->previewKode =
            $parent->kode . '-' . str_pad($lastUrutan + 1, 2, '0', STR_PAD_LEFT);
    }

    // =========================
    // SAVE
    // =========================
    public function save()
    {
        $this->validate();

        // ========= UPDATE =========
        if ($this->editId) {
            $pos = PosMasterData::findOrFail($this->editId);

            if ($pos->level < 2) {
                return;
            }

            $pos->nama = $this->nama;
            $pos->save();

        } 
        // ========= CREATE =========
        else {
            $parent = PosMasterData::where('id', $this->parent_id)
                ->where('level', 1)
                ->firstOrFail();

            $lastUrutan = PosMasterData::where('parent_id', $parent->id)
                ->max('urutan') ?? 0;

            PosMasterData::create([
                'parent_id'     => $parent->id,     // 🔒 PASTI ADA
                'kode'          => $parent->kode . '-' . str_pad($lastUrutan + 1, 2, '0', STR_PAD_LEFT),
                'nama'          => $this->nama,
                'kategori'      => $parent->kategori,
                'sub_kategori'  => $parent->sub_kategori,
                'normal_saldo'  => $parent->normal_saldo,
                'level'         => 2,                // 🔒 PAKSA DETAIL
                'urutan'        => $lastUrutan + 1,
                'is_active'     => true,
            ]);
        }

        session()->flash('success', 'Akun berhasil disimpan');

        $this->resetForm();
        $this->loadPos();
    }

    public function resetForm()
    {
        $this->reset([
            'showForm',
            'editId',
            'parent_id',
            'nama',
            'previewKode',
        ]);
    }

    public function render()
    {
        return view('livewire.pos-master-index', [
            // 🔒 HANYA KELOMPOK (LEVEL 1)
            'parents' => PosMasterData::where('level', 1)
                ->orderBy('kode')
                ->get(),
        ]);
    }
}
