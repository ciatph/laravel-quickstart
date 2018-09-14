# laravel-quickstart

A completed experimental demo app for testing laravel from [https://laravel.com/docs/5.2/quickstart](https://laravel.com/docs/5.2/quickstart). This is a simple TO-DO list of tasks project.

## Tools and Plugins

1. Composer
2. MySQL via xampp (7.2.9 / PHP 7.2.9)
2. VS Code for IDE
3. [Laravel Blade Snippets](https://marketplace.visualstudio.com/items?itemName=onecentlin.laravel-blade) as plugin for VS Code


## Usage

1. Clone this repository into your local directory.
2. Navigate to the cloned directory from the command line. Run `Composer install`.
3. Open `.env`. See `DB_DATABASE`. Create a `quickstart-demo` database in MySQL.
4. Run `php artisan make:migration`
5. Run this project: `php serve artisan`. Open the resulting URL to a web browser.


# Methods 

## A. Prepare your Development Environment

1. Create a database that your laravel project will use in MySQL.

2. Create an empty laravel project<br>
`composer create-project laravel/laravel list 5.7.*`, or you can use the static complete laravel 5.7.0 starter project files [here](https://github.com/ciatph/laravel-cache).

3. Navigate into your project directory from the command line.

4. Edit the following codes in `.env. The following have been used from local xampp

		APP_NAME=<YOUR_APP_NAME>
		DB_DATABASE=<YOUR_DB_NAME>
		DB_USERNAME=root
		DB_PASSWORD=

5. Scaffold the authentication system: <br>
`php artisan make:auth`

6. If you are using Laravel 5.4.* and MySQL less than 5.5, do the following first: Edit *app/Providers/AppServiceProvider.php*
	
		use Illuminate\Support\Facades\Schema;
		public function boot()
		{
			Schema::defaultStringLength(191);
		}


## B. Prepare the Database

1. **Database Migrations** - defines database tables to hold rows of data of a certain class/model. 


	`php artisan make:migration create_tasks_table --create=tasks`

	This will create a `/database/migrations/{{datetimestamp}}_create_tasks_table.php` which contains definitions for the Tasks database table. Add the `name` field after the auto-incrementing `id`:

        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

	Run the migration: `php artisan migrate`

	This command will create the *Tasks*, users and other relevant tables in your database.

2. **Create a Model** <br>
Models are ORMs (Object relational mappers) that makes it easy to retrieve and store data to databases using "Models". Each Eloquent model corresponds to a database table. 

	Create a Tasks Model: <br>
	`php artisan make:model Task`

	A model `/apps/Task.php` will be created.


## C. Routing

Routes are used to redirect/point URLs to certain functions or controllers that should be executed when a user accesses a page. The route is in `/routes/web.php` and this is included in every page.

1. **Create stub Routes** <br>
Write the following stub routes in `/routes/web.php`:

		<?php
		
		use App\Task;
		use Illuminate\Http\Request;
		
		// Show task dashboard
		Route::get('/', function() {
			
		});
		
		// Add a new task
		Route::post('/task', function() {
			
		});
		
		// Delete a task
		Route::delete('/task/{task}', function(Task $task) {
		
		});

2. **Display a View** <br>
Update the `get` route. This will fetch a view template from `/resources/views/tasks.blade.php`.

		Route::get('/', function() {
			return view('tasks');
		});

	See the following *Building Layouts and Views* for creating the views which this route loads.


## D. Building Layouts and Views

1. **Create a parent** *.blade.php* layout which will be common for all pages. Put it in `/resources/views/layouts/app.blade.php`

		<!-- resources/views/layouts/app.blade.php -->
		
		<!DOCTYPE html>
		<html lang="en">
		    <head>
		        <title>Laravel Quickstart - Basic</title>
		
		        <!-- CSS And JavaScript -->
		    </head>
		
		    <body>
		        <div class="container">
		            <nav class="navbar navbar-default">
		                <!-- Navbar Contents -->
		            </nav>
		        </div>
		
		        @yield('content')
		    </body>
		</html>

2. **Create the Child View:** a view that will contain a `form` for creating new tasks and a `table` that lists all existing tasks. Put this in `/resources/views/tasks.blade.php`

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
		@endsection


3. **Create an errors** template, put it in `/resources/views/common/errors.blade.php` 

	The `$errors` variable is available on all pages. It contains errors while processing data from the server. Display a list of `$errors` if there are any returned.

		<!-- resources/views/common/errors.blade.php -->
		
		@if (count($errors) > 0)
		    <!-- Form error list -->
		    <div class="alert alert-danger">
		        <strong>Something went wrong!</strong>
		        <br><br>
		
		        <ul>
		            @foreach ($errors->val() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif


## E. Adding Tasks

1. **Update the POST /task route.** from `/routes/web.php` This will *validate* and *insert* incoming form data, if there are no validation errors Write the following:

		Route::post('/task', function(Request $request) {
			$validator = Validator::make($request->all(), [
				'name' => 'required|max:255'
			]);
		
			if($validator->fails()) {
				return redirect('/')
					->withInput()
					->withErrors($validator);
			}
		
			$task = new Task;
			$task->name = $request->name;
			$task->save();
		
			return redirect('/');
		});


2. **Display Existing/Saved Tasks** <br>
Update the `/` route to pass all existing tasks to the view. The `view()` function accepts server-side data that will be made available for the view as an array of key-value pair.

		Routes::get('/', function() {
			$tasks = Task::orderBy('create_at', 'ask')->get();
		
			return view('tasks', [
				'tasks' => $tasks
			]);
		});

	Update `tasks.blade.php`, write the following codes BELOW `<!-- TODO: Current Tasks -->` to display all existing tasks.


## F. Deleting Tasks

1. **Add a delete button** in `tasks.blade.php`. Write the following codes below *TODO: Delete button*


        <form action="{{ url('task/'.$task->id) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}

            <button type="submit" class="btn btn-danger">
                <i class="fa fa-trash"></i> Delete
            </button>
        </form>

2. **Update the DELETE route**. Write the following codes:

		Route::delete('/task/{task}', function(Task $task) {
		    $task->delete();
		    return redirect('/');
		});

	Laravel uses [*implicit model binding*](https://laravel.com/docs/5.2/routing#route-model-binding) which means, the whole row of data defined in `Rask $task` is hinted from the given URL parameter `{task}` (id). This is automatically processed from routes and controllers.

<br>

**Date Created:** 20180914 <br>
**Date Modified:** 20180914 