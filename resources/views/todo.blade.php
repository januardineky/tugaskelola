@extends('template.navbar')

@section('content')

    <!-- Flash Messages with SweetAlert -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Button to Open Add Todo Modal -->
    <div class="mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTodoModal">Add New List</button>
    </div>

    <!-- Table for Todos -->
    <table id="todoTable" class="display">
        <thead>
            <tr>
                <th>Todo</th>
                <th>Status</th>
                <th>User ID</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['data'] as $todo)
                <tr>
                    <td>{{ $todo['todo'] }}</td>
                    <td>{{ $todo['completed'] ? 'Finished' : 'Not Finished' }}</td>
                    <td>{{ $todo['userId'] }}</td>
                    <td>{{ $todo['created_at'] }}</td>
                    <td>{{ $todo['updated_at'] }}</td>
                    <td>
                        <!-- Edit Link -->
                        <a href="/edit/{{ $todo['_id'] }}" class="btn btn-primary text-white p-2" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <!-- Delete Link -->
                        <a href="/delete/{{ $todo['_id'] }}" class="btn btn-danger text-white" title="Delete" onclick="return confirmDelete(event, '{{ $todo['_id'] }}')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add Todo Modal -->
    <div class="modal fade" id="addTodoModal" tabindex="-1" aria-labelledby="addTodoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTodoModalLabel">Add New Todo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTodoForm" action="/add" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="todoName" class="form-label">Todo Name</label>
                            <input type="text" class="form-control" id="todoName" name="todo" required>
                        </div>
                        <div class="mb-3">
                            <label for="completed" class="form-label">Completed</label>
                            <select class="form-select" id="completed" name="completed" required>
                                <option value="false">Not Finished</option>
                                <option value="true">Finished</option>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Todo Modal -->
    <div class="modal fade" id="editTodoModal" tabindex="-1" aria-labelledby="editTodoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTodoModalLabel">Edit Todo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTodoForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editTodoName" class="form-label">Todo Name</label>
                            <input type="text" class="form-control" id="editTodoName" name="todo" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCompleted" class="form-label">Completed</label>
                            <select class="form-select" id="editCompleted" name="completed" required>
                                <option value="false">Not Finished</option>
                                <option value="true">Finished</option>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Update">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Open the modal for editing a todo item
        function openEditModal(todoId) {
            $.get(`/edit/${todoId}`, function(todo) {
                if (todo.error) {
                    alert(todo.error);
                    return;
                }

                // Pre-fill the modal form with the todo data
                $('#editTodoName').val(todo.todo);
                $('#editCompleted').val(todo.completed);
                $('#editTodoForm').attr('action', `/update/${todo._id}`);  // Ensure we use '_id' here
                $('#editTodoModal').modal('show');
            });
        }


        function confirmDelete(event, todoId) {
    event.preventDefault(); // Prevent the default link action (navigate to href)
    if (confirm('Are you sure you want to delete this item?')) {
        $.ajax({
            url: '/delete/' + todoId,  // Your route to handle deletion
            type: 'GET',  // You can use 'POST' if required on the server side
            success: function(response) {
                if (response.success) {
                    alert('Todo deleted successfully');
                    // Optionally, remove the row from the table after deletion
                    $('#todoRow_' + todoId).remove();
                } else {
                    alert('Failed to delete the Todo');
                }
            },
            error: function() {
                alert('Error occurred while deleting the Todo');
            }
        });
    }
}


    </script>

@endsection
