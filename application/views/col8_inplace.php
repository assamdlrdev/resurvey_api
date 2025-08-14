
      <!DOCTYPE html>

      <html lang="en">
      <head>
      	<title>Dharitee Data Entry</title>
      	<meta charset="utf-8">
      	<meta name="viewport" content="width=device-width, initial-scale=1">
      	<?php $this->load->view('header'); ?>
    </head>  
	
      	<style>
      		
      		.row {
      margin-left:-5px;
      margin-right:-5px;

    }

      	</style>
		<script src="<?= base_url('assets/js/common.js') ?>"></script>
		<?php if (($this->session->userdata('dist_code')=='21') || ($this->session->userdata('dist_code')=='22') || ($this->session->userdata('dist_code')=='23')) { ?>
	       <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
		<?php } else { ?>
		   <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
		<?php } ?>
		
      	<body>
      		<!--div class="container bg-light p-0 border border-dark"-->
	<div class="container-fluid mt-3 mb-2 font-weight-bold"> 
<?php  if($locationname["dist_name"]!=NULL) echo $locationname['dist_name']['loc_name'].'/'.$locationname['subdiv_name']['loc_name'].'/'.$locationname['cir_name']['loc_name'].'/'.$locationname['mouza_name']['loc_name'].'/'.$locationname['lot']['loc_name'].'/'.$locationname['village']['loc_name']; ?>  
<?php echo $daghd?><?php echo $landhd?>
<div class="col-12 px-0 pb-3">
        <div class="bg-info text-white text-center py-2">
      					<h3>Enter In Place of/Alongwith Details(Column 8)</h3>
      				</div> 
      			</div>
      			<form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
				<div class="row">
      						
      						<div class="form-group row">
      							<div class="col-sm-12">
								<div class="form-check-inline">
      								<label class="form-check-label">
      									<input type="radio" class="form-check-input" value="i" name="inplaceof_alongwith">Inplace&nbsp;&nbsp;
      								</label>

      								<label class="form-check-label">
      									<input type="radio" class="form-check-input" value="a" name="inplaceof_alongwith">Alongwith
      								</label>
								 </div>
      							</div>
      						</div>
					</div>
					 
							<div class="form-group row">
      							<label for="inputPassword3" class="col-sm-2 col-form-label">ID:</label>
      							<div class="col-sm-6">
								  <input type="text" class="form-control" name="inplace_of_id" id="inplace_of_id" value="<?php echo $inplaceid?>" readonly >
      							</div>
								
								<label for="inputPassword3" class="col-sm-2 col-form-label">Co-Pattadar ?:</label>
      							<div class="col-sm-2">
								  <input type="checkbox" class="form-check-input" name="ch1" id="ch1" >
      							</div>
      						</div>
						
							<div class="form-group row">
      							<label for="inputPassword3" class="col-sm-2 col-form-label">Name:</label>
      							<div class="col-sm-6">
								  <input type="text" class="form-control" name="inplace_of_name" id="inplace_of_name" charset="utf-8"  onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
      							</div>
							</div>
						
							<div class="form-group row">
      							<label for="inputPassword3" class="col-sm-12 col-form-label">Land Area Left:</label>
							</div>
							<?php if (($this->session->userdata('dist_code')=='21') || ($this->session->userdata('dist_code')=='22') || ($this->session->userdata('dist_code')=='23')){?>
							<div class="form-group row">
      							<label for="inputPassword3" class="col-sm-2 col-form-label">Bigha:</label>
      							<div class="col-sm-6">
								  <input type="text" class="form-control" name="land_area_b" id="land_area_b" value="0" >
      							</div>
							</div>
								<div class="form-group row">
      							<label for="inputPassword3" class="col-sm-2 col-form-label">Katha:</label>
      							<div class="col-sm-6">
								  <input type="text" class="form-control" name="land_area_k" id="land_area_k" value="0">
      							</div>
							</div>
							<div class="form-group row">
      							<label for="inputPassword3" class="col-sm-2 col-form-label">Chatak:</label>
      							<div class="col-sm-6">
								  <input type="text" class="form-control" name="land_area_lc" id="land_area_lc" value="0" >
      							</div>
							</div>
							<div class="form-group row">
      							<label for="inputPassword3" class="col-sm-2 col-form-label">Ganda:</label>
      							<div class="col-sm-6">
								  <input type="text" class="form-control" name="land_area_g" id="land_area_g" value="0" >
      							</div>
							</div>
							<?php } else { ?>
								<div class="form-group row">
      							<label for="inputPassword3" class="col-sm-2 col-form-label">Bigha:</label>
      							<div class="col-sm-6">
								  <input type="text" class="form-control" name="land_area_b" id="land_area_b" value="0" >
      							</div>
							</div>
								<div class="form-group row">
      							<label for="inputPassword3" class="col-sm-2 col-form-label">Katha:</label>
      							<div class="col-sm-6">
								  <input type="text" class="form-control" name="land_area_k" id="land_area_k" value="0">
      							</div>
							</div>
							<div class="form-group row">
      							<label for="inputPassword3" class="col-sm-2 col-form-label">Lessa:</label>
      							<div class="col-sm-6">
								  <input type="text" class="form-control" name="land_area_lc" id="land_area_lc" value="0" >
      							</div>
							</div>
							<input type="hidden" class="form-control" name="land_area_g" id="land_area_g" value="0" >
						<?php } ?>
      				</div>
      				<div class="col-12 text-center pb-3">
                        <input type="hidden" name="base" id="base" value='<?php echo $base ?>'/><input type="hidden" name="dag_no" id="dag_no" value='<?php echo $dag_no ?>'/><input type="hidden" name="col8order_cron_no" id="col8order_cron_no" value='<?php echo $col8crno ?>'/>
						<input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
						<input type="button" class="btn btn-primary" id="iasubmit" name="iasubmit" value="Submit" onclick="inplacent();" ></input>
						<input type="button" class="btn btn-primary" id="onext" name="onext" value="Proceed to Tenant" onclick="tntentry();" ></input>
      				</div>
          </form>
		  </div>
      	</body>
      </html>
	  
	  <script src="<?= base_url('assets/js/location.js') ?>"></script>



