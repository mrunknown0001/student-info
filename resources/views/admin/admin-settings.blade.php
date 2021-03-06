@extends('layouts.app')

@section('title') Settings - Admin - Student Information System @endsection

@section('content')
<div id="wrapper">

    {{-- Includes Admin Menu --}}
    @include('admin.admin-menu')

     <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">Admin Settings</h3>
            </div>
            
        </div>
        <div class="row">
        	<div class="col-lg-8 col-md-12">
        		{{-- Includes Errors and Success Message templates --}}
                @include('includes.errors')
                @include('includes.error')
                @include('includes.success')
                @include('includes.notice')
	        	<div class="panel panel-primary">
	        		<div class="panel-heading">
	        			<strong><i class="fa fa-key fa-lg" aria-hidden="true"></i> Change Password</strong>
	        		</div>
	        		<div class="panel-body">
	        			<form action="{{ route('admin_post_change_password') }}" method="POST">
	        				<div class="form-group">
	        					<input type="password" name="old_pass" class="form-control" placeholder="Old Password" />
	        				</div>
	        				<div class="form-group">
	        					<input type="password" name="password" class="form-control" placeholder="New Password" />
	        				</div>
		        			<div class="form-group">
	        					<input type="password" name="password_confirmation" class="form-control" placeholder="Confrim Password" />
		        			</div>
		        			<div class="form-group">
		        				{{ csrf_field() }}
		        				<button class="btn btn-primary">Change Password</button>
		        			</div>
		        		</form>
	        		</div>
	        	</div>
        		<p><i>Note: Use alpha-numeric password, minimum password: 8</i></p>
        		
        	</div>
        </div>

       
    </div>

</div>
@endsection