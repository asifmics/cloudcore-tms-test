<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreReuest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $tasks = Task::query()
            ->when(\request('status'), function ($q) {
                $q->where('status', \request('status'));
            })->latest()->get();

        return Inertia::render('Task/Index', props: [
            'tasks' => $tasks,
            'taskStatus' => ['pending', 'progress', 'completed'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Task/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreReuest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        Task::create($data);

        return redirect(route('tasks.index'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): Response
    {
        return Inertia::render('Task/Edit', props: [
            'task' => $task,
            'pending' => 'pending',
            'progress' => 'progress',
            'completed' => 'completed',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect(route('tasks.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): Application|Redirector|RedirectResponse
    {
        $task->delete();
        return redirect(route('tasks.index'));
    }
}
