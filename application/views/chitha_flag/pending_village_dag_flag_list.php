<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/sweetalert2.min.css">
<script src="<?php echo base_url(); ?>assets/js/sweetalert2.min.js"></script>
<style type="text/css">
    .checkBoxD{
        width: 20px;
        height: 20px;
    }

</style>
<div class="modal " id='myLargeModalLabelDagList' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabelDagList">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content login">
            <div class="row text-right">
                <span class="edit-enc-close-new px-4">&times;</span>
            </div>
            <div class="modal-body">
                    
                    <form id="approveMappingbyCO">
                        <h5 style="border-bottom:5px solid #ff681d">Mapping List for approval/revert by Circle Officer (Village Name - <?=$vill_name?>)</h5>
                        <input type="hidden" name="dist_code" id="dist_code" value="<?=$dist_code?>">
                        <input type="hidden" name="subdiv_code"  id="subdiv_code" value="<?=$subdiv_code?>">
                        <input type="hidden" name="cir_code" id="cir_code"  value="<?=$cir_code?>">
                        <input type="hidden" name="mouza_pargona_code" id="mouza_pargona_code" value="<?=$mouza_pargona_code?>">
                        <input type="hidden" name="lot_no" id="lot_no" value="<?=$lot_no?>">
                        <input type="hidden" name="vill_townprt_code" id="vill_townprt_code" value="<?=$vill_code?>">
                        <input type="hidden" name="approve_reject" value="<?=$approve_reject?>">
                     <div class="row px-5">
                        <b class="text-danger">1) Select Chechbox only for revert the dags, </b>
                        <b class="text-danger">2) For approval, no need to select Checkboxes,It will approve all the dags</b>
                    </div>
                        <div class="table-responsive">
                            <table class="datatable table table-stripped" id='datatable'>
                                <thead >
                                    <tr>
                                        <th>All <input  type="checkbox" class="checkBoxD " value="all" id="checkedAll" > </th>
                                        <th></th>
                                        <th>Area Flag</th>
                                        <th>Is eroded</th>
                                        <th>No land class</th>
                                        <th>Is Chaad dag</th>
                                        <th>Is Char Area</th>
                                        <th>Is Wetland</th>
                                        <th>Land Type<button type="button" class="search_button btn btn-sm btn-success">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        Search Dag</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                                
                            </table>
                            <?php if($status==true) {?>
                               
                                <div class="text-center">
                                    <button class="btn btn-primary" type="button" onclick="openModalForFlag();"><i class="fa fa-check-square-o"></i> Click here for Revert mapping</button>
                                    <button class="btn btn-success" type="submit"> Click here for Approve <i class="fa fa-check-square-o"></i> </button>
                                </div>
                            <?php } ?>

                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>

