
@extends('components.layouts.app')

@section('header', 'Edit Barang (Identitas)')

@section('content')
<div>
	@livewire('barang-master-form', ['barangMaster' => $barangMaster])
</div>
@endsection
