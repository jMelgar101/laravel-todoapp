<?php

namespace App\Interfaces;

use App\Models\Checklist;

interface ChecklistInterface
{
    public function getUserChecklists();
    public function storeChecklist(array $checklistParams);
    public function updateChecklist(array $checklistParams, Checklist $checklist);
    public function deleteChecklist(Checklist $checklist);
}
