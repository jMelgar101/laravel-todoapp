<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequests\ItemStoreRequest;
use App\Http\Requests\ItemRequests\ItemUpdateRequest;

use App\Models\Item;
use App\Models\Checklist;

use Illuminate\Http\RedirectResponse;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ItemRequests\ItemStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ItemStoreRequest $request): RedirectResponse
    {
        $request->user()->items()->create($request->validated());

        // to remove...
        Checklist::doesntHave('items')->where('user_id', auth()->id())->delete();

        return redirect(route('checklists.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ItemRequests\ItemUpdateRequest  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ItemUpdateRequest $request, Item $item): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_complete'] = $request->has('is_complete') ? 1 : 0;

        if ($validated['is_complete'] === 1) {
            $validated['completed_at'] = Carbon::now()->toDateString();
        }

        $item->update($validated);

        if ($request->has('parent_id')) {
            $childItemsCount = Item::where(['parent_id' => $request->parent_id, 'is_complete' => 0])->count();

            $isParentComplete = 0;

            if ($childItemsCount < 1) {
                $isParentComplete = 1;
            }

            Item::where('id', $request->parent_id)->update(['is_complete' => $isParentComplete]);
        } else {
            $item->subItems()->update($validated);
        }

        return redirect(route('checklists.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();

        return redirect(route('checklists.index'));
    }
}
