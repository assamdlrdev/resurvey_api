<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success">
        <?php echo ($this->session->flashdata('success')); ?>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger">
        <?php echo ($this->session->flashdata('error')); ?>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('info')) : ?>
    <div class="alert alert-info">
        <?php echo ($this->session->flashdata('info')); ?>
    </div>
<?php endif; ?>