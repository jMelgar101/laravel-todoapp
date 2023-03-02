@extends('layouts.app')

@inject('formatDateTime', 'App\Services\DateTimeService')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8 card-container">
                <div class="card-group h-100">
                    <div class="card h-100 d-flex flex-column justify-content-between">
                        <div class="list-group list-group-flush" id="list-tab" role="tablist">
                            <div class="list-group-item">
                                <form method="POST" action="{{ route('checklists.store') }}">
                                    @csrf
                                    <div class="input-group">
                                        <input name="title" type="text" class="form-control"
                                            placeholder="Add new list - Title" aria-label="Add new list" required>
                                        <button type="submit" class="btn btn-primary">Add List</button>
                                    </div>
                                </form>
                            </div>

                            <?php $carbon = Carbon\Carbon::class; ?>
                            @forelse ($checklists as $checklist)
                                @php
                                    $lastUpdated = $carbon::parse($checklist->updated_at)->diffForHumans(null, null, true);
                                    $tabId = $checklist->slug . $checklist->id;
                                    $setActive = $loop->first ? 'active' : '';
                                    
                                    $subTitle = empty($checklist->items->first()) ? 'No additional text' : $checklist->items->first()->name;
                                @endphp
                                <a class="list-group-item list-group-item-action {{ $setActive }}"
                                    alt-text="{{ $checklist->slug }}" id="{{ $tabId }}-list" data-bs-toggle="list"
                                    href="#{{ $tabId }}" role="tab" aria-controls="{{ $tabId }}">
                                    <strong class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        {{ Str::title($checklist->title) }}
                                        <small>{{ $lastUpdated }}</small>
                                    </strong>
                                    <small>
                                        {{ $subTitle }}
                                        {!! $checklist->is_all_complete ? '<span class="badge text-bg-success float-end">Completed</span>' : '' !!}
                                    </small>
                                </a>
                            @empty
                                <div class="list-group-item text-center">Add a list!</div>
                            @endforelse
                        </div>

                        @unless($checklists->isEmpty())
                            <div class="card-footer">
                                <ul class="pagination justify-content-center mb-0">
                                    {{ $checklists->links() }}
                                </ul>
                            </div>
                        @endunless
                    </div>

                    @if ($checklists->isEmpty())
                        <div class="card h-100 d-flex flex-column justify-content-between text-center">
                            <div class="card-header">Your task items will display here. Create a new one!</div>
                        </div>
                    @else
                        <div class="card h-100 d-flex flex-column justify-content-between tab-content" id="nav-tabContent">
                            @foreach ($checklists as $checklist)
                                @php
                                    $tabContentId = $checklist->slug . $checklist->id;
                                    $setActive = $loop->first ? 'active' : '';
                                @endphp

                                <div class="tab-pane fade show {{ $setActive }}" id="{{ $tabContentId }}"
                                    role="tabpanel" aria-labelledby="{{ $tabContentId }}-list">
                                    <div class="card-header d-flex justify-content-between">
                                        <form action="{{ route('checklists.update', $checklist) }}" method="POST"
                                            class="w-auto">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="title" class="editable-input text-truncate"
                                                size="50" maxlength="50"
                                                value="{{ Str::title($checklist->title) }}"
                                                required>
                                        </form>

                                        <form action="{{ route('checklists.destroy', $checklist) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a
                                                onclick="confirm('Are you sure?'); event.preventDefault(); this.closest('form').submit();">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    fill="currentColor" class="bi bi-trash3 link-danger"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                                </svg>
                                            </a>
                                        </form>
                                    </div>
                                    <div class="card-body">
                                        <form class="mb-3" method="POST" action="{{ route('items.store') }}">
                                            @csrf
                                            <div class="input-group input-group-sm" {{ $checklist->is_all_complete ? 'hidden' : '' }}>
                                                <input name="name" id="item_name" type="text"
                                                    class="form-control w-25" placeholder="Add new task"
                                                    aria-label="Add new task" required>
                                                {{-- <input type="datetime-local" class="form-control" name="to_complete_by"
                                                    min="{{ $carbon::now()->format('Y-m-d\Th:i') }}"> --}}
                                                <input type="date" class="form-control" name="to_complete_by_date"
                                                    min="{{ $carbon::now()->toDateString() }}">
                                                <input type="time" class="form-control" name="to_complete_by_time"
                                                    min="{{ $carbon::now()->format('h:i') }}">
                                                <input name="checklist_id" id="checklist_id" value="{{ $checklist->id }}"
                                                    type="text" class="form-control" hidden>
                                                <button type="submit" class="btn btn-primary">Add Item</button>
                                            </div>
                                        </form>

                                        <ul class="list-group list-group-flush items-list" id="items-list">
                                            @forelse ($checklist->items as $item)
                                                {{-- Todo List items --}}
                                                <li class="list-group-item position-relative">
                                                    <div class="form-check list-group-item-action">
                                                        <form action="{{ route('items.update', $item) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input class="form-check-input" type="checkbox"
                                                                {{ $item->is_complete ? 'checked' : '' }}
                                                                onchange="event.preventDefault(); this.closest('form').submit();">
                                                            <input type="hidden" name="is_complete"
                                                                value="{{ ($item->is_complete + 1) % 2 }}">
                                                        </form>
                                                        <form action="{{ route('items.update', $item) }}" method="POST"
                                                            class="d-flex justify-content-between align-items-center">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="text" name="name"
                                                                class="editable-input text-truncate {{ $item->is_complete ? 'text-decoration-line-through' : '' }}"
                                                                size="35" maxlength="100"
                                                                value="{{ $item->name }}"
                                                                onkeypress="this.style.width = ((this.value.length + 1) * 8) + 'px';"
                                                                {{ $item->is_complete ? 'disabled' : '' }} required>

                                                            <small class="text-muted me-3">
                                                                {{ $formatDateTime->formatCompleteDateTime($item->to_complete_by_date, $item->to_complete_by_time) }}
                                                            </small>
                                                        </form>
                                                    </div>

                                                    {{-- Add sublist item --}}
                                                    <form action="{{ route('items.store', $item) }}" method="POST"
                                                        class="form-group">
                                                        @csrf
                                                        <div class="input-group input-group-sm">
                                                            <input name="name" id="subItem_name{{ $item->id }}"
                                                                type="text" class="form-control w-25"
                                                                placeholder="Add sub task" hidden required>
                                                            <input type="date" class="form-control"
                                                                name="to_complete_by_date"
                                                                id="subItem_date{{ $item->id }}" hidden
                                                                min="{{ $carbon::now()->toDateString() }}"
                                                                max="{{ !is_null($item->to_complete_by_date) ? $carbon::create($item->to_complete_by_date)->toDateString() : '' }}">
                                                            <input type="time" class="form-control"
                                                                name="to_complete_by_time"
                                                                id="subItem_time{{ $item->id }}" hidden
                                                                min="{{ $carbon::now()->format('h:i') }}">

                                                            <input name="checklist_id" id="checklist_id"
                                                                value="{{ $checklist->id }}" type="hidden">
                                                            <input name="parent_id" id="parent_id"
                                                                value="{{ $item->id }}" type="hidden">
                                                            <button type="submit" class="btn btn-primary"
                                                                id="subItem_button{{ $item->id }}" hidden>Add</button>
                                                        </div>
                                                    </form>

                                                    {{-- List Item Action buttons --}}
                                                    @if ($item->user->is(auth()->user()))
                                                        <div {{ $item->is_complete ? 'hidden' : '' }}
                                                            class="float-end position-absolute top-0 end-0 mt-2 action-icons">
                                                            <a href="#"
                                                                onclick="document.getElementById('subItem_name{{ $item->id }}').removeAttribute('hidden');
                                                                    document.getElementById('subItem_date{{ $item->id }}').removeAttribute('hidden');
                                                                    document.getElementById('subItem_time{{ $item->id }}').removeAttribute('hidden');
                                                                    document.getElementById('subItem_button{{ $item->id }}').removeAttribute('hidden');">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                    height="12" fill="currentColor"
                                                                    class="bi bi-plus-circle me-1" viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                                    <path
                                                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                                                </svg>
                                                            </a>

                                                            <form class="row float-end" method="POST" id="delete_item"
                                                                action="{{ route('items.destroy', $item) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a
                                                                    onclick="confirm('Are you sure?'); event.preventDefault(); this.closest('form').submit();">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                        height="12" fill="currentColor"
                                                                        class="bi bi-trash3 link-danger"
                                                                        viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                                                    </svg>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endif

                                                    {{-- Sublist Items --}}
                                                    <ul class="list-group list-group-flush">
                                                        @forelse ($item->subItems as $subItem)
                                                            <li class="list-group-item position-relative border-0 pb-0">
                                                                <div class="form-check list-group-item-action">
                                                                    <form action="{{ route('items.update', $subItem) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input class="form-check-input" type="checkbox"
                                                                            {{ $subItem->is_complete ? 'checked' : '' }}
                                                                            onchange="event.preventDefault(); this.closest('form').submit();">
                                                                        <input type="hidden" name="is_complete"
                                                                            value="{{ ($subItem->is_complete + 1) % 2 }}">
                                                                        <input type="hidden" name="parent_id"
                                                                            value="{{ $subItem->parent_id }}">
                                                                    </form>

                                                                    <form action="{{ route('items.update', $subItem) }}"
                                                                        method="POST"
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="text" name="name"
                                                                            class="editable-input text-truncate {{ $subItem->is_complete ? 'text-decoration-line-through' : '' }}"
                                                                            size="33" maxlength="100"
                                                                            value="{{ $subItem->name }}"
                                                                            {{ $subItem->is_complete ? 'disabled' : '' }}
                                                                            required>

                                                                        <small class="text-muted">
                                                                            {{ $formatDateTime->formatCompleteDateTime($subItem->to_complete_by_date, $subItem->to_complete_by_time) }}
                                                                        </small>
                                                                    </form>
                                                                </div>

                                                                <form class="float-end position-absolute top-0 end-0 mt-2"
                                                                    method="POST" id="delete_item"
                                                                    action="{{ route('items.destroy', $subItem) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="{{ route('items.destroy', $subItem) }}"
                                                                        {{ $subItem->is_complete ? 'hidden' : '' }}
                                                                        class="action-icons"
                                                                        onclick="confirm('Are you sure?'); event.preventDefault(); this.closest('form').submit();">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="12" height="12"
                                                                            fill="currentColor"
                                                                            class="bi bi-trash3 link-danger"
                                                                            viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                                                        </svg>
                                                                    </a>
                                                                </form>
                                                            </li>
                                                        @empty
                                                        @endforelse
                                                    </ul>
                                                </li>
                                            @empty
                                                <p class="card-text text-center mt-4">No task. Add a task!</p>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
