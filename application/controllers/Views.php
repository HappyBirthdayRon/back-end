<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Views extends Application
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/
	 * 	- or -
	 * 		http://example.com/welcome/index
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 public function index()
	    {
	        $this->data['pagetitle'] = 'Ordered TODO List';
	        $tasks = $this->tasks->all();   // get all the tasks
	        $this->data['content'] = 'Ok'; // so we don't need pagebody
			$this->data['leftside'] = $this->makePrioritizedPanel($tasks);
			$this->data['rightside'] = $this->makeCategorizedPanel($tasks);
	        $this->render('template_secondary');
	    }
	function makePrioritizedPanel($tasks) {
		// extract the undone tasks
		foreach ($tasks as $task)
		{
			if ($task->status != 2)
				$undone[] = $task;
		}

		// order them by priority
		usort($undone, "Views::orderByPriority");

		// substitute the priority name
		foreach ($undone as $task)
		    $task->priority = $this->app->priority($task->priority);

		// convert the array of task objects into an array of associative objects
		foreach ($undone as $task)
		    $converted[] = (array) $task;

		// and then pass them on
	    $parms = ['display_tasks' => $converted];
	    return $this->parser->parse('by_priority', $parms, true);
	}

	function makeCategorizedPanel($tasks)
	{
	    $parms = ['display_tasks' => $this->tasks->getCategorizedTasks()];
	    return $this->parser->parse('by_category', $parms, true);
	}


	// return -1, 0, or 1 of $a's priority is higher, equal to, or lower than $b's
	function orderByPriority($a, $b)
	{
	    if ($a->priority > $b->priority)
	        return -1;
	    elseif ($a->priority < $b->priority)
	        return 1;
	    else
	        return 0;
	}
}
