<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Tasks\TaskService;
use Validator;

class ToDoController extends Controller
{

    protected $tasks;

    /**
     * Dependency injection of TaskService class,
     * Setting middleware for store and destroy routes
     *
     * @param $tasks
     */
    public function  __construct(TaskService $tasks){
        $this->tasks = $tasks;
        $this->middleware('auth:api', ['only' => ['store', 'destroy']]);
    }


    /**
     * Display a listing of the tasks (10 per page)
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->tasks->getAllTasks();
        return response()->json($data);
    }


    /**
     * Store a newly created tasks in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        try{
            $data = $this->tasks->createTask($req);
            return response()->json($data,201);
        }catch (ModelNotFoundException $ex){
            throw $ex;
        }catch (NotFoundHttpException $ex){
            throw $ex;
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()],500);
        }
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $data = $this->tasks->getOneTask($id);

            return response()->json($data);

        }catch (ModelNotFoundException $ex){
            return response()->json(['status' => 'error', 'message' => 'Your route is empty'], 404);
        }catch (NotFoundHttpException $ex){
            return response()->json(['status' => 'error', 'message' => 'Bad route provided'], 404);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()],500);
        }          
    }


    /**
     * Remove the specified task from storage.
     * Only user who created that task can delete him
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $data = $this->tasks->deleteTask($id);
            return response()->json($data);
        }catch (ModelNotFoundException $ex){
            return response()->json(['status' => 'error', 'message' => 'Your route is empty'], 404);
        }catch (NotFoundHttpException $ex){
            return response()->json(['status' => 'error', 'message' => 'Bad route provided'], 404);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()],500);
        }
    }
}
