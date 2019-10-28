@extends('Tourify::master')
@section('tourifySection')
<div class="container">

    <div class="row">
        <div class="col-12">

            <div class="form__group">
                <label class="input-label" for="routes">Route Name</label>
                <br>
                <select class="form__field form-control" id="routes">
                    <option class="form-control"  value="{{$tour->route}}">{{ $tour->route }}</option>
                </select>
            </div>

            <div class="form__group">
                <label class="input-label" for="tour-name">Tour Name</label>
                    <br>
                <input type="text" class="form__field form-control" value="{{ explode('.',$tour->file_name)[0] }}" id="tour-name" placeholder="Enter the Tour Name">
            </div>

            <br><br>
            <div id="table-buttons">
                <button class="tour-button" id="add-new-tour">Add New Row</button>
                <button class="tour-button" id="remove-selected-tour">Delete Selected Row</button>
                <button class="tour-button" data-tour-id="{{$tour->id}}" id="update-tour">Update</button>
                <a style="float: right" class="view-all remove-href tour-button" href="{{ route('listTour') }}">View All Tours</a>
            </div>
            <br>
            <br>
            <table id="tour-details">
                <tr>
                    <th class="add-border-radius-left">Select</th>
                    <th>No</th>
                    <th>div ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th class="add-border-radius-right">Placement</th>
                </tr>
                @foreach($allTourSteps as $tourStep)
                    <tr>
                        <td>
                            <label>
                                <input type="checkbox" name="table-selection">
                            </label>

                        </td>
                        <td>{{ $loop->index+1 }}</td>
                        <td contenteditable="true">{{ $tourStep['target'] }}</td>
                        <td contenteditable="true">{{ $tourStep['title'] }}</td>
                        <td contenteditable="true">{{ $tourStep['content'] }}</td>
                        <td contenteditable="true">
                            <label>
                                <select class="form__field">
                                    <option @if($tourStep['placement'] === 'right') selected @endif value="right">Right</option>
                                    <option @if($tourStep['placement'] === 'left') selected @endif value="left">Left</option>
                                    <option @if($tourStep['placement'] === 'bottom') selected @endif value="bottom">Bottom</option>
                                    <option @if($tourStep['placement'] === 'top') selected @endif value="top">Top</option>
                                </select>
                            </label>
                        </td>
                    </tr>
                @endforeach
            </table>

        </div>
    </div>
</div>
@endsection
