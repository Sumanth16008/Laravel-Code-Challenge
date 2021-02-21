@extends('main')
@section('main-content')

<div class="card">
    <div class="card-header">
    	<h3>User List</h3>
    </div>
    <div class="card-body">
    	@if(Session::has('message'))
         <p class="alert alert-success">{{ Session::get('message') }}</p>
        @endif
    	<div class="panel panel-primary">
    		<div class="panel-heading text-right pb-3">
    			<a href="{{ route('user.create') }}" class="btn btn-info btn-sm">
    				<i class="glyphicon glyphicon-plus"></i> Add New
    			</a>
    		</div>
    		<div class="panel-body">
    			<table class="table table-hover table-bordered table-stripped" id="users">
    				<thead>
    					<tr>
    						<th>S.No</th>
    						<th>User Name</th>
    						<th>Roles</th>
    						<th>Permissions</th>
    						<th style="width: 200px;">Action</th>
    					</tr>
    				</thead>
    				<tbody>
    				</tbody>
    			</table>
    		</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div id="deleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      	<h4 class="modal-title">Delete User</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      	<input type="hidden" id="user_id"/>
        <p>Do you want to delete user?</p>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-success" id="delete_user" >Ok</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-js-script')
    var url = '{{ url('')}}';
  var userTable =  $('#users').DataTable( {
    	"responsive": true,
    	"serverSide":true,
        "ajax":{
                url:"{{route('user-data-list')}}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
        "columns": [ 
				{"data":"id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                },
				{"data":"name"},
				{"data":"roles"},
				{"data":"permissions"},
				{ "data": "id",
			        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
			        	 $(nTd).html("<a href='"+url+"/user/"+oData.id+"/edit' class='btn btn-success'>Edit</a>&nbsp;<a href='#deleteModal' data-toggle='modal' data-id="+oData.id+" class='btn btn-danger delete_user'>Delete</a>");
			        }
			        }
            ],
            "bFilter": true,
            "bInfo": true,
            "aLengthMenu": [[10,25, 50, 75,100, -1], [10,25, 50, 75,100, "All"]],
            "columnDefs": [
                            { orderable: false, targets: [1,2,3,4] }
                          ],
    });
    
    $(document).on("click", ".delete_user", function () {
         var deleteId = $(this).data('id');
         $("#user_id").val( deleteId );
         // $('#deleteModal').modal('show');
    });
    
    $(document).on("click", "#delete_user", function () {
        var id = $("#user_id").val();
        $('#delete_user').attr('disabled', 'disabled');
          $.ajax({
                  url: url+'/user/'+id, 
                  type: "DELETE",
                  headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                  success: function(result){
                  	$('#delete_user').removeAttr("disabled");
                  	 $('#deleteModal').modal('hide');
                   	userTable.draw();
                  }
                  });
    });
@endsection