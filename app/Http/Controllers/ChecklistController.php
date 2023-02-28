<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChecklistRequests\ChecklistStoreRequest;
use App\Repositories\ChecklistRepository;
use App\Models\Checklist;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChecklistController extends Controller
{
    private ChecklistRepository $checklistRepository;

    public function __construct(ChecklistRepository $checklistRepository)
    {
        $this->checklistRepository = $checklistRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $checklists = $this->checklistRepository->getUserChecklists();

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
        $this->checklistRepository->storeChecklist($request->validated());

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
        $this->checklistRepository->updateChecklist($request->validated(), $checklist);

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
        $this->checklistRepository->deleteChecklist($checklist);

        return redirect(route('checklists.index'));
    }
}
