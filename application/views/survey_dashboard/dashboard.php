<head>
    <?php $this->load->view('header'); ?>
    <style>
        .dashboard {
            height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        body {
            background: #eee;
        }

        .card-box {
            position: relative;
            color: #fff!important;
            padding: 20px 10px 40px;
            margin: 20px 0px;
        }

        .card-box:hover {
            text-decoration: none;
            color: #f1f1f1;
        }

        .card-box:hover .icon i {
            font-size: 100px;
            transition: 1s;
            -webkit-transition: 1s;
        }

        .card-box .inner {
            padding: 5px 10px 0 10px;
        }

        .card-box h3 {
            font-size: 27px;
            font-weight: bold;
            margin: 0 0 8px 0;
            white-space: nowrap;
            padding: 0;
            text-align: left;
        }

        .card-box p {
            font-size: 15px;
        }

        .card-box .icon {
            position: absolute;
            top: auto;
            bottom: 5px;
            right: 5px;
            z-index: 0;
            font-size: 72px;
            color: rgba(0, 0, 0, 0.15);
        }

        .card-box .card-box-footer {
            position: absolute;
            left: 0px;
            bottom: 0px;
            text-align: center;
            padding: 3px 0;
            color: rgba(255, 255, 255, 0.8);
            background: rgba(0, 0, 0, 0.1);
            width: 100%;
            text-decoration: none;
        }

        .card-box:hover .card-box-footer {
            background: rgba(0, 0, 0, 0.3);
        }

        .bg-blue {
            background-color: #00c0ef !important;
        }

        .bg-green {
            background-color: #00a65a !important;
        }

        .bg-orange {
            background-color: #f39c12 !important;
        }

        .bg-red {
            background-color: #d9534f !important;
        }
    </style>
</head>

<div class="container">
    <?php if($show_filter_section): ?>
    <div class="card p-2">
        <form action="" id="searchForm">
            <div class="row">
                <div class="col-md-3">
                    <label for="dist_code">District</label>
                    <select class="form-control" name="dist_code" id="dist_code">
                        <option value="">Select District</option>
                        <?php foreach($districts as $district): ?>
                            <option value="<?= $district['dist_code'] ?>"><?= $district['loc_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="subdiv_code">Sub-division</label>
                    <select class="form-control" name="subdiv_code" id="subdiv_code">
                        <option value="">Select Sub-division</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="cir_code">Circle</label>
                    <select class="form-control" name="cir_code" id="cir_code">
                        <option value="">Select Circle</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="mouza_pargona_code">Mouza</label>
                    <select class="form-control" name="mouza_pargona_code" id="mouza_pargona_code">
                        <option value="">Select Mouza</option>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3">
                    <label for="lot_no">Lot</label>
                    <select class="form-control" name="lot_no" id="lot_no">
                        <option value="">Select Lot</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="vill_townprt_code">Village</label>
                    <select class="form-control" name="vill_townprt_code" id="vill_townprt_code">
                        <option value="">Select Village</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <!-- <label for="user">User</label>
                    <select class="form-control" name="user" id="user">
                        <option value="">Select User</option>
                        <?php foreach($users as $user): ?>
                            <option value="<?= $user->username ?>"><?= $user->name . ' (' . $user->role_name . ')'; ?></option>
                        <?php endforeach; ?>
                    </select> -->
                </div>
                <div class="col-md-3">
                    <!-- <button class="btn btn-info">Search</button> -->
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>
    <div class="counters_wrap">
        <?php include(APPPATH . 'views/survey_dashboard/dashboard_counter.php') ?>
    </div>
    <!-- <div class="row">
        <div class="col-lg-3 col-sm-6">
            <a href="#">uiuxstream</a>
        </div>
    </div> -->
</div>

<!-- <script src="<?= base_url('assets/js/login.js') ?>"></script> -->
<script src="<?= base_url('assets/js/remark.js') ?>"></script>

<script>
    let baseUrl = window.base_url;
    $(document).on('change', '#dist_code', function(e) {
        $('#searchForm').submit();
        let dist_code = $(this).val();
        $('#subdiv_code, #cir_code, #mouza_pargona_code, #lot_no, #vill_townprt_code').val('');
        $.ajax({
            type: 'POST',
            url: baseUrl + 'index.php/survey/get-subdivs',
            data: {
                'dist_code': dist_code
            },
            dataType: 'json',
            success: function(response){
                if(response.success){
                    let html = `<option value="">Select Sub-division</option>`;
                    $.each(response.data, function(index, val){
                        html += `<option value="${val.subdiv_code}">${val.loc_name} (${val.locname_eng})</option>`;
                    });
                    $('#subdiv_code').html(html);
                }
            },
            error: function(error){
                //
            }
        });
    });
    
    $(document).on('change', '#subdiv_code', function(e) {
        $('#searchForm').submit();
        let dist_code = $('#dist_code').val();
        let subdiv_code = $('#subdiv_code').val();
        $('#cir_code, #mouza_pargona_code, #lot_no, #vill_townprt_code').val('');
        $.ajax({
            type: 'POST',
            url: baseUrl + 'index.php/survey/get-circles',
            data: {
                'dist_code': dist_code,
                'subdiv_code': subdiv_code
            },
            dataType: 'json',
            success: function(response){
                if(response.success){
                    let html = `<option value="">Select Circle</option>`;
                    $.each(response.data, function(index, val){
                        html += `<option value="${val.cir_code}">${val.loc_name} (${val.locname_eng})</option>`;
                    });
                    $('#cir_code').html(html);
                }
            },
            error: function(error){
                //
            }
        });
    });
    
    $(document).on('change', '#cir_code', function(e) {
        $('#searchForm').submit();
        let dist_code = $('#dist_code').val();
        let subdiv_code = $('#subdiv_code').val();
        let cir_code = $('#cir_code').val();
        $('#mouza_pargona_code, #lot_no, #vill_townprt_code').val('');
        $.ajax({
            type: 'POST',
            url: baseUrl + 'index.php/survey/get-mouzas',
            data: {
                'dist_code': dist_code,
                'subdiv_code': subdiv_code,
                'cir_code': cir_code
            },
            dataType: 'json',
            success: function(response){
                if(response.success){
                    let html = `<option value="">Select Mouza</option>`;
                    $.each(response.data, function(index, val){
                        html += `<option value="${val.mouza_pargona_code}">${val.loc_name} (${val.locname_eng})</option>`;
                    });
                    $('#mouza_pargona_code').html(html);
                }
            },
            error: function(error){
                //
            }
        });
    });
    
    $(document).on('change', '#mouza_pargona_code', function(e) {
        $('#searchForm').submit();
        let dist_code = $('#dist_code').val();
        let subdiv_code = $('#subdiv_code').val();
        let cir_code = $('#cir_code').val();
        let mouza_pargona_code = $('#mouza_pargona_code').val();
        $('#lot_no, #vill_townprt_code').val('');
        $.ajax({
            type: 'POST',
            url: baseUrl + 'index.php/survey/get-lots',
            data: {
                'dist_code': dist_code,
                'subdiv_code': subdiv_code,
                'cir_code': cir_code,
                'mouza_pargona_code': mouza_pargona_code
            },
            dataType: 'json',
            success: function(response){
                if(response.success){
                    let html = `<option value="">Select Lot</option>`;
                    $.each(response.data, function(index, val){
                        html += `<option value="${val.lot_no}">${val.loc_name} (${val.locname_eng})</option>`;
                    });
                    $('#lot_no').html(html);
                }
            },
            error: function(error){
                //
            }
        });
    });
    
    $(document).on('change', '#lot_no', function(e) {
        $('#searchForm').submit();
        let dist_code = $('#dist_code').val();
        let subdiv_code = $('#subdiv_code').val();
        let cir_code = $('#cir_code').val();
        let mouza_pargona_code = $('#mouza_pargona_code').val();
        let lot_no = $('#lot_no').val();
        $('#vill_townprt_code').val('');
        $.ajax({
            type: 'POST',
            url: baseUrl + 'index.php/survey/get-villages',
            data: {
                'dist_code': dist_code,
                'subdiv_code': subdiv_code,
                'cir_code': cir_code,
                'mouza_pargona_code': mouza_pargona_code,
                'lot_no': lot_no,
            },
            dataType: 'json',
            success: function(response){
                if(response.success){
                    let html = `<option value="">Select Village</option>`;
                    $.each(response.data, function(index, val){
                        html += `<option value="${val.vill_townprt_code}">${val.loc_name} (${val.locname_eng})</option>`;
                    });
                    $('#vill_townprt_code').html(html);
                }
            },
            error: function(error){
                //
            }
        });
    });
    
    $(document).on('change', '#vill_townprt_code', function(e) {
        $('#searchForm').submit();
    });

    $('#searchForm').on('submit', function(e){
        e.preventDefault();
        $('.counters_wrap').html(`<h3 class="dashboard">Please wait...</h3>`);
        let actionUrl = $(this).attr('action');
        let formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: actionUrl,
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response){
                if(response.success){
                    $('.counters_wrap').html(response.html);
                }
            },
            error: function(error){
                //
            }
        });
    });
</script>