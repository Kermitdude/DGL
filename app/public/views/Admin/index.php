<script type="text/javascript" src="/assets/js/jquery.gridster.min.js"></script> <!-- Gridster.js -->

<div style="position: relative;">

	<div id="sidebar"></div>
	
	<div style="margin-left: 225px;">
		<h5>Dashboard <small>administrative services</small></h5>
		
		<div class="gridster">
			<ul></ul>
		</div>
		
	</div>

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

<script type="text/javascript">
	$(function(){
		DGL_nav_loadElement('adminMenu','#sidebar');
		$("#sidebar").on("click", "li", function() { $(this).addClass('active'); });
	});
</script>

