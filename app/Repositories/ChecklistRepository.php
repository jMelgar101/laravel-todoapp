<?php

namespace App\Repositories;

use App\Interfaces\ChecklistInterface;
use App\Models\Checklist;
use App\Models\Item;

use Illuminate\Support\Str;

class ChecklistRepository implements ChecklistInterface {
    public function getUserChecklists()
    {
        $checklists = Checklist::with('user')
            ->where('user_id', auth()->id())
            ->latest('updated_at')
            ->paginate(7);


        //
        // Should add Service
        //
        foreach($checklists as $checklist) {
            if (Item::where('checklist_id', $checklist->id)->doesntExist()) {
                $checklist->update(['is_all_complete' => 0]);

                continue;
            }

            $itemsCount = Item::where([
                'is_complete' => 0,
                'checklist_id' => $checklist->id,
            ])->count();
            
            $checklist->update(['is_all_complete' => ($itemsCount > 0) ? 0 : 1]);
        }

        return $checklists;
    }

    public function storeChecklist($checklistParams)
    {
        return Checklist::create([
            ...$checklistParams,
            'slug' => Str::slug($checklistParams['title']),
            'user_id' => auth()->id(),
        ]);
    }

    public function updateChecklist($checklistParams, $checklist)
    {
        return $checklist->update([
            ...$checklistParams,
            'slug' => Str::slug($checklistParams['title']),
        ]);
    }

    public function deleteChecklist($checklist)
    {
        return $checklist->delete();
    }
}
