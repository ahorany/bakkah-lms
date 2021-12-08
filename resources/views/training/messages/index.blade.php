@extends('layouts.app')

@section('useHead')
    <title>{{__('education.Messages')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')
    <div class="card p-5">
        <form action="">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label for="course">Choose Course:</label>
                        <select name="course_id" id="course" class="form-control" style="width: 75%;">
                            <option value="" disabled selected>Choose Course...</option>
                            <option value="">aaa</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label for="subject">Subject:</label>
                        <input type="text" name="subject" id="subject" class="form-control" style="width: 75%;">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label for="description">description:</label>
                        <input type="text" name="description" id="description" class="form-control" style="width: 75%;">
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
