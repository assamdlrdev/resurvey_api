<?php
$salt = rand(1000, 10000);
$this->session->set_userdata('salt', $salt);
$csrf = array(
  'name' => $this->security->get_csrf_token_name(),
  'hash' => $this->security->get_csrf_hash()
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dharitee Data Entry </title>
  <?php $this->load->view('header'); ?>

  <link rel="stylesheet" href="<?php echo base_url('assets/css/loginStyle.css') ?>" />
</head>
<style>
  .wrapper {
    width: 100%;
    height: calc(100vh - 100px);
    box-sizing: border-box;
    background: url(<?php echo base_url('assets/img/bg1.gif') ?>);
    background-repeat: no-repeat;
    background-size: 100% 100%;
    font-family: 'Roboto', sans-serif;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 60px;
  }

  .floating-btn:hover {
    background: linear-gradient(45deg, #2195f3ca, #b22dfaba), url(<?php echo base_url('assets/img/star.gif') ?>);
    transition: 0.4s linear;
  }
</style>

<body>
  <header>
    <div class="headerBox">
      <div class="headerLeft">
        <img src="<?php echo base_url('assets/img/flag.gif') ?>" alt="logo">
        <p>Government of Assam | Revenue & Disaster Management</p>
      </div>
      <div class="headerRight">
        <p id="chithaEntry">Chitha Entry</p>
      </div>
    </div>
  </header>
  <div class="wrapper">
    <div class="textImage">

      <img src="<?php echo base_url('assets/img/loginimg2.png') ?>" alt="" id="loginImg" />
      <p>
        <span class="first-letter">T</span>his portal is used for data entry
        of Chitha data under Revenue and Disaster Management department. It
        can be accessed by Admin users and operators engaged for data entry.
        The system is integrated with Dharitree and Basundhara.
      </p>
    </div>

    <div class="panel-lite">
      <div class="lock-icon">
        <img src="<?php echo base_url('assets/img/lock.png') ?>" alt="lock" />
      </div>
      <h4>Login</h4>
      <form class="login" method="post">
        <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
        <input type="hidden" id="hashedPassword" name="hashedpwd" value="" />
        <input type="hidden" id="salt" name="salt" value="<?php echo $salt ?>" />
        <input type="hidden" id="csrf" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
        <div class="form-group">
          <input class="form-control" id="username" name="username" required="required" />
          <label class="form-label">Username</label>
          <span><i class="bi bi-person"></i></span>
        </div>
        <div class="form-group">
          <input class="form-control" type="password" name="password" required="required" id="password" />
          <label class="form-label">Password</label>
          <span onclick="togglePasswordVisibility()"><i class="bi bi-eye"></i></span>
        </div>
        <div class="form-group">
          <input id="captcha" name="captcha" class="form-control" type="text" required="required" />
          <label class="form-label">Captcha</label>
          <div class="captcha-image">
            <?php echo $captcha['image']; ?>
          </div>
        </div>
        <button class="floating-btn" onclick="logincheck(this.form);">
          Login <i class="bi bi-box-arrow-in-right"></i>
        </button>
      </form>
    </div>
  </div>
  <footer>
    <div class="footerBox">
      <div class="footerLeft">
        <p>Copyright &copy; 2023 Government of Assam</p>
      </div>
      <div class="footerRight">
        <p>Design, Developed & Hosted by <a href="">&nbsp; National Informatics Centre, Assam</a></p>
        <div class="footerLogo">
          <a href=""><img src="<?php echo base_url('assets/img/nic-logo.png') ?>" alt="nic"></a>
        </div>
      </div>
    </div>
  </footer>
</body>

</html>

<script>
  function togglePasswordVisibility() {
    var passwordField = document.getElementById("password");
    if (passwordField.type === "password") {
      passwordField.type = "text";
      document.getElementsByTagName("span")[2].innerHTML =
        "<i class='bi bi-eye-slash'></i>";
    } else {
      passwordField.type = "password";
      document.getElementsByTagName("span")[2].innerHTML =
        "<i class='bi bi-eye'></i>";
    }
  }
</script>
<script src="<?= base_url('assets/js/login.js?v=1.5') ?>"></script>