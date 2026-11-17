@extends('layouts.master')

@section('title', 'Edit Animal')
@section('page-title', 'Edit Animal')
@section('breadcrumb', 'Animals / Edit')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit: {{ $animal->animal_code }}</h3>
    </div>
    <form action="{{ route('animals.update', $animal) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Farm <span class="text-danger">*</span></label>
                    <select name="farm_id" class="form-control" required>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}" {{ old('farm_id', $animal->farm_id) == $farm->id ? 'selected' : '' }}>
                                {{ $farm->farm_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Section <span class="text-danger">*</span></label>
                    <select name="section_id" class="form-control" required>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id', $animal->section_id) == $section->id ? 'selected' : '' }}>
                                {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 form-group">
                    <label>Animal Code <span class="text-danger">*</span></label>
                    <input type="text" name="animal_code" class="form-control" value="{{ old('animal_code', $animal->animal_code) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Animal Type <span class="text-danger">*</span></label>
                    <select name="animal_type" class="form-control" required>
                        @foreach(['cattle','goat','sheep','buffalo'] as $type)
                            <option value="{{ $type }}" {{ old('animal_type', $animal->animal_type) == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 form-group">
                    <label>Breed</label>
                    <input type="text" name="breed" class="form-control" value="{{ old('breed', $animal->breed) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Sex</label>
                    <select name="sex" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="female" {{ old('sex', $animal->sex) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="male"   {{ old('sex', $animal->sex) == 'male' ? 'selected' : '' }}>Male</option>
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $animal->date_of_birth) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Purchase Date</label>
                    <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $animal->purchase_date) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Purchase Value (BDT)</label>
                    <input type="number" step="0.01" name="purchase_value" class="form-control" value="{{ old('purchase_value', $animal->purchase_value) }}">
                </div>

                <div class="col-md-6 form-group">
                    <label>Source Type</label>
                    <select name="source_type" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="purchased"   {{ old('source_type', $animal->source_type) == 'purchased' ? 'selected' : '' }}>Purchased</option>
                        <option value="born"        {{ old('source_type', $animal->source_type) == 'born' ? 'selected' : '' }}>Born on Farm</option>
                        <option value="transferred" {{ old('source_type', $animal->source_type) == 'transferred' ? 'selected' : '' }}>Transferred</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        @foreach(['active','sold','deceased'] as $st)
                            <option value="{{ $st }}" {{ old('status', $animal->status) === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $animal->notes) }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Animal</button>
            <a href="{{ route('animals.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection