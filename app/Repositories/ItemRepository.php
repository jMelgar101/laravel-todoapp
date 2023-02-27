<?php

namespace App\Repositories;

use App\Interfaces\ItemInterface;
use App\Models\Checklist;
use App\Models\Item;

use Carbon\Carbon;

class ItemRepository implements ItemInterface
{
    /**
     * Store a newly created checklist item
     *
     * @param  array validated $itemParams
     * @return \App\Models\Item $createdItem
     */
    public function storeItem($itemParams): Item
    {
        $createdItem = Item::create([
            ...$itemParams,
            'user_id' => auth()->id(),
        ]);

        // to remove...
        Checklist::doesntHave('items')->where('user_id', auth()->id())->delete();

        return $createdItem;
    }

    /**
     * Update the specified checklist item.
     *
     * @param  array  $itemParams
     * @param  \App\Models\Item  $item
     * @return bool
     */
    public function updateItem($itemParams, $item): bool
    {
        $itemParams['is_complete'] = (isset($itemParams['is_complete'])) ? 1 : 0;

        if ($itemParams['is_complete'] === 1) {
            $itemParams['completed_at'] = Carbon::now()->toDateString();
        }

        $updatedItem = $item->update($itemParams);

        if (isset($itemParams['parent_id'])) {
            $childItemsCount = Item::where(['parent_id' => $itemParams['parent_id'], 'is_complete' => 0])->count();

            $isParentComplete = 0;

            if ($childItemsCount < 1) {
                $isParentComplete = 1;
            }

            Item::where('id', $itemParams['parent_id'])->update(['is_complete' => $isParentComplete]);
        } else {
            $item->subItems()->update($itemParams);
        }

        return $updatedItem;
    }

    /**
     * Remove the specified checklist item
     *
     * @param  \App\Models\Item  $item
     * @return bool
     */
    public function deleteItem($item): bool
    {
        return $item->delete();
    }
}
