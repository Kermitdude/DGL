<script type="text/javascript" src="/assets/js/jquery.gridster.min.js"></script> <!-- Gridster.js -->

<div class="admin">

	<nav id="sidebar">		
		<ul class="nav nav-pills nav-stacked" data-spy="affix" data-offset-top="50">
			<li class="active"><a href="/admin">Dashboard</a></li>
			<li><a href="/admin/users">Users</a></li>
			<li><a href="/admin/roles">Roles</a></li>
			<li><a href="/admin/permissions">Permissions</a></li>
			<li><a href="/admin/stats">Statistics</a></li>
			<li><a href="/admin/logs">Logs</a></li>
			<li><a href="/admin/settings">Settings</a></li>
		</ul>
	</nav>
	
	<main>
		<h5>Dashboard <small>administrative utilities</small></h5>
		
		<div class="gridster">
			<ul></ul>
		</div>
		
	</main>

    <script type="text/javascript">
		var gridster;
		var boxes = [
			'users-latest', 
			'analytics',
			'users-logged',
			'recent-entries',
			'matches-monthly',
			'matches-latest',
			'errors',
			'messages'];			

		$(function(){
			
			gridster = $(".gridster ul").gridster(
			{
				widget_base_dimensions: [140, 120],
				widget_margins: [10, 10],
				helper: 'clone',
				min_cols: 6,
				resize: 
				{
					enabled: true,
					max_size: [3, 3],
					stop: function(e, ui, $widget) 
					{
						gridSave();
					}
				},
				draggable: 
				{
					stop: function(e, ui, $widget) 
					{
						gridSave();
					}
				}
			}).data('gridster');
			
			gridFetch();
		});
		
		function gridSave()
		{
			var s = gridster.serialize();
			localStorage['admin-dashboard'] = JSON.stringify(s);
			//console.log(JSON.stringify(s));
		}
		
		function gridFetch()
		{
			var defaultGrid = '[{"col":1,"row":1,"size_x":2,"size_y":1},{"col":3,"row":5,"size_x":1,"size_y":1},{"col":3,"row":1,"size_x":2,"size_y":1},{"col":4,"row":5,"size_x":1,"size_y":1},{"col":1,"row":3,"size_x":2,"size_y":3},{"col":2,"row":2,"size_x":1,"size_y":1},{"col":3,"row":2,"size_x":2,"size_y":3},{"col":1,"row":2,"size_x":1,"size_y":1}]';
			var savedGrid = localStorage['admin-dashboard'];
			var s = savedGrid ? savedGrid : defaultGrid;
			s = JSON.parse(s);
			for (var i = 0; i < s.length; i++)
			{
				gridster.add_widget('<li id="admin-dashboard-box-' + boxes[i] + '" />', s[i].size_x, s[i].size_y, s[i].col, s[i].row);
			}
		}
    </script>
	
	
</div>
