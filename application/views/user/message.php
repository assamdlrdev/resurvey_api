<?php if($this->session->flashdata('success')) { ?>
    <div class="alert alert-success" style="box-shadow:  0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <h5><i class="fa fa-check"></i> <?php echo $this->session->flashdata('success') ?></h5>
    </div>
<?php } ?>


<?php if($this->session->flashdata('error')) { ?>
    <div class="alert alert-danger alert-dismissable" style="box-shadow:  0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <b><?php echo $this->session->flashdata('error') ?></b>
        <br>
        <b><?php echo $this->session->flashdata('error_code') ?></b>
    </div>

<?php } ?>
