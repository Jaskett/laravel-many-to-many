@extends('layouts.admin')

@section('page_name') | Projects  @endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="mb-3">
                <a href="{{ route('admin.projects.create') }}" class="btn btn-primary text-light">
                    New project
                </a>
            </div>
            
            <div class="card">
                <div class="card-header">{{ __('Project list') }}</div>

                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($projects as $project)
                            <li class="list-group-item p-4">
                                <strong>Title:</strong> {{ $project->title }}
                                <br>
                                <strong>Link:</strong> {{ $project->link }}
                                <br>
                                <strong>Type:</strong>
                                @if ($project->type)
                                    <a href="{{ route('admin.types.show', $project->type->id) }}">{{ $project->type->name }}</a>
                                @else
                                    None
                                @endif
                                
                                <div class="mt-3">
                                    <a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-info text-light">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </a>
                                    <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-warning text-light">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>

                                    <form
                                        action="{{ route('admin.projects.destroy', $project->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Do you really want to delete this project? You won\'t be able to recover it later')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger text-light">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection