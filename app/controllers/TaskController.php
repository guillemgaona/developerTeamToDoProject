<?php

class TaskController extends Controller
{
	public function indexAction()
	{
		$tasks = new Task();
        $taskObjects = [];
        
        foreach ($tasks as $task) {
            $taskObjects[] = Task::fromArray($task);
        }
        
        $this->view->tasks = $taskObjects;
        $this->view->render('task/index');
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
