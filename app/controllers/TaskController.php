<?php

class TaskController extends Controller
{
    private Task $taskModel;

    public function __construct() {
        parent::init();
        $this->taskModel = new Task();
    }

    public function indexAction() {
        try {
            // Load raw task data
            $tasksData = $this->taskModel->loadData();

            // Convert raw data to Task objects
            $tasks = [];
            foreach ($tasksData as $taskData) {
                $task = new Task(
                    $taskData['id'] ?? null,
                    $taskData['title'] ?? '',
                    $taskData['description'] ?? '',
                    Status::from($taskData['status'] ?? 'pending'),
                    $taskData['startTime'] ? new DateTime($taskData['startTime']) : null,
                    $taskData['endTime'] ? new DateTime($taskData['endTime']) : null,
                    $taskData['userId'] ?? null
                );
                $tasks[] = $task;
            }

            // Render the view with tasks
            $this->view->tasks = $tasks;
            $this->view->render('task/index.phtml');
        } catch (Exception $e) {
            // Log error and show error page
            error_log('Error fetching tasks: ' . $e->getMessage());
            $this->view->error = 'Unable to retrieve tasks';
            $this->view->render('error.phtml');
        }
    }

    public function addAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate and sanitize input
                $title = $_POST['title'] ?? '';
                $description = $_POST['description'] ?? '';
                $status = $_POST['status'] ?? 'pending';

                // Validate required fields
                if (empty($title)) {
                    throw new Exception('Title is required');
                }

                // Prepare data for saving
                $taskData = [
                    'title' => $title,
                    'description' => $description,
                    'status' => $status,
                    'startTime' => (new DateTime())->format('Y-m-d H:i:s'),
                    'userId' => $_SESSION['user_id'] ?? null // Assuming you have user sessions
                ];

                // Save the task
                $newTaskId = $this->taskModel->save($taskData);

                // Redirect to tasks list or show success message
                header('Location: /tasks');
                exit();
            } catch (Exception $e) {
                // Handle errors
                $this->view->error = $e->getMessage();
                $this->view->render('task/add.phtml');
            }
        } else {
            // Render add task form
            $this->view->render('task/add.phtml');
        }
    }

    public function showAction($id = null) {
        try {
            // If no ID is provided, redirect to tasks list
            if ($id === null) {
                header('Location: /tasks');
                exit();
            }

            // Fetch the specific task
            $taskData = $this->taskModel->fetchOne($id);

            // If task not found, show error
            if (!$taskData) {
                throw new Exception('Task not found');
            }

            // Create Task object
            $task = new Task(
                $taskData['id'] ?? null,
                $taskData['title'] ?? '',
                $taskData['description'] ?? '',
                Status::from($taskData['status'] ?? 'pending'),
                $taskData['startTime'] ? new DateTime($taskData['startTime']) : null,
                $taskData['endTime'] ? new DateTime($taskData['endTime']) : null,
                $taskData['userId'] ?? null
            );

            // Pass task to view
            $this->view->task = $task;
            $this->view->render('task/show.phtml');
        } catch (Exception $e) {
            // Handle errors
            $this->view->error = $e->getMessage();
            $this->view->render('error.phtml');
        }
    }

    public function editAction($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate and sanitize input
                $id = $_POST['id'] ?? $id;
                $title = $_POST['title'] ?? '';
                $description = $_POST['description'] ?? '';
                $status = $_POST['status'] ?? 'pending';

                // Validate required fields
                if (empty($title)) {
                    throw new Exception('Title is required');
                }

                // Prepare data for saving
                $taskData = [
                    'id' => $id,
                    'title' => $title,
                    'description' => $description,
                    'status' => $status,
                    'endTime' => $status === 'done' ? (new DateTime())->format('Y-m-d H:i:s') : null
                ];

                // Save the task
                $this->taskModel->save($taskData);

                // Redirect to tasks list or task details
                header('Location: /tasks');
                exit();
            } catch (Exception $e) {
                // Handle errors
                $this->view->error = $e->getMessage();
                $this->view->render('task/edit.phtml');
            }
        } else {
            try {
                // Fetch the specific task for editing
                $taskData = $this->taskModel->fetchOne($id);

                // If task not found, show error
                if (!$taskData) {
                    throw new Exception('Task not found');
                }

                // Create Task object
                $task = new Task(
                    $taskData['id'] ?? null,
                    $taskData['title'] ?? '',
                    $taskData['description'] ?? '',
                    Status::from($taskData['status'] ?? 'pending'),
                    $taskData['startTime'] ? new DateTime($taskData['startTime']) : null,
                    $taskData['endTime'] ? new DateTime($taskData['endTime']) : null,
                    $taskData['userId'] ?? null
                );

                // Pass task to view
                $this->view->task = $task;
                $this->view->render('task/edit.phtml');
            } catch (Exception $e) {
                // Handle errors
                $this->view->error = $e->getMessage();
                $this->view->render('error.phtml');
            }
        }
    }

    public function deleteAction($id = null) {
        try {
            // If no ID is provided, redirect to tasks list
            if ($id === null) {
                header('Location: /tasks');
                exit();
            }

            // Attempt to delete the task
            $deleted = $this->taskModel->delete($id);

            if ($deleted) {
                // Redirect to tasks list with success message
                header('Location: /tasks');
                exit();
            } else {
                throw new Exception('Unable to delete task');
            }
        } catch (Exception $e) {
            // Handle errors
            $this->view->error = $e->getMessage();
            $this->view->render('error.phtml');
        }
    }

    public function findTasksAction() {
        // Implement task search/filtering logic if needed
        // This could filter tasks by status, title, etc.
    }
}