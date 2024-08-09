<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <h1 class="mb-4">Task Manager</h1>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Add a New Task</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tasks.store') }}" method="POST">
                            @csrf
                            <div class="form-group mt-2">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control mt-1" placeholder="Title" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control mt-1" placeholder="Description" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Add Task</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Tasks</h2>
                    </div>
                    <div class="card-body" id="task-list" style="max-height: 500px; overflow-y: auto;">
                        <ul class="list-group mb-5" id="task-items">
                            @foreach ($tasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6>{{ $task->getTitle() }}</h6>
                                        <p>{{ $task->getDescription() }}</p>
                                    </div>
                                    <div>
                                        <form action="{{ route('tasks.updateStatus', $task->getId()) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="button" class="btn badge
                                                {{ $task->getStatus() === \App\Domain\Task\Entities\Task::STATUS_TODO ? 'bg-danger' : '' }}
                                                {{ $task->getStatus() === \App\Domain\Task\Entities\Task::STATUS_IN_PROGRESS ? 'bg-warning' : '' }}
                                                {{ $task->getStatus() === \App\Domain\Task\Entities\Task::STATUS_COMPLETED ? 'bg-success' : '' }}"
                                                onclick="updateTaskStatus(this, '{{ $task->getStatus() === \App\Domain\Task\Entities\Task::STATUS_TODO ? \App\Domain\Task\Entities\Task::STATUS_IN_PROGRESS : ($task->getStatus() === \App\Domain\Task\Entities\Task::STATUS_IN_PROGRESS ? \App\Domain\Task\Entities\Task::STATUS_COMPLETED : \App\Domain\Task\Entities\Task::STATUS_TODO) }}')">
                                                {{ $task->getStatus() }}
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateTaskStatus(button, status) {
            const form = button.closest('form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status';
            input.value = status;
            form.appendChild(input);
            form.submit();
        }

        let page = 1;
        const taskList = document.getElementById('task-list');
        const taskItems = document.getElementById('task-items');

        taskList.addEventListener('scroll', function() {
            if (taskList.scrollTop + taskList.clientHeight >= taskList.scrollHeight) {
                page++;
                loadMoreTasks(page);
            }
        });

        function loadMoreTasks(page) {
            $.ajax({
                url: '{{ route("tasks.index") }}?page=' + page,
                type: 'get',
                success: function(response) {
                    response.tasks.forEach(task => {
                        const taskItem = document.createElement('li');
                        taskItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                        taskItem.innerHTML = `
                            <div>
                                <h6>${task.title}</h6>
                                <p>${task.description}</p>
                            </div>
                            <div>
                                <form action="{{ route('tasks.updateStatus', 'TASK_ID') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="button" class="btn badge
                                        ${task.status === 'TODO' ? 'bg-danger' : ''}
                                        ${task.status === 'IN_PROGRESS' ? 'bg-warning' : ''}
                                        ${task.status === 'COMPLETED' ? 'bg-success' : ''}"
                                        onclick="updateTaskStatus(this, '${task.status === 'TODO' ? 'IN_PROGRESS' : (task.status === 'IN_PROGRESS' ? 'COMPLETED' : 'TODO')}')">
                                        ${task.status}
                                    </button>
                                </form>
                            </div>
                        `;
                        taskItems.appendChild(taskItem);

                        // Replace placeholder TASK_ID with actual task ID
                        const form = taskItem.querySelector('form');
                        form.action = form.action.replace('TASK_ID', task.id);
                    });
                }
            });
        }
    </script>
</body>
</html>
