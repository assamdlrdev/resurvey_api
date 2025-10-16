<!DOCTYPE html>
<html lang="en">

<head>
  <title>Dharitee Data Entry</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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


        <?php echo form_open('Chithacontrol/indexSubmit'); ?>
        <div class="card-title  text-center font-weight-bold">Select Dag Location</div><br><br>
        <div class="form-group">
          <label for="sel1">District:</label>
          <select name="dist_code" class="form-control" id="d">
            <option selected value="">Select District</option>

            <?php foreach ($districts as $value) { ?>
              <option value="<?= $value['dist_code'] ?>"><?= $value['loc_name'] ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="sel1">Sub-Div:</label>
          <select name="subdiv_code" class="form-control" id="sd">
            <option value="">Select Sub Division </option>
          </select>
        </div>
        <div class="form-group">
          <label for="sel1">Circle:</label>
          <select name="cir_code" class="form-control" id="c">
            <option value="">Select Circle </option>
          </select>
        </div>
        <div class="form-group">
          <label for="sel1">Mouza:</label>
          <select name="mouza_pargona_code" class="form-control" id="m">
            <option value="">Select Mouza </option>
          </select>
        </div>
        <div class="form-group">
          <label for="sel1">Lot:</label>
          <select name="lot_no" class="form-control" id="l">
            <option value="">Select Lot </option>
          </select>
        </div>
        <div class="form-group">
          <label for="sel1">Village:</label>
          <select name="vill_townprt_code" class="form-control" id="v">
            <option value="">Select Village </option>
          </select>
        </div>
        <div class="text-center"><button class="btn btn-primary" id="loc_save_btn" name="submit">Proceed</button></div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</body>

</html>


<script src="<?= base_url('assets/js/location.js') ?>"></script>