<?php

namespace App\Interfaces;

interface ItemInterface {
    public function storeItem($itemParams);
    public function updateItem($itemParams, $item);
    public function deleteItem($item);
}
