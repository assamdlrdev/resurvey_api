<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
<style>
    .circle {
        width: 20px;
        height: 20px;
        border-radius: 50%;
    }

    .legend_box{
        position: absolute;
        top: 69px;
        right: 52px;
        border: 1px solid;
        padding: 15px;
    }
</style>
<div class="col-lg-12 col-md-12 p-2">
    <div class="row">
        <div class="col-xl-8 mx-auto text-center">
            <div class="section-title">
                <h4>NC VILLAGE PROCEEDING STATUS</h4>
            </div>
        </div>
        <div class="col-xl-12 mx-auto text-right">            
            <div class="legend_box d-flex flex-row-reverse">
                <div class="d-flex align-items-right">
                    <div class="circle bg-success"></div>
                    <strong class="ms-2">Forwarded</strong>
                </div>
                <div class="d-flex align-items-right">
                    <div class="circle bg-secondary"></div>
                    <strong class="ms-2 me-2">Not applicable</strong>
                </div>
                <div class="d-flex align-items-right">
                    <div class="circle bg-warning"></div>
                    <strong class="ms-2 me-2">Pending</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="timeline">
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class=" text-light <?= $dlr_status == 0 ? 'bg-secondary' : ($dlr_status == 1 ? 'bg-success' : 'bg-warning') ?>">Director of Land Records & Survey</h3>
                    <p>PROPOSAL FORWARDED TO SENIOR MOST SECRETARY.</p>
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($dlr_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time><?= $dlr_proposal_date; ?></time>
                </div>
            </div>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class="text-light <?= $ps_1_status == 0 ? 'bg-secondary' : ($ps_1_status == 1 ? 'bg-success' : 'bg-warning') ?>" >SENIOR MOST SECRETARY </h3>
                    <p>PROPOSAL FORWARDED TO SECRETARY.</p>
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($ps_1_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time><?= $ps_1_action_date; ?></time>
                </div>
            </div>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class="text-light <?= $secretary_1_status == 0 ? 'bg-secondary' : ($secretary_1_status == 1 ? 'bg-success' : 'bg-warning') ?>">SECRETARY </h3>
                    <p>PROPOSAL FORWARDED TO JOINT SECRETARY.</p>
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($secretary_1_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time><?= $secretary_1_action_date; ?></time>
                </div>
            </div>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class="text-light <?= $js_1_status == 0 ? 'bg-secondary' : ($js_1_status == 1 ? 'bg-success' : 'bg-warning') ?>">JOINT SECRETARY </h3>
                    <p>PROPOSAL FORWARDED TO SECTION OFFICER .</p>
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($js_1_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time></time>
                </div>
            </div>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class="text-light <?= $so_1_status == 0 ? 'bg-secondary' : ($so_1_status == 1 ? 'bg-success' : 'bg-warning') ?>">SECTION OFFICER </h3>
                    <p>PROPOSAL FORWARDED TO ASSISTANT SECTION OFFICER .</p>
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($so_1_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time><?= $so_1_action_date; ?></time>
                </div>
            </div>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class="text-light <?= $aso_status == 0 ? 'bg-secondary' : ($aso_status == 1 ? 'bg-success' : 'bg-warning') ?>">ASSISTANT SECTION OFFICER </h3>
                    <p>DRAFT NOTIFICATION GENERATED AND FORWARDED TO SECTION OFFICER .</p>
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($aso_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time><?= $aso_1_action_date; ?></time>
                </div>
            </div>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class="text-light <?= $so_2_status == 0 ? 'bg-secondary' : ($so_2_status == 1 ? 'bg-success' : 'bg-warning') ?>">SECTION OFFICER </h3>
                    <p>DRAFT NOTIFICATION FORWARDED TO JOINT SECRETARY .</p>
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($so_2_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time><?= $so_2_action_date; ?></time>
                </div>
            </div>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class="text-light <?= $js_2_status == 0 ? 'bg-secondary' : ($js_2_status == 1 ? 'bg-success' : 'bg-warning') ?>">JOINT SECRETARY </h3>
                    <p>DRAFT NOTIFICATION FORWARDED TO SECRETARY .</p>
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($js_2_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time ><?= $js_2_action_date; ?></time>
                </div>
            </div>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class="text-light <?= $secretary_2_status == 0 ? 'bg-secondary' : ($secretary_2_status == 1 ? 'bg-success' : 'bg-warning') ?>">SECRETARY </h3>
                    <p>DRAFT NOTIFICATION FORWARDED TO SENIOR MOST SECRETARY.</p>
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($secretary_2_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time><?= $sec_2_action_date; ?></time>
                </div>
            </div>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class="text-light <?= $ps_2_status == 0 ? 'bg-secondary' : ($ps_2_status == 1 ? 'bg-success' : 'bg-warning') ?>">SENIOR MOST SECRETARY</h3>
                    <p>DRAFT NOTIFICATION FORWARDED TO HONOURABLE MINISTER.</p>
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($ps_2_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time><?= $ps_action_date; ?></time>
                </div>
            </div>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class="text-light <?= $minister_status == 0 ? 'bg-secondary' : ($minister_status == 1 ? 'bg-success' : 'bg-warning') ?>">HONOURABLE MINISTER</h3>
                    <p>DRAFT NOTIFICATION APPROVED AND FORWARDED TO SENIOR MOST SECRETARY.</p>
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($minister_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time><?= $minister_action_date; ?></time>
                </div>
            </div>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class="text-light <?= $ps_3_status == 0 ? 'bg-secondary' : ($ps_3_status == 1 ? 'bg-success' : 'bg-warning') ?>">SENIOR MOST SECRETARY</h3>
                    <p>DRAFT NOTIFICATION  APPROVED, E-SIGNED AND FORWARDED TO JOINT SECRETARY.</p>
                    <!-- <a target="_blank" class="btn btn-success text-white">View Notification</a> -->
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($ps_3_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time><?= $ps_2_action_date; ?></time>
                </div>
            </div>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                    <h3 class="text-light <?= $js_3_status == 0 ? 'bg-secondary' : ($js_3_status == 1 ? 'bg-success' : 'bg-warning') ?>">JOINT SECRETARY</h3>
                    <p>NOTIFICATION E-SIGN COMPLETED.</p>
                    <!-- <a class="btn btn-success text-white">View Notification</a> -->
                </div>
                <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-md-center">
                    <?php if($js_3_status == 1): ?>
                        <img src="<?= base_url('assets/check.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php else: ?>
                        <img src="<?= base_url('assets/pending.svg') ?>" width="40px" style="color: red;" class="img-fluid" alt="img">
                    <?php endif; ?>
                </div>
                <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                    <time><?= $js_3_action_date; ?></time>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --white: #ffffff;
        --black: #000000;
        --blue: #0288d1;
        --gray: #ebebeb;
        --box-shadow1: 0px 0px 18px 2px rgba(10, 55, 90, 0.15);
    }

    body {
        font-family: 'Roboto', sans-serif;
        font-weight: lighter;
        color: #637280;
        -moz-user-select: none;
        -webkit-user-select: none;
        user-select: none;
    }

    :focus {
        outline: 0px solid transparent !important;
    }

    .timeline {
        padding: 50px 0;
        position: relative;
    }

    .timeline-nodes {
        padding-bottom: 25px;
        position: relative;
    }

    .timeline-nodes:nth-child(even) {
        flex-direction: row-reverse;
    }

    .timeline h3,
    .timeline p {
        padding: 5px 15px;
    }

    .timeline h3 {
        font-weight: lighter;
        /* background: var(--blue); */
    }

    .timeline p,
    .timeline time {
        /* color: var(--blue) */
    }

    .timeline::before {
        content: "";
        display: block;
        position: absolute;
        top: 0;
        left: 50%;
        width: 0;
        border-left: 2px dashed gray;
        height: 100%;
        z-index: 1;
        transform: translateX(-50%);
    }

    .timeline-content {
        border: 1px solid gray;
        position: relative;
        border-radius: 0 0 10px 10px;
        box-shadow: 0px 3px 25px 0px rgba(10, 55, 90, 0.2)
    }

    .timeline-nodes:nth-child(odd) h3,
    .timeline-nodes:nth-child(odd) p {
        text-align: right;
    }

    .timeline-nodes:nth-child(odd) .timeline-date {
        text-align: left;
    }

    .timeline-nodes:nth-child(even) .timeline-date {
        text-align: right;
    }

    .timeline-nodes:nth-child(odd) .timeline-content::after {
        content: "";
        position: absolute;
        top: 5%;
        left: 100%;
        width: 0;
        border-left: 10px solid var(--blue);
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
    }

    .timeline-nodes:nth-child(even) .timeline-content::after {
        content: "";
        position: absolute;
        top: 5%;
        right: 100%;
        width: 0;
        border-right: 10px solid var(--blue);
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
    }

    .timeline-image {
        position: relative;
        z-index: 100;
    }

    .timeline-image::before {
        content: "";
        width: 80px;
        height: 80px;
        border: 2px dashed gray;
        border-radius: 50%;
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        z-index: 1;


    }

    .timeline-image img {
        position: relative;
        z-index: 100;
    }

    /*small device style*/

    @media (max-width: 767px) {

        .timeline-nodes:nth-child(odd) h3,
        .timeline-nodes:nth-child(odd) p {
            text-align: left
        }

        .timeline-nodes:nth-child(even) {
            flex-direction: row;
        }

        .timeline::before {
            content: "";
            display: block;
            position: absolute;
            top: 0;
            left: 4%;
            width: 0;
            border-left: 2px dashed gray;
            height: 100%;
            z-index: 1;
            transform: translateX(-50%);
        }

        .timeline h3 {
            font-size: 1.7rem;
        }

        .timeline p {
            font-size: 14px;
        }

        .timeline-image {
            position: absolute;
            left: 0%;
            top: 60px;
            /*transform: translateX(-50%;);*/
        }

        .timeline-nodes:nth-child(odd) .timeline-content::after {
            content: "";
            position: absolute;
            top: 5%;
            left: auto;
            right: 100%;
            width: 0;
            border-left: 0;
            border-right: 10px solid var(--blue);
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
        }

        .timeline-nodes:nth-child(even) .timeline-content::after {
            content: "";
            position: absolute;
            top: 5%;
            right: 100%;
            width: 0;
            border-right: 10px solid var(--blue);
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
        }

        .timeline-nodes:nth-child(even) .timeline-date {
            text-align: left;
        }

        .timeline-image::before {
            width: 65px;
            height: 65px;
        }
    }

    /*extra small device style */
    @media (max-width: 575px) {
        .timeline::before {
            content: "";
            display: block;
            position: absolute;
            top: 0;
            left: 3%;
        }

        .timeline-image {
            position: absolute;
            left: -5%;
        }

        .timeline-image::before {
            width: 60px;
            height: 60px;
        }
    }

    .section-title h4 {
        text-transform: capitalize;
        font-size: 40px;
        position: relative;
        padding-bottom: 20px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .section-title h4:before {
        position: absolute;
        content: "";
        width: 60px;
        height: 2px;
        background-color: #ff3636;
        bottom: 0;
        left: 50%;
        margin-left: -30px;
    }

    .section-title h4:after {
        position: absolute;
        background-color: #ff3636;
        content: "";
        width: 10px;
        height: 10px;
        bottom: -4px;
        left: 50%;
        margin-left: -5px;
        border-radius: 50%;
    }
</style>