@extends('Tourify::master')
@section('tourifySection')
@if(Session::has('status'))
    <div class="{{ Session::get('status') }}">
        <p class="alert">{{ Session::get('msg') }}</p>
    </div>
@endif


<br>
<div class="main-display">
    <div class="headings">All Created Tours</div>
    <div class="toggle-view ">
        <a class="view-all remove-href tour-button" href="{{ route('createTour') }}">Create Tours</a>
    </div>
</div>
<br><br>
@if(count($allTours) > 0)
<table>
    <tr>
        <th class="add-border-radius-left">S.No</th>
        <th>Route</th>
        <th>File Name</th>
        <th class="add-border-radius-right">Actions</th>

    </tr>
    @foreach($allTours as $tour)
    <tr>
        <td>{{ $loop->index+1 }}</td>
        <td>{{ $tour->route }}</td>
        <td>{{ $tour->fileName }}</td>
        <td>
            <a class="remove-href" href="{{ route('editTour', ['tour' => $tour->id]) }}">Edit</a>
            <a class="remove-href" href="{{ route('deleteTour', ['tour' => $tour->id]) }}">Delete</a>
        </td>
    </tr>
    @endforeach
</table>
@else
No Tour created
@endif
@endsection
