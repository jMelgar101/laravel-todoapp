<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListRequest;
use App\Models\TodoList;

use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $todoLists = TodoList::with('user')
            ->where('user_id', auth()->id())
            ->latest('updated_at')
            ->paginate(7);

        return view('todo.index', compact('todoLists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\TodoListRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TodoListRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->title);

        $request->user()->todoLists()->create($validated);

        return redirect(route('todoLists.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\TodoListRequest  $request
     * @param  \App\Models\TodoList  $todoList
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TodoListRequest $request, TodoList $todoList): RedirectResponse
    {
        $todoList->update($request->validated());

        return redirect(route('todoLists.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TodoList  $todoList
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TodoList $todoList): RedirectResponse
    {
        $todoList->delete();

        return redirect(route('todoLists.index'));
    }
}
