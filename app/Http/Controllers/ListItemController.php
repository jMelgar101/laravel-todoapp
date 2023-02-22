<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListItemRequest;
use App\Models\ListItem;
use App\Models\TodoList;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $validated = $request->only(['is_complete']);
        $validated['is_complete'] = $request->has('is_complete') ? 1 : 0;

        $listItem->update($validated);
        $listItem->sublistItems()->update($validated);

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
