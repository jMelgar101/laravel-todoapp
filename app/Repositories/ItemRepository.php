<?php

namespace App\Repositories;

use App\Interfaces\ItemInterface;
use App\Models\Checklist;
use App\Models\Item;

use Carbon\Carbon;

class ItemRepository implements ItemInterface {
    public function storeItem($itemParams)
    {
        $createdItem = Item::create([
            ...$itemParams,
            'user_id' => auth()->id(),
        ]);

        // to remove...
        Checklist::doesntHave('items')->where('user_id', auth()->id())->delete();

        return $createdItem;
    }

    public function updateItem($itemParams, $item)
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

    public function deleteItem($item){
        return $item->delete();
    }
}
