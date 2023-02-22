<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListItemRequests\ListItemStoreRequest;
use App\Http\Requests\ListItemRequests\ListItemUpdateRequest;

use App\Models\ListItem;
use App\Models\TodoList;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Carbon\Carbon;

class ListItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ListItemRequests\ListItemStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ListItemStoreRequest $request): RedirectResponse
    {
        $request->user()->listItems()->create($request->validated());

        // to remove...
        TodoList::doesntHave('listItems')->where('user_id', auth()->id())->delete();

        return redirect(route('todoLists.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ListItemRequests\ListItemUpdateRequest  $request
     * @param  \App\Models\ListItem  $listItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ListItemUpdateRequest $request, ListItem $listItem): RedirectResponse
    {
        $validated = $request->validated();
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
