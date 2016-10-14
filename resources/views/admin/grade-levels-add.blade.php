@extends('layouts.app')

@section('title') Add New Grade Level - Admin - Student Information System @endsection

@section('content')
<div id="wrapper">

    {{-- Includes Admin Menu --}}
    @include('admin.admin-menu')

     <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">Add New Grade Level</h3>
            </div>
            
        </div>
		<div class="row">
        	<div class="col-lg-8 col-md-12">
        		{{-- Includes errors and session flash message display container --}}
                @include('includes.errors')
                @include('includes.error')
                @include('includes.success')
                @include('includes.notice')
	        	<div class="panel panel-primary">
	        		<div class="panel-heading">
	        			<strong><i class="fa fa-list-alt fa-lg" aria-hidden="true"></i> Grade Level Details</strong>
	        		</div>
	        		<div class="panel-body">
	        			<form action="{{ route('admin_post_add_grade_level') }}" method="POST" autocomplete="off">
	        				<div class="form-group">
	        					<input type="text" name="code" class="form-control text-uppercase" placeholder="Grade Level Code" />
	        				</div>
	        				<div class="form-group">
	        					<input type="text" name="title" class="form-control" placeholder="Grade Level Title" />
	        				</div>
		        			<div class="form-group">
		        				<textarea name="description" id="description" cols="30" rows="10" class="form-control" placeholder="Description of the Grade Level..."></textarea>
		        			</div>
		        			<div class="form-group">
		        				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		        				<button class="btn btn-primary">Add Grade Level</button>
		        			</div>
		        		</form>
	        		</div>
	        	</div>
        		
        	</div>
        </div>
       
    </div>

</div>
@endsection