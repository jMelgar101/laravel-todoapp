<?php

namespace App\Repositories;

use App\Interfaces\ChecklistInterface;
use App\Models\Checklist;
use App\Models\Item;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ChecklistRepository implements ChecklistInterface
{
    /**
     * Display paginated checklists of authorized user
     *
     * @return Illuminate\Contracts\Pagination\LengthAwarePaginator $checklists
     */
    public function getUserChecklists(): LengthAwarePaginator
    {
        $checklists = Checklist::with('user')
            ->where('user_id', auth()->id())
            ->latest('updated_at')
            ->paginate(7);


        //
        // Should add Service
        //
        foreach ($checklists as $checklist) {
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

    /**
     * Store a newly created checklist
     *
     * @param  array  $checklistParams
     * @return \App\Models\Checklist
     */
    public function storeChecklist($checklistParams): Checklist
    {
        return Checklist::create([
            ...$checklistParams,
            'slug' => Str::slug($checklistParams['title']),
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Update the specified checklist
     *
     * @param  array  $checklistParams
     * @param  \App\Models\Checklist  $checklist
     * @return bool
     */
    public function updateChecklist($checklistParams, $checklist): bool
    {
        return $checklist->update([
            ...$checklistParams,
            'slug' => Str::slug($checklistParams['title']),
        ]);
    }

    /**
     * Remove the specified checklist
     *
     * @param  \App\Models\Checklist  $checklist
     * @return bool
     */
    public function deleteChecklist($checklist): bool
    {
        return $checklist->delete();
    }
}
