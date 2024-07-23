@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Courses</div>

                <div class="card-body">
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Add Courses</a>

                    <ul class="list-group">
                        @foreach($tasks as $task)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $task->title }}</strong>
                                    <p>{{ $task->description }}</p>
                                    <span class="badge badge-{{ $task->is_done ? 'success' : 'secondary' }}">
                                        {{ $task->is_done ? 'Done' : 'Not Done' }}
                                    </span>
                                </div>
                                <div>
                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                    @if(!$task->is_done)
                                        <form action="{{ route('tasks.markAsDone', $task) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-success">Mark as Done</button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
