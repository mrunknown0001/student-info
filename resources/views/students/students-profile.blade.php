@extends('layouts.app')

@section('title') My Profile - Student - Student Information System @endsection

@section('content')
{{-- Includes Student's Menu --}}
@include('students.students-menu')
<div class="container-fluid">

    <div class="row">
    	<div class="col-lg-6 col-md-12 col-lg-offset-2">
            <h3>My Profile <a href="{{ route('students_profile_update') }}"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a></h3>
            {{-- Includes errors and session flash message display container --}}
            @include('includes.errors')
            @include('includes.error')
            @include('includes.success')
            @include('includes.notice')
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong><i class="fa fa-user fa-lg" aria-hidden="true"></i> Student's Details</strong>
                </div>
                <div class="panel-body">
                    <ul>
                        <li class="profile">LRN: {{ $s->user_id }}</li>
                        <li class="profile">Name: {{ $s->firstname }} {{ $s->lastname }}</li>
                        <li class="profile">Email: {{ $s->email }}</li>
                        <li class="profile">Mobile Number: {{ $s->mobile }}</li>
                        <li class="profile">Birthday: {{ date('F j, Y', strtotime($s->birthday)) }}</li>
                    </ul>
                </div>
            </div>
            <p class="center"><a href="{{ route('students_view_full_profole_data') }}">View Full Profile Data</a></p>
    	</div>
    </div>

</div>
@endsection