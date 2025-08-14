<div class="container my-3">
    <div class="row">
        <div class="col-lg-12 ">
            <h1 class="text-center">Welcome To Chitha Entry</h1>
        </div>
    </div>
    <?php if(in_array($this->session->userdata('dcode'), BARAK_VALLEY)): ?>
        <div class="row mt-5">
            <?php if($this->session->userdata('user_desig_code') == 'LM'): ?>
                <div class="col-lg-12 col-md-12 d-flex justify-content-center">
                    <a class="btn btn-primary" href="<?= CHITHAENTRY_BARAK_URL . "index.php/Login/LMdashboard" ?>">Redirect to Chithaentry Barak Application</a>
                </div>
            <?php elseif($this->session->userdata('user_desig_code') == 'CO'): ?>
                <div class="col-lg-12 col-md-12 d-flex justify-content-center">
                    <a class="btn btn-primary" href="<?= CHITHAENTRY_BARAK_URL . "index.php/Login/dashboard" ?>">Redirect to Chithaentry Barak Application</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>