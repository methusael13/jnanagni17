@extends('reg.layout')

@section('title')
Event Registrations
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="css/reg/details.css">
@endsection

@section('script')
<script src="js/reg/details.js"></script>
@endsection

@section('content')
<div class="content">
    <div class="title title-small">Jnanagni Registrations</div>
    <div class="controls">
        <select id="cat-name">
            @foreach ($evtcats as $cat)
            <option>{{ $cat->getTitle() }}</option>
            @endforeach
        </select>
        <select id="evt-name"></select>
        <div>
            <input type="checkbox" name="sort" value="Sort" id="_sort">
            <label for="sort">Sort By Name</label>
        </div>
        <div class="btn animatable-all" id="fetch-btn">Fetch</div>
    </div>
    <div class="evt-title" id="evt-title-id"></div>
    <div class="data-section" id="data-section-id"></div>
    <div class="controls" id="exp-controls">
    <div class="btn animatable-all hidden" id="export-btn">Export to EXCEL</div>
    </div>
</div>
@endsection
