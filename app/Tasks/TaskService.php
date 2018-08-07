<?php

namespace App\Tasks;

use App\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskService 
{

    /**
     * Preparing tasks information to disply
     *
     * @param  $tasks
     * @return array $data
     */
    protected function ispis($tasks)
    {
        $data =[];
        foreach ($tasks as $task){
            $entry = [
                "Title:"                => $task->name,
                "Description:"          => $task->description,
                "Author:"               => $task->users->name,
                "Updated at:"           => $task->updated_at, 
                "Link:"                 => route('singleTask', $task->id),
            ];
            $data[] = $entry;
        }

        return $data;
    }


    /**
     * Display a listing of the tasks (10 per page)
     *
     * @return array
     */
    public function getAllTasks()
    {
        return $this->ispis(Task::paginate(10));
    }


    /**
    * Display the specified task.
    *
    * @param  int  $id
    * @return array
    */
    public function getOneTask($id)
    {
        return $this->ispis(Task::where('id',$id)->get());

    }

    /**
     * Remove the specified task from storage.
     * Only user who created that task can delete him
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteTask($id)
    {
        $task = Task::where('id',$id)->firstOrFail();

        if(Auth::user()->user_id !== $task->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized access'], 401);
        }
        $task->delete();
        return response()->json(['message' => 'Task successfully deleted'],200);
    }


    /**
     * Store a newly created tasks in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @return array
     */
    public function createTask(Request $req)
    {
        $task = $req->validate([
            "name"                => 'required|unique:tasks|max:255',
            "description"         => 'required',
        ]);
        $task = new Task();
        $task->name = $req->input('name');
        $task->description = $req->input('description');
        $task->user_id = Auth::user()->user_id;
        $task->save();

        return $this->ispis([$task]);
    }

}