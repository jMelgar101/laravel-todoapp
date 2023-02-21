<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListItemRequest;
use App\Models\ListItem;
use App\Models\TodoList;

use Illuminate\Http\RedirectResponse;

class ListItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ListItem  $listItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ListItemRequest $request, ListItem $listItem): RedirectResponse
    {
        $listItem->update($request->validated());

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
