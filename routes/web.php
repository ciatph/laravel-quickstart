<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Task;
use Illuminate\Http\Request;


// Show task dashboard
Route::get('/', function() {
    return view('tasks');
});

// Add a new task
Route::post('/task', function() {

});

// Delete a task
Route::delete('/task/{task}', function(Task $task) {

});