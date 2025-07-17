@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create New Outlook Folder</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('outlook.folder.store') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="folder_name">Folder Name</label>
                            <input type="text" class="form-control @error('folder_name') is-invalid @enderror" 
                                   id="folder_name" name="folder_name" value="{{ old('folder_name') }}" required>
                            @error('folder_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="deskripsi">Description</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                Create Folder
                            </button>
                            <a href="{{ route('outlook.folder.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
