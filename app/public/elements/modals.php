<div class="modal" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modal-login-label"><i class="fa fa-lock"></i> Login</h4>
			</div>
			<form class="form-horizontal" id="UserLoginForm" method="post" accept-charset="utf-8">
				<div class="modal-body">
							
						<div class="form-group">
							<label for="modal-login-username" class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="modal-login-username" maxlength="255" id="modal-login-username" required="required" placeholder="Username">
							</div>
						</div>
						<div class="form-group">
							<label for="modal-login-password" class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" name="modal-login-password" id="modal-login-password" required="required" placeholder="Password" />
							</div>
						</div>
						
						<div id="modal-login-error" class="alert alert-danger" style="display: none;" role="alert"></div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-info" data-dismiss="modal">Register</button>
					<button type="button" id="modal-login-submit" class="btn btn-primary">Login</button>
				</div>
			</form>
		</div>
	</div>
</div>