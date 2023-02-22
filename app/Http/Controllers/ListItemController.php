<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListItemRequest;
use App\Models\ListItem;
use App\Models\TodoList;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ListItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ListItemRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ListItemRequest $request): RedirectResponse
    {
        $request->user()->listItems()->create($request->validated());

        // to remove...
        TodoList::doesntHave('listItems')->where('user_id', auth()->id())->delete();

        return redirect(route('todoLists.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ListItemRequest  $request
     * @param  \App\Models\ListItem  $listItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ListItem $listItem): RedirectResponse
    {
        // $validated = $request->validated();
        $validated = $request->only([
            'name',
            'is_complete',
            'to_complete_by',
            'todo_list_id',
            'parent_id',
        ]);

        $validated['is_complete'] = $request->has('is_complete') ? 1 : 0;

        if ($validated['is_complete'] === 1) {
            $validated['completed_at'] = Carbon::now()->toDateString();
        }

        $listItem->update($validated);
        $listItem->sublistItems()->update($validated);

        if ($request->filled('parent_id')) {
            $listItem->sublistItems()->update($validated);
        }

        // update todoList complete status
        $items_count = $listItem->todoList->listItems->where('is_complete', 0)->count();
        $listItem->todoList->update(['is_all_complete' => ($items_count > 0) ? 0 : 1]);

        return redirect(route('todoLists.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ListItem  $listItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ListItem $listItem): RedirectResponse
    {
        $listItem->delete();

        return redirect(route('todoLists.index'));
    }
}
