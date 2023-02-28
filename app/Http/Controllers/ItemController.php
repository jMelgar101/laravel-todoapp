<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequests\ItemStoreRequest;
use App\Http\Requests\ItemRequests\ItemUpdateRequest;

use App\Repositories\ItemRepository;
use App\Models\Item;

use Illuminate\Http\RedirectResponse;

class ItemController extends Controller
{
    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ItemRequests\ItemStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ItemStoreRequest $request): RedirectResponse
    {
        $this->itemRepository->storeItem($request->validated());

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
        $this->itemRepository->updateItem($request->validated(), $item);

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
        $this->itemRepository->deleteItem($item);

        return redirect(route('checklists.index'));
    }
}
