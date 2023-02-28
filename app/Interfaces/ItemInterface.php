<?php

namespace App\Interfaces;

use App\Models\Item;

interface ItemInterface
{
    public function storeItem(array $itemParams);
    public function updateItem(array $itemParams, Item $item);
    public function deleteItem(Item $item);
}
