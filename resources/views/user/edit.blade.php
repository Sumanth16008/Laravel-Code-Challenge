@extends('main')
@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('main-content')

<div class="card">
	<div class="card-header">
    	<h3>Update User</h3>
    </div>
    <div class="card-body">
    	@if ($errors->any())
    	    <div class="alert alert-danger">
    	        <ul>
    	            @foreach ($errors->all() as $error)
    	                <li>{{ $error }}</li>
    	            @endforeach
    	        </ul>
    	    </div>
    	@endif
		<form action="{{ route('user.update', ['user'=>$id])}}" class="col-12" method="post">
		{{ csrf_field() }}
		{{ method_field('PUT') }}
			<div class="form-group col-12 row" >
				<label class="col-4" for="user_name">Name :</label>
				<div class="col-8">
					<input type="text" class="form-control" id="user_name" name="user_name" value="{{$user['user_name']??null}}" required/>
				</div>
			</div>
			<div class="form-group col-12 row" >
				<label class="col-4" for="user_email">Roles :</label>
				<div class="col-8">
					<select class="form-control" id="user_roles" name="user_roles[]" multiple="multiple">
						@foreach($roles as $role)
						<option value="{{$role->id}}">{{$role->name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="row">
				<button type="submit" class="btn btn-success">Save</button>
			</div>
		</form>
	</div>
</div>
@endsection
@section('js-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
@section('page-js-script')
 $('#user_roles').select2();
 @if(!empty($user['user_roles']))
  var roles = [];
  @foreach($user['user_roles'] as $role)
  roles.push({{$role}})
  @endforeach
 	$('#user_roles').val(roles).trigger('change')
 @endif
@endsection