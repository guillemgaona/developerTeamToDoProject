<?php

class TaskController extends Controller
{

	private Task $taskModel;

	public function __construct() {
        parent::init();
        $this->taskModel = new Task();
    }

	public function indexAction()
	{

		try {
            $tasks = $this->taskModel->loadData();
            
            // Render the view with tasks
            /*$this->view->render('task/index.phtml', [
                'tasks' => $tasks,
                'pageTitle' => 'All Tasks'
            ]);*/
        } catch (Exception $e) {
            // Log error and show error page
            error_log('Error fetching tasks: ' . $e->getMessage());
            $this->view->render('error', [
                'message' => 'Unable to retrieve tasks'
            ]);
        }

		// $tasks = Task::loadData();
        // $taskObjects = [];
        
        // foreach ($tasks as $task) {
        //     $taskObjects[] = Task::fromArray($task);
        // }
        
        // $this->view->tasks = $taskObjects;
        // $this->view->render('task/index');
	}

	public function newAction() {

	}
	public function createAction() {
		
	}
	public function checkAction()
	{
		//echo "hello from test::check";
	}
}
