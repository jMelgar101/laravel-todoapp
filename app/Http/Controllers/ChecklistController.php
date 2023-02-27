<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChecklistRequests\ChecklistStoreRequest;

use App\Models\Checklist;
use App\Models\Item;

use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $checklists = Checklist::with('user')
            ->where('user_id', auth()->id())
            ->latest('updated_at')
            ->paginate(7);

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

        return view('todo.index', compact('checklists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ChecklistRequests\ChecklistStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ChecklistStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->title);

        $request->user()->checklists()->create($validated);

        return redirect(route('checklists.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ChecklistRequests\ChecklistStoreRequest  $request
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ChecklistStoreRequest $request, Checklist $checklist): RedirectResponse
    {
        $checklist->update($request->validated());

        return redirect(route('checklists.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Checklist $checklist): RedirectResponse
    {
        $checklist->delete();

        return redirect(route('checklists.index'));
    }
}
