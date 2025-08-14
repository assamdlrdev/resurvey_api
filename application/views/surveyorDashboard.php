<head>
  <?php $this->load->view('header'); ?>
  <style>
    .dashboard {
      height: 500px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
  </style>
</head>

<div class="container dashboard">
  <h3>Dashboard</h3>
</div>

<!-- <script src="<?= base_url('assets/js/login.js') ?>"></script> -->
<script src="<?= base_url('assets/js/remark.js') ?>"></script>

<script>
  $(document).on('change', '#user_code', function(e) {

  });

  $(document).on('click', '#psubmit', (e) => {
    // var baseurl = $('#base').val();
    // var user_code = $('#user_code').val();
    // var name = $('#name').val();
    // var username = $('#username').val();
    // var password = $('#password').val();

    // if (user_code == '' || name == '' || username == '' || password == '') {
    //   alert('All fields with (*) marked are mandatory');
    //   return false;
    // }

    // $.ajax({
    //   url: baseurl + 'index.php/Login/userCreateSubmit',
    //   method: 'POST',
    //   dataType: 'JSON',
    //   data: {user_code:user_code, name:name, username:username, password:password},
    //   success: function(response) {
    //     if (response.st == 1) {
    //       swal.fire("", response.msg, "success")
    //       .then((value) => {
    //           window.location = baseurl + "index.php/Login/usercreationIndex";
    //       });
    //     } else {
    //       swal.fire("", response.msg, "info");

    //     }
    //   },
    //   error: function(error) {
    //     console.log(error);
    //   }
    // });
  });
</script>