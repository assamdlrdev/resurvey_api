<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->load->view('header'); ?>
    <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
    <style>
        .card {
            margin: 0 auto;
            /* Added */
            float: none;
            /* Added */
            margin-bottom: 10px;
            /* Added */
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card col-md-10" id="loc_save">
            <div class="card-body">
                <form class="form-horizontal unicode" name="form" method='post' action="<?php echo $base ?>index.php/get-jamabandi-details-report">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 class="mb-4" style="line-height: 0.2; color: #007bff; margin-top: 20px">
                                Select Patta Details For Jamabandi Report
                            </h4>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; border: 1px solid #007bff"></div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('patta_no'); ?></label>
                                <select class="form-control" id="pattaNumber" name="patta_no" required>
                                    <?php if ($patta_no) : ?>
                                        <option><?php echo $patta_no; ?></option>
                                    <?php else : ?>
                                        <option disabled selected>Select Patta No </option>
                                        <?php foreach ($pattas as $p) : ?>
                                            <option><?php echo $p ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('patta_type') ?></label>
                                <select class="form-control" id="getPattaType" name="patta_type">
                                    <option selected disabled>Select Patta Type</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="select" class="col-lg-3 control-label">Show Pattadar By</label>
                                <div class="form-check">
                                    <div class="col">
                                        <input class="form-check-input" type="radio" name="pattadar_order" id="pattadar_order2" value="serial_no" checked>
                                        <label class="form-check-label" for="pattadar_order2">
                                            Serial No
                                        </label>
                                    </div>
                                    <div class="col">
                                        <input class="form-check-input" type="radio" name="pattadar_order" id="pattadar_order1" value="pdar_id">
                                        <label class="form-check-label" for="pattadar_order1">
                                            Pattadar ID
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <br>
                        <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <br>
                            <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                            <a href="<?php echo base_url(); ?>index.php/set-location-for-jamabandi" class="btn btn-danger">
                                <i class="fa fa-arrow-left"></i>&nbsp; BACK TO MAIN MENU
                            </a>
                            <button type='submit' class="btn btn-primary">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>
    </div>

</body>

<script>
    $('#pattaNumber').on('change', function() {
        var patta_no = this.value;
        var baseurl = $("#base").val();
        var tokenName = $('meta[name="csrf-token-name"]').attr('content');
        var tokenHash = $('meta[name="csrf-token-hash"]').attr('content');
        var data = {};
        data[tokenName] = tokenHash;
        data.patta_no = patta_no;

        $.ajax({
            url: baseurl + 'index.php/get-patta-type-jamabandi-report',
            type: 'POST',
            data: data,
            error: function() {
                alert('Something is wrong');
            },
            success: function(data) {

                var pattaType = '<option selected disabled>Select</option>';

                $.each(JSON.parse(data), function(item, value) {
                    pattaType += '<option value="' + value.type_code + '">' + value.patta_type + '</option>'
                });

                $("#getPattaType").html(pattaType);
            }
        });

    });
</script>


</html>


<script src="<?= base_url('assets/js/location.js') ?>"></script>