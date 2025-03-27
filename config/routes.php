<?php 

/**
 * Used to define the routes in the system.
 * 
 * A route should be defined with a key matching the URL and an
 * controller#action-to-call method. E.g.:
 * 
 * '/' => 'index#index',
 * '/calendar' => 'calendar#index'
 */
$routes = array(
	'/login' => 'auth#login',
    '/register' => 'auth#register',
    '/logout' => 'auth#logout',
    
    '/task/show' => 'task#show',
    '/tasks' => 'task#index', 
    '/task/findTasks' => 'task#findTasks',
    '/task/add' => 'task#add',
    '/task/delete' => 'task#delete',
    '/task/move' => 'task#move', 

    '/' => 'auth#login'
);
