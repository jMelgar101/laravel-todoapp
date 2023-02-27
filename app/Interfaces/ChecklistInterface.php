<?php

namespace App\Interfaces;

interface ChecklistInterface {
    public function getUserChecklists();
    public function storeChecklist($checklistParams);
    public function updateChecklist($checklistParams, $checklist);
    public function deleteChecklist($checklist);
}
