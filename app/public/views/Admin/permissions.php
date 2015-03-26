<div class="admin">

	<nav id="sidebar">		
		<ul class="nav nav-pills nav-stacked" data-spy="affix" data-offset-top="50">
			<li><a href="/admin">Dashboard</a></li>
			<li><a href="/admin/users">Users</a></li>
			<li><a href="/admin/roles">Roles</a></li>
			<li class="active"><a href="/admin/permissions">Permissions</a></li>
			<li><a href="/admin/stats">Statistics</a></li>
			<li><a href="/admin/logs">Logs</a></li>
			<li><a href="/admin/settings">Settings</a></li>
		</ul>
	</nav>
	
	<main>
	
		<h5>Permissions</h5>
		
		<div style="text-align: right;">
		
			<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
				 <i class="fa fa-plus-square"></i> Add Permission
			</button>
			
			<div class="collapse" id="collapseExample">
				<div class="well">
					...
				</div>
			</div>
		
		</div>
		
		<hr />
		
		<table id="permissions-table" class="table stripe hover">
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Description</th>
				</tr>
			</thead>
		</table>
		
	</main>	

</div>


<script type="text/javascript">
$(document).ready(function()
{	
	var data = <?= $permissionsTable; ?>;
	
	// Initialize datatable
	var table = $('.admin #permissions-table').DataTable({
		data: data,
        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "name" },
            { "data": "annotation" }
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
				'<td>Description:</td>'+
				'<td>'+d.annotation+'</td>'+
			'</tr>'+
		'</table>';
	}
	
    // Add event listener for opening and closing details
    $('.admin #permissions-table tbody').on('click', 'td.details-control', function () {
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