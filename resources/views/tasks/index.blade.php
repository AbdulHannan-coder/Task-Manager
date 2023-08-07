@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>Task Manager</h1>
                <hr>
                <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Create Task</a>
                <table id="task-table" class="table">
                    <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Priority</th>
                        <th>Time Stamp</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tasks as $task)
                        <tr data-task-id="{{ $task->id }}">
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->priority }}</td>
                            <td>{{ $task->created_at }}</td>
                            <td class="d-flex align-items-center">
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-info">Edit</a>
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="mx-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sortablejs@1.13.0/Sortable.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script>
        var taskTable = document.getElementById('task-table');
        Sortable.create(taskTable.tBodies[0], {
            animation: 150,
            onUpdate: function(event) {
                var rows = Array.from(event.from.children);
                rows.forEach(function(row, index) {
                    var taskId = row.dataset.taskId;
                    var newPriority = index + 1;
                    // Update the priority in the UI
                    row.cells[1].textContent = newPriority;
                    // Send an AJAX request to update the priority in the database
                    updateTaskPriority(taskId, newPriority);
                });
            }
        });

        function updateTaskPriority(taskId, priority) {
            var formData = new FormData();
            formData.append('_method', 'PATCH');
            formData.append('priority', priority);

            fetch('/tasks/' + taskId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(function(response) {
                    if (response.ok) {
                        console.log('Task priority updated successfully.');
                    } else {
                        throw new Error('Error updating task priority.');
                    }
                })
                .catch(function(error) {
                    console.error(error);
                });
        }
    </script>

@endsection
