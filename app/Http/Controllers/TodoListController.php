<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListRequests\TodoListStoreRequest;

use App\Models\TodoList;
use App\Models\ListItem;

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

        foreach($todoLists as $todoList) {
            if (!ListItem::where('todo_list_id', $todoList->id)->exists()) {
                continue;
            }

            $itemsCount = ListItem::where([
                'is_complete' => 0,
                'todo_list_id' => $todoList->id,
            ])->count();
            
            $todoList->update(['is_all_complete' => ($itemsCount > 0) ? 0 : 1]);
        }

        return view('todo.index', compact('todoLists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\TodoListRequests\TodoListStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TodoListStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->title);

        $request->user()->todoLists()->create($validated);

        return redirect(route('todoLists.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\TodoListRequests\TodoListStoreRequest  $request
     * @param  \App\Models\TodoList  $todoList
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TodoListStoreRequest $request, TodoList $todoList): RedirectResponse
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
