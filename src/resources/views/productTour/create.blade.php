@extends('Tourify::master')
@section('tourifySection')
    <div class="container">

        <div class="row">
            <div class="col-12">

                    <div class="form-group form__group">
                        <label class="input-label" for="routes">Route Name</label>
                        <br>
                        <select class="form__field form-control" id="routes">
                            @if(count($allGetRoutes) > 0)
                                @foreach($allGetRoutes as $value)
                                    @if(in_array($value,$existingTour) || substr($value, 0, 3) === 'api' || substr($value, 0, 11) === 'productTour')
                                        <option class="form-control"  value="{{$value}}" disabled>{{ $value }}</option>
                                    @else
                                        <option class="form-control"  value="{{$value}}">{{ $value }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group form__group">
                        <label class="input-label" for="tour-name">Tour Name</label>
                        <br>
                        <input type="text" class="form__field form-control" id="tour-name" placeholder="Enter the Tour Name">
                    </div>
                    <br><br>
                    <div id="table-buttons">
                        <button id="add-new-tour">Add New Row</button>
                        <button id="remove-selected-tour">Delete Selected Row</button>
                        <button id="save-tour">Save</button>
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
                    <tr>
                        <td>
                            <label>
                                <input type="checkbox" name="table-selection">
                            </label>

                        </td>
                        <td>1</td>
                        <td contenteditable="true">#div</td>
                        <td contenteditable="true">Default Title</td>
                        <td contenteditable="true">Default Content</td>
                        <td contenteditable="true">
                            <label>
                                <select class="form__field">
                                    <option value="right">Right</option>
                                    <option value="left">Left</option>
                                    <option value="bottom">Bottom</option>
                                    <option value="top">Top</option>
                                </select>
                            </label>
                        </td>
                    </tr>

                </table>

                {{--                </form>--}}
            </div>
        </div>
    </div>
@endsection
