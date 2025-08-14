<!DOCTYPE html>
<html lang="en">

<head>
	<title>Dharitee Data Entry</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php $this->load->view('header'); ?>
	<style>
		.card {
			margin: 0 auto;
			/* Added */
			float: none;
			/* Added */
			margin-bottom: 10px;
			/* Added */
			/* margin-top: 50px; */
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="card col-md-6 p-0" id="loc_save">
			<div class="card-header bg-info">
				<div class="text text-center font-weight-bold">
					<h5>User Edit</h5>
				</div>
			</div>
			<div class="card-body">
				<form action="<?= base_url('index.php/survey-user/' . urlencode($user->username) . '/update') ?>" id="userUpdateForm" autocomplete="off">
					<div class="form-group">
						<label for="user_code">User Type *</label>
						<select required name="user_code" id="user_code" class="form-control form-control-sm" disabled>
							<option value="">--Select--</option>
							<option value="<?php echo ($this->UserModel::$SUPERVISOR_CODE) ?>" <?= $user->user_role == $this->UserModel::$SUPERVISOR_CODE ? 'selected' : '' ?>>Supervisor</option>
							<option value="<?php echo ($this->UserModel::$SURVEYOR_CODE) ?>" <?= $user->user_role == $this->UserModel::$SURVEYOR_CODE ? 'selected' : '' ?>>Surveyor</option>
							<option value="<?php echo ($this->UserModel::$SURVEY_GIS_ASSISTANT_CODE) ?>" <?= $user->user_role == $this->UserModel::$SURVEY_GIS_ASSISTANT_CODE ? 'selected' : '' ?>>GIS Assistant</option>
							<option value="<?php echo ($this->UserModel::$SPMU_CODE) ?>" <?= $user->user_role == $this->UserModel::$SPMU_CODE ? 'selected' : '' ?>>SPMU</option>
						</select>
						<span class="error text-danger user_code_error"></span>
					</div>

					<div class="form-group" id="name_div">
						<label for="sel1">Name *</label>
						<input type="text" name="name" id="name" class="form-control form-control-sm" required value="<?= $user->name ?>">
						<span class="error text-danger name_error"></span>
					</div>
					<div class="form-group" id="username_div">
						<label for="sel1">Username *</label>
						<input type="text" name="username" id="username" maxlength="15" class="form-control form-control-sm" value="<?= $user->username ?>" required readonly>
						<span class="error text-danger username_error"></span>
					</div>
					<div class="form-group" id="phone_div">
						<label for="sel1">Phone No. (10 digits) *</label>
						<input type="text" name="phoneno" id="phoneno" maxlength="10" class="form-control form-control-sm" value="<?= $user->mobile_no ?>" required>
						<span class="error text-danger phoneno_error"></span>
					</div>
					<div class="form-group psswrd_wrap" id="pwd_div">
						<label for="sel1">Password </label>
						<div class="input-group mb-3">
							<input type="password" name="password" id="password" class="form-control form-control-sm" autocomplete="new-password">
							<div class="input-group-prepend">
								<span class="input-group-text togglePassword">
									<i class="far fa-eye"></i>
								</span>
							</div>
						</div>
						<span class="error text-danger password_error"></span>
					</div>
					<div class="input-group form-group psswrd_wrap" id="con_pwd_div">
						<label for="sel1">Confirm Password </label>
						<div class="input-group mb-3">
							<input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-sm" autocomplete="new-password">
							<div class="input-group-prepend">
								<span class="input-group-text togglePassword">
									<i class="far fa-eye"></i>
								</span>
							</div>
						</div>
						<span class="error text-danger confirm_password_error"></span>
					</div>

					<div class="form-group" id="con_pwd_div">
						<input type="checkbox" name="reset_on_login" class="" checked>
						<label for="reset_password">Reset Password On Login? </label>
						<!-- <span class="error text-danger confirm_password_error"></span> -->
					</div>

					<div class="text-center">
						<button type="submit" class="btn btn-primary submit_btn">Update</button>
					</div>

				</form>
			</div>
		</div>

</body>

</html>

<!-- <script src="<?= base_url('assets/js/login.js') ?>"></script> -->
<script src="<?= base_url('assets/js/remark.js') ?>"></script>

<script>
	$('#userUpdateForm').on('submit', function(e) {
		e.preventDefault();
		$('.error').html('');
		let submitBtn = $('.submit_btn');
		let btnText = submitBtn.text();
		submitBtn.text('Please wait...').attr('disabled', true);

		let formData = new FormData(this);

		$.ajax({
			method: 'POST',
			url: $(this).attr('action'),
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					Swal.fire({
						icon: 'success',
						title: response.message
					}).then((rep) => {
						window.location.href = response.redirect_url;
					});
				} else {
					submitBtn.text(btnText).attr('disabled', false);
					Swal.fire({
						icon: 'error',
						title: response.message
					});
				}
			},
			error: function(errors) {
				submitBtn.text(btnText).attr('disabled', false);
				let errorData = errors.responseJSON.errors;
				$.each(errorData, function(index, error) {
					$(`.${index}_error`).text(error);
				});
			}
		});
	});

	$('.togglePassword').on('click', function(){
		const $this = $(this);
		const passwordWrap = $this.closest('.psswrd_wrap');

		if($this.hasClass('text-primary')){
			$this.removeClass('text-primary');
			$('input', passwordWrap).prop('type', 'password');
		}else{
			$this.addClass('text-primary');
			$('input', passwordWrap).prop('type', 'text');
		}
	});
</script>