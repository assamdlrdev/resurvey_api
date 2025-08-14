function logincheck(){
	var baseurl = $("#base").val();
	$.ajax({
		dataType: "json",
		url: baseurl + "index.php/Login/LoginSubmit",
		data: $('form').serialize(),
		type: "POST",
		success: function (data) {
                    if (data.st == 1) {
				swal.fire("", data.msg, "success")
					.then((value) => {
						window.location = baseurl + "index.php/Chithacontrol/index";
					});
			} else {
				swal.fire("", data.msg, "info");
				
			}
		}
	});
}

function remarkentry(){
	var baseurl = $("#base").val();
	$.ajax({
		dataType: "json",
		url: baseurl + "index.php/Remark/remarkFormsubmit",
		data: $('form').serialize(),
		type: "POST",
		success: function (data) {
                    if (data.st == 1) {
				swal.fire("", data.msg, "success")
					.then((value) => {
						window.location = baseurl + "index.php/Remark/remarkForm_in_favor_of";
					});
			} else {
				swal.fire("", data.msg, "info");
				
			}
		}
	});
}

function infavorentry() {
    var baseurl = $("#base").val();
      $.ajax({
        dataType: "json",
       url: baseurl + "index.php/Remark/remarkForm_in_favor_of_submit",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
					Swal.fire({ 
					icon: 'success',
					title: 'Success',
					text: data.msg,
					}).then((result) => {
					if (result.isConfirmed) 
					{
					
					Swal.fire({
					  //title: 'Are you sure?',
					  text: "Do you want to continue entering data for more Persons ?",
					  icon: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes',
					  cancelButtonText: 'No'
					}).then((result) => {
					  if (result.isConfirmed) {
						window.location = baseurl + "index.php/Remark/remarkForm_in_favor_of";
					  }
					  else
					  {
						window.location = baseurl + "index.php/Remark/remarkForm_along_with";
					  }
					});
					}
					});

            } else if (data.st == 0) {
				swal.fire("", data.msg, "info");
              

            }
        }
    });
};


function alongwithentry() {
    var baseurl = $("#base").val();
      $.ajax({
        dataType: "json",
       url: baseurl + "index.php/Remark/remarkForm_along_with_submit",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
					Swal.fire({ 
					icon: 'success',
					title: 'Success',
					text: data.msg,
					}).then((result) => {
					if (result.isConfirmed) 
					{
					
					Swal.fire({
					  //title: 'Are you sure?',
					  text: "Do you want to continue entering data for more Persons ?",
					  icon: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes',
					  cancelButtonText: 'No'
					}).then((result) => {
					  if (result.isConfirmed) {
						window.location = baseurl + "index.php/Remark/remarkForm_along_with";
					  }
					  else
					  {
						window.location = baseurl + "index.php/Remark/remarkForm_in_place_of";
					  }
					});
					}
					});

            } else if (data.st == 0) {
				swal.fire("", data.msg, "info");
              

            }
        }
    });
};

function inplaceofentry() {
    var baseurl = $("#base").val();
      $.ajax({
        dataType: "json",
       url: baseurl + "index.php/Remark/remarkForm_in_place_of_submit",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
					Swal.fire({ 
					icon: 'success',
					title: 'Success',
					text: data.msg,
					}).then((result) => {
					if (result.isConfirmed) 
					{
					
					Swal.fire({
					  //title: 'Are you sure?',
					  text: "Do you want to continue entering data for more Persons ?",
					  icon: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes',
					  cancelButtonText: 'No'
					}).then((result) => {
					  if (result.isConfirmed) {
						window.location = baseurl + "index.php/Remark/remarkForm_in_place_of";
					  }
					  else
					  {
						window.location = baseurl + "index.php/Remark/remarkForm_on_behalf_of";
					  }
					});
					}
					});

            } else if (data.st == 0) {
				swal.fire("", data.msg, "info");
              

            }
        }
    });
};


function onbehalfofentry() {
    var baseurl = $("#base").val();
      $.ajax({
        dataType: "json",
       url: baseurl + "index.php/Remark/remarkForm__on_behalf_of_submit",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
					Swal.fire({ 
					icon: 'success',
					title: 'Success',
					text: data.msg,
					}).then((result) => {
					if (result.isConfirmed) 
					{
					
					Swal.fire({
					  //title: 'Are you sure?',
					  text: "Do you want to continue entering data for more Persons ?",
					  icon: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes',
					  cancelButtonText: 'No'
					}).then((result) => {
					  if (result.isConfirmed) {
						window.location = baseurl + "index.php/Remark/remarkForm_on_behalf_of";
					  }
					  else
					  {
						window.location = baseurl + "index.php/Remark/remarkcomplete";
					  }
					});
					}
					});

            } else if (data.st == 0) {
				swal.fire("", data.msg, "info");
              

            }
        }
    });
};