@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8 card-container">
                <div class="card-group h-100">
                    <div class="card h-100 d-flex flex-column justify-content-between">
                        <div class="list-group list-group-flush" id="list-tab" role="tablist">
                            <div class="list-group-item">
                                <form class="row" method="POST" action="{{ route('todoLists.store') }}">
                                    @csrf
                                    <div class="input-group">
                                        <input name="title" type="text" class="form-control"
                                            placeholder="Add new list - Title" aria-label="Add new list" required>
                                        <button type="submit" class="btn btn-primary">Add List</button>
                                    </div>
                                </form>
                            </div>

                            <?php $carbon = Carbon\Carbon::class; ?>
                            @forelse ($todoLists as $todoList)
                                @php
                                    $lastUpdated = $carbon::parse($todoList->updated_at)->diffForHumans(null, null, true);
                                    $tabId = $todoList->slug . $todoList->id;
                                    $setActive = $loop->first ? 'active' : '';
                                    
                                    $subTitle = empty($todoList->listItems->first()) ? 'No additional text' : $todoList->listItems->first()->name;
                                @endphp
                                <a class="list-group-item list-group-item-action {{ $setActive }}"
                                    alt-text="{{ $todoList->slug }}" id="{{ $tabId }}-list" data-bs-toggle="list"
                                    href="#{{ $tabId }}" role="tab" aria-controls="{{ $tabId }}">
                                    <strong class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        {{ Str::title($todoList->title) }}
                                        <small>{{ $lastUpdated }}</small>
                                    </strong>
                                    <small>
                                        {{ $subTitle }}
                                        {!! $todoList->is_all_complete ? '<span class="badge text-bg-success float-end">Completed</span>' : '' !!}
                                    </small>
                                </a>
                            @empty
                                <div class="list-group-item text-center">Add a list!</div>
                            @endforelse
                        </div>

                        @unless($todoLists->isEmpty())
                            <div class="card-footer">
                                <ul class="pagination justify-content-center mb-0">
                                    {{ $todoLists->links() }}
                                </ul>
                            </div>
                        @endunless
                    </div>

                    @if ($todoLists->isEmpty())
                        <div class="card h-100 d-flex flex-column justify-content-between text-center">
                            <div class="card-header">Your task items will display here. Create a new one!</div>
                        </div>
                    @else
                        <div class="card h-100 d-flex flex-column justify-content-between tab-content" id="nav-tabContent">
                            @foreach ($todoLists as $todoList)
                                @php
                                    $tabContentId = $todoList->slug . $todoList->id;
                                    $setActive = $loop->first ? 'active' : '';
                                @endphp

                                <div class="tab-pane fade show {{ $setActive }}" id="{{ $tabContentId }}"
                                    role="tabpanel" aria-labelledby="{{ $tabContentId }}-list">
                                    <div class="card-header">
                                        <strong><u>{{ Str::title($todoList->title) }}</u></strong>

                                        <form class="row float-end" method="POST" id="delete_list"
                                            action="{{ route('todoLists.destroy', $todoList) }}">
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
                                        <form class="mb-3" method="POST" action="{{ route('listItems.store') }}">
                                            @csrf
                                            <div class="input-group input-group-sm">
                                                <input name="name" id="list_item_name" type="text"
                                                    class="form-control w-25" placeholder="Add new task"
                                                    aria-label="Add new task" required>
                                                {{-- <input type="datetime-local" class="form-control" name="to_complete_by"
                                                    min="{{ $carbon::now()->format('Y-m-d\Th:i') }}"> --}}
                                                <input type="date" class="form-control" name="to_complete_by_date"
                                                    min="{{ $carbon::now()->toDateString() }}">
                                                <input type="time" class="form-control" name="to_complete_by_time"
                                                    min="{{ $carbon::now()->format('h:i') }}">
                                                <input name="todo_list_id" id="todo_list_id" value="{{ $todoList->id }}"
                                                    type="text" class="form-control" hidden>
                                                <button type="submit" class="btn btn-primary">Add Item</button>
                                            </div>
                                        </form>

                                        <ul class="list-group list-group-flush items-list" id="items-list">
                                            @forelse ($todoList->listItems as $listItem)
                                                {{-- Todo List items --}}
                                                <li class="list-group-item position-relative">
                                                    <div class="form-check">
                                                        <form method="POST"
                                                            action="{{ route('listItems.update', $listItem) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input class="form-check-input" type="checkbox"
                                                                name="is_complete" value="1"
                                                                id="is_complete_{{ $listItem->id }}"
                                                                {{ $listItem->is_complete ? 'checked' : '' }}
                                                                onchange="event.preventDefault(); this.closest('form').submit();">
                                                            <label class="form-check-label" for="is_complete">
                                                                {!! $listItem->is_complete ? '<del>' . $listItem->name . '</del>' : $listItem->name !!}
                                                            </label>
                                                            @php
                                                                $hasCompleteDate = false;
                                                                $hasCompleteTime = false;
                                                                $to_complete_by_date = '';
                                                                $to_complete_by_time = '';
                                                                
                                                                if (!is_null($listItem->to_complete_by_date)) {
                                                                    $hasCompleteDate = true;
                                                                    $to_complete_by_date = $carbon::create($listItem->to_complete_by_date)->format('M j');
                                                                }
                                                                
                                                                if (!is_null($listItem->to_complete_by_time)) {
                                                                    $hasCompleteTime = true;
                                                                    $to_complete_by_time = $carbon::create($listItem->to_complete_by_time)->format('h:ia');
                                                                }
                                                            @endphp
                                                            <small class="text-muted float-end me-3">
                                                                @php
                                                                    if ($hasCompleteDate && $hasCompleteTime) {
                                                                        echo $to_complete_by_date . ', ' . $to_complete_by_time;
                                                                    } elseif ($hasCompleteDate) {
                                                                        echo $to_complete_by_date;
                                                                    } elseif ($hasCompleteTime) {
                                                                        echo $to_complete_by_time;
                                                                    }
                                                                @endphp
                                                            </small>
                                                        </form>
                                                    </div>

                                                    {{-- Add sublist item --}}
                                                    <form class="form-group" method="POST"
                                                        action="{{ route('listItems.store', $listItem) }}">
                                                        @csrf
                                                        <div class="input-group input-group-sm">
                                                            <input name="name" id="sublist_item_name{{ $listItem->id }}"
                                                                type="text" class="form-control w-25"
                                                                placeholder="Add sub task" hidden required>
                                                            <input type="date" class="form-control"
                                                                name="to_complete_by_date"
                                                                id="sublist_item_date{{ $listItem->id }}" hidden
                                                                min="{{ $carbon::now()->toDateString() }}" max="">
                                                            <input type="time" class="form-control"
                                                                name="to_complete_by_time"
                                                                id="sublist_item_time{{ $listItem->id }}" hidden
                                                                min="{{ $carbon::now()->format('h:i') }}">
                                                            <input name="todo_list_id" id="todo_list_id"
                                                                value="{{ $todoList->id }}" type="text"
                                                                class="form-control" hidden>
                                                            <input name="parent_id" id="parent_id"
                                                                value="{{ $listItem->id }}" type="text"
                                                                class="form-control" hidden>
                                                            <button type="submit" class="btn btn-primary"
                                                                id="sublist_item_button{{ $listItem->id }}"
                                                                hidden>Add</button>
                                                        </div>
                                                    </form>

                                                    {{-- List Item Action buttons --}}
                                                    @if ($listItem->user->is(auth()->user()))
                                                        <div
                                                            class="float-end position-absolute top-0 end-0 mt-1 action-icons">
                                                            <a href="#"
                                                                onclick="document.getElementById('sublist_item_name{{ $listItem->id }}').removeAttribute('hidden');
                                                                    document.getElementById('sublist_item_date{{ $listItem->id }}').removeAttribute('hidden');
                                                                    document.getElementById('sublist_item_time{{ $listItem->id }}').removeAttribute('hidden');
                                                                    document.getElementById('sublist_item_button{{ $listItem->id }}').removeAttribute('hidden');">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-plus-circle me-1" viewBox="0 0 16 16">
                                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                                                  </svg>
                                                            </a>

                                                            <form class="row float-end" method="POST" id="delete_item"
                                                                action="{{ route('listItems.destroy', $listItem) }}">
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
                                                        @forelse ($listItem->subListItems as $subItem)
                                                            <li
                                                                class="list-group-item list-group-item-action position-relative border-0 pb-0">
                                                                <div class="form-check">
                                                                    <form method="POST"
                                                                        action="{{ route('listItems.update', $subItem) }}">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="is_complete" value="1"
                                                                            id="is_complete_{{ $subItem->id }}"
                                                                            {{ $subItem->is_complete ? 'checked' : '' }}
                                                                            onchange="event.preventDefault(); this.closest('form').submit();">
                                                                        <label class="form-check-label" for="is_complete">
                                                                            {!! $subItem->is_complete ? '<del>' . $subItem->name . '</del>' : $subItem->name !!}
                                                                        </label>
                                                                        @php
                                                                            $hasCompleteDate = false;
                                                                            $hasCompleteTime = false;
                                                                            $to_complete_by_date = '';
                                                                            $to_complete_by_time = '';
                                                                            
                                                                            if (!is_null($subItem->to_complete_by_date)) {
                                                                                $hasCompleteDate = true;
                                                                                $to_complete_by_date = $carbon::create($subItem->to_complete_by_date)->format('M j');
                                                                            }
                                                                            
                                                                            if (!is_null($subItem->to_complete_by_time)) {
                                                                                $hasCompleteTime = true;
                                                                                $to_complete_by_time = $carbon::create($subItem->to_complete_by_time)->format('h:ia');
                                                                            }
                                                                        @endphp
                                                                        <small class="text-muted float-end">
                                                                            @php
                                                                                if ($hasCompleteDate && $hasCompleteTime) {
                                                                                    echo $to_complete_by_date . ', ' . $to_complete_by_time;
                                                                                } elseif ($hasCompleteDate) {
                                                                                    echo $to_complete_by_date;
                                                                                } elseif ($hasCompleteTime) {
                                                                                    echo $to_complete_by_time;
                                                                                }
                                                                            @endphp
                                                                        </small>
                                                                    </form>

                                                                    <form
                                                                        class="float-end position-absolute top-0 end-0 mt-1"
                                                                        method="POST" id="delete_item"
                                                                        action="{{ route('listItems.destroy', $subItem) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="{{ route('listItems.destroy', $subItem) }}"
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
                                                                </div>
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