<div class="modal " id='myLargeModalLabelRevert' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabelRevert">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content login">
            <div class="row text-right">
                <span class="edit-enc-close px-4">&times;</span>
            </div>
            <div class="modal-body">
                <form id="ajaxMappingFormRevert">
                <h4 style="border-bottom: 5px solid #ff681d;">Chitha Dag Mapping with Area/Zonal Details For Revert</h4>
                <div class="row">
                    <div class="col-lg-4">
                       <b style="font-size:18px"><i class="fa fa-info-circle text-red"></i> Selected Dags</b>
                    </div>
                    <div class="col-lg-8">
                       <b style="font-size:18px"> <textarea class="form-control" readonly="" id="dags_view" style="height: 180px;"></textarea></b>
                       <input type="hidden" name="dag_list" id="dag_list">
                       <input type="hidden" name="vill_code" id="vill_code">
                       <input type="hidden" name="dist_code1" id="dist_code1" >
                        <input type="hidden" name="subdiv_code1"  id="subdiv_code1" >
                        <input type="hidden" name="cir_code1" id="cir_code1"  >
                        <input type="hidden" name="mouza_pargona_code1" id="mouza_pargona_code1" >
                        <input type="hidden" name="lot_no1" id="lot_no1" >
                    </div>
                </div>
                <div class="container mt-2" style="margin-bottom:25px">
                    <div class="col-md-6 text-center">
                        <label for="" class="text-danger"><i class="fa fa-hand-o-right text-green"></i> CO Remarks</label>
                   </div>
                   <div class="col-md-6">   
                        <textarea class="form-control" placeholder="Remarks" name="co_remarks" id="co_remarks" required></textarea>
                   </div>
               </div>
               
                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-danger"><i class="fa fa-save"></i> Revert mapping</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    var span_close = document.getElementsByClassName("edit-enc-close-new")[0];
    span_close.onclick = function() {
       $('#myLargeModalLabelDagList').modal('hide');
       // table.destroy();
    }

    // $(document).ready(function() {
    //     var table = $('#example1').DataTable();
    //     table.destroy();
    //     $('#example1').DataTable({
    //         "bLengthChange": false,
    //         "showNEntries" : false,
    //         "bSort" :   false,
    //         "bnew" :    false,
    //         "pageLength": 10
    //     });
    // });

    function showSuccessMessage(text) {
        Swal.fire({
            title: "Success !",
            text: text,
            icon: 'success',
            position: 'top',
            showConfirmButton: true,
            timer: 5000,
        }).then(function(){
                window.location.href =  baseurl + "ChithaFlag/ChithaFlagPendingList";
            });

        }

    function showErrorMessage(text) {
        Swal.fire({
            title: "Error!",
            text: text,
            icon: 'error',
            position: 'top',
            timer: 5000,
            showCancelButton: true

        });
    }



    function openModalForFlag(){
        if(selectedCheckBoxArray.length == 0){
            showErrorMessage("Please select one dag for proceed...");
            return false;
        }
        var btn = document.getElementById("myBtn");

        var span_close = document.getElementsByClassName("edit-enc-close")[0];

        $('#myLargeModalLabelRevert').modal('show');
        // console.log(selectedCheckBoxArray);
        $("#dags_view").html(selectedCheckBoxArray.toString());
        $("#dag_list").val(selectedCheckBoxArray);
        $('#vill_code').val($("#vill_townprt_code").val());
        $('#dist_code1').val($("#dist_code").val());
        $('#subdiv_code1').val($("#subdiv_code").val());
        $('#cir_code1').val($("#cir_code").val());
        $('#mouza_pargona_code1').val($("#mouza_pargona_code").val());
        $('#lot_no1').val($("#lot_no").val());
       
   
        span_close.onclick = function() {
           $('#myLargeModalLabelRevert').modal('hide');
           // table.destroy();
        }
   }



    $('#approveMappingbyCO').submit(function (e) {

        e.preventDefault();
        if(!confirm("Are you sure? "))
        {
            return false;
        }
        $.blockUI({
            message: $('#displayBox'),
            css: {
                border:'none',
                backgroundColor:'transparent'
            }
        });
        $.ajax({
            url: baseurl + "ChithaFlag/saveandApprove",
            type: 'POST',
            data: $("#approveMappingbyCO").serialize(),
            dataType: 'json',
            success: function (data) {
                $.unblockUI();
                if(data.responseType == 2){
                    showSuccessMessage(data.msg);
                }else{
                    showErrorMessage(data.msg); 
                }
            },error: function (error) {
                $.unblockUI();
                showErrorMessage('Something went wrong.');
            }
        });
    });



    load_data();
    var selectedCheckBoxArray = [];
    function load_data()
        {

            var base_url = "<?php echo base_url();?>";

            $('#datatable thead th:nth-of-type(2)').each(function () {
                var title = 'Dag No';
                $(this).html('<input type="text" class="input_search form-control form-control-sm" placeholder="Search '+title+'" data-column-index="0">');
            });

            $('#datatable thead th:nth-of-type(3)').each(function () {
      
                var title = 'Area Flag Search';
                $(this).html('<input type="text" class="input_search form-control form-control-sm" placeholder="Search '+title+'" data-column-index="1">');
            });

            
            
            var table = $('#datatable').DataTable({
                // "scrollX": true,
                'pageLength':10,
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "lengthMenu": [[5, 10, 20, 50, 100], [5, 10, 20, 50, 100]],
                'language': {
                            "processing": '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:rgb(75, 183, 245);"></i>'
                        },
                'ajax':{
                    url: base_url+'index.php/ChithaFlag/getAllDagsForMappingViewCOend',
                    type:'POST',
                    data: {
                        dist_code : $("#dist_code").val(),
                        subdiv_code : $("#subdiv_code").val(),
                        cir_code : $("#cir_code").val(),
                        mouza_pargona_code : $("#mouza_pargona_code").val(),
                        lot_no : $("#lot_no").val(),
                        village_code:$("#vill_townprt_code").val()
                    },
                    deferLoading: 57,
                },


                // order: [[2, 'asc']],
                // columnDefs: [{
                //     targets: "_all",
                //     orderable: false,
                //     "className": "dt-center", "targets":[ 0, 1, 2, 3, 4, 5, 6],
                //     }],

                order: [[2, 'asc']],
        
                columnDefs: [{
                  targets: 0,
                  // checkboxes: {
                  //   'selectRow': true
                  // },
                  data: "is_visible",
                  'render': function (data, type, row) {
                    // let text = row[0];
                    // const myArray = text.split("/");
                    // var arr = myArray[3];
                    return '<input type="checkbox" class="checkBoxD selectMark" value='+row[0]+' id='+row[0]+' name="selectMark[]">';
                  }
                }],
                    
            });



           
            $('#datatable tbody').on('click', 'input[type="checkbox"]', function(e) {
              var checkBoxId = $(this).val();
              var rowIndex = $.inArray(checkBoxId, selectedCheckBoxArray); 
              if(this.checked && rowIndex === -1) {
                selectedCheckBoxArray.push(checkBoxId);
              }
              else if (!this.checked && rowIndex !== -1) {
                selectedCheckBoxArray.splice(rowIndex, 1); // Remove it from the array.
              }
              // console.log(selectedCheckBoxArray);
              $("#dag_list").val(dag_list);
            });

            $("#checkedAll").click(function(){
                if(this.checked){
                    $('.selectMark').each(function(){
                        this.checked = true;
                        var id = $(this).val();
                        if($.inArray(id, selectedCheckBoxArray) !== -1){
                          // $('.selectMark').prop('checked', false);
                        }else{
                          selectedCheckBoxArray.push(id);
                          $('.selectMark').prop('checked', true);
                        }
                    })
                }else{
                    $('.selectMark').each(function(){
                        this.checked = false;
                        var id = $(this).val();
                        var rowIndex = $.inArray(id, selectedCheckBoxArray);
                        if(rowIndex == -1){

                        }else{
                          selectedCheckBoxArray.splice(rowIndex, 1);
                          $('.selectMark').prop('checked', false);
                        }                
                    })
                }
                console.log(selectedCheckBoxArray);
                $("#dag_list").val(dag_list);
            });


            $("#datatable").on('draw.dt', function() {
              for (var i = 0; i < selectedCheckBoxArray.length; i++) {
                checkboxId = selectedCheckBoxArray[i];
                // const myArray = checkboxId.split("/");
                // var arr = myArray[3];
                var arr = checkboxId;
                $('#' + arr).attr('checked', true);
              }
            });



            // on keypree search automatically
            // table.columns().every(function () {
            //     var table = this;
            //     $('input', this.header()).on('keyup change', function () {
            //         if (table.search() !== this.value) {
            //                 table.search(this.value).draw();
            //         }
            //     });
            // });

            // button search
            $('.search_button').on('click', function () {            
                $('table thead tr th .input_search').each(function(){ 
                    table.column($(this).data('columnIndex')).search(this.value);
                });
                table.draw();
            });
        }

        $('#ajaxMappingFormRevert').submit(function (e) {

            e.preventDefault();
            if(!confirm("Are you sure you want to submit this mapping?"))
            {
                return false;
            }
            $.blockUI({
                message: $('#displayBox'),
                css: {
                    border:'none',
                    backgroundColor:'transparent'
                }
            });
            $.ajax({
                url: baseurl + "ChithaFlag/RevertMappingWithChitha",
                type: 'POST',
                data: $("#ajaxMappingFormRevert").serialize(),
                dataType: 'json',
                success: function (data) {
                    $.unblockUI();
                    if(data.responseType == 2){
                        showSuccessMessage(data.msg);
                    }else{
                        showErrorMessage(data.msg); 
                    }
                },error: function (error) {
                    $.unblockUI();
                    showErrorMessage('Something went wrong.');
                }
            });
        });
</script>