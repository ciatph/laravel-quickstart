<!-- resources/views/tasks.blade.php -->

@extends('layouts.app')

@section('content')
    <!-- Bootstrap Boilerplate -->

    <div class="panel-body">

        <!-- Validation errors -->
        @include('common.errors')

        <!-- New Task for, -->
        <form action="{{ url('task') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <!-- Task Name -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Task</label>
            </div>

            <div class="col-sm-6">
                <input type="text" name="name" id="task-name" class="form-control">
            </div>

            <!-- Add Task button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-default">
                        <i class="fa"></i> Add Task
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TODO: Current Tasks -->
    @if (count($tasks) > 0) 
        <div class="panel panel-default">
            <div class="panel-heading">
                Current Tasks
            </div>

            <div class="panel-body">
                <table class="table table-striped task-table">
                    <!-- Table Headings -->
                    <thead>
                        <th>Task</th>
                        <th>&nbsp;</th>
                    </thead>

                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div>{{ $task->name }}</div>
                                </td>

                                <td>
                                    <!-- TODO: Delete button -->
                                    <form action="{{ url('task/' . $task->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-fresh"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    
@endsection