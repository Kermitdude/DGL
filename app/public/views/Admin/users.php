<div class="admin">

	<nav id="sidebar">		
		<ul class="nav nav-pills nav-stacked" data-spy="affix" data-offset-top="50">
			<li><a href="/admin">Dashboard</a></li>
			<li class="active"><a href="/admin/users">Users</a></li>
			<li><a href="/admin/roles">Roles</a></li>
			<li><a href="/admin/permissions">Permissions</a></li>
			<li><a href="/admin/stats">Statistics</a></li>
			<li><a href="/admin/logs">Logs</a></li>
		</ul>
	</nav>
	
	<main>
	
		<h5>Users</h5>
		
		<div style="text-align: right;">
		
			<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
				 <i class="fa fa-plus-square"></i> Add User
			</button>
			
			<div class="collapse" id="collapseExample">
				<div class="well">
					...
				</div>
			</div>
		
		</div>
		
		<hr />
		
		<table id="users-table" class="table stripe hover">
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
					<th>E-mail</th>
					<th>Created</th>
				</tr>
			</thead>
		</table>
		
	</main>	

</div>


<script type="text/javascript">
$(document).ready(function()
{	
	var data = <?= $usersTable; ?>;
	
	// Initialize datatable
	var table = $('.admin #users-table').DataTable({
		data: data,
        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "name" },
            { "data": "email" },
            { "data": "created_at" },
        ],
	});

	/* Formatting function for row details - modify as you need */
	function format ( d ) {
		// `d` is the original data object for the row
		return '<table class="subtable table-condensed">'+
			'<tr>'+
				'<td>Full name:</td>'+
				'<td>'+d.name+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td>E-mail:</td>'+
				'<td>'+d.email+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td>Extra info:</td>'+
				'<td>And any further details here (images etc)...</td>'+
			'</tr>'+
		'</table>';
	}
	
    // Add event listener for opening and closing details
    $('.admin #users-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );
	
} );
</script>