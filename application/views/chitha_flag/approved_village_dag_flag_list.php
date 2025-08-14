<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/sweetalert2.min.css">
<script src="<?php echo base_url(); ?>assets/js/sweetalert2.min.js"></script>
<div class="modal " id='myLargeModalLabelDagList' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabelDagList">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content login">
            <div class="row text-right">
                <span class="edit-enc-close-new px-4">&times;</span>
            </div>
            <div class="modal-body">
                    <form id="approveMappingbyCO">
                        <h5 style="border-bottom:5px solid #ff681d">Approved Mapping List by Circle Officer (Village Name - <?=$vill_name?>)</h5>
                        <input type="hidden" name="dist_code" id="dist_code" value="<?=$dist_code?>">
                        <input type="hidden" name="subdiv_code"  id="subdiv_code" value="<?=$subdiv_code?>">
                        <input type="hidden" name="cir_code" id="cir_code"  value="<?=$cir_code?>">
                        <input type="hidden" name="mouza_pargona_code" id="mouza_pargona_code" value="<?=$mouza_pargona_code?>">
                        <input type="hidden" name="lot_no" id="lot_no" value="<?=$lot_no?>">
                        <input type="hidden" name="vill_townprt_code" id="vill_townprt_code" value="<?=$vill_code?>">
                      
                        <div class="table-responsive">
                            <table class="datatable table table-stripped" id='datatable'>
                                <thead >
                                    <tr>
                                        <th>Dag no.</th>
                                        <th>Area Flag</th>
                                        <th>Is eroded</th>
                                        <th>No land class</th>
                                        <th>Is Chaad dag</th>
                                        <th>Is Char Area</th>
                                        <th>Is Wet Land</th>
                                        <th>Land Type<button type="button" class="search_button btn btn-sm btn-success">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        Search Dag</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                                
                            </table>
                          

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

    load_data();

    function load_data()
        {

            var base_url = "<?php echo base_url();?>";

            $('#datatable thead th:nth-of-type(1)').each(function () {
      
                var title = 'Dag No';
                $(this).html('<input type="text" class="input_search form-control form-control-sm" placeholder="Search '+title+'" data-column-index="0">');
            });

            $('#datatable thead th:nth-of-type(2)').each(function () {
      
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
                    url: base_url+'index.php/ChithaFlag/getAllDagsForMappingViewCOendApproved',
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
                
                    
            });



           
            // $('#datatable tbody').on('click', 'input[type="checkbox"]', function(e) {
            //   var checkBoxId = $(this).val();
            //   var rowIndex = $.inArray(checkBoxId, selectedCheckBoxArray); 
            //   if(this.checked && rowIndex === -1) {
            //     selectedCheckBoxArray.push(checkBoxId);
            //   }
            //   else if (!this.checked && rowIndex !== -1) {
            //     selectedCheckBoxArray.splice(rowIndex, 1); // Remove it from the array.
            //   }
            //   // console.log(selectedCheckBoxArray);
            // });

            // $("#checkedAll").click(function(){
            //     if(this.checked){
            //         $('.selectMark').each(function(){
            //             this.checked = true;
            //             var id = $(this).val();
            //             if($.inArray(id, selectedCheckBoxArray) !== -1){
            //               // $('.selectMark').prop('checked', false);
            //             }else{
            //               selectedCheckBoxArray.push(id);
            //               $('.selectMark').prop('checked', true);
            //             }
            //         })
            //     }else{
            //         $('.selectMark').each(function(){
            //             this.checked = false;
            //             var id = $(this).val();
            //             var rowIndex = $.inArray(id, selectedCheckBoxArray);
            //             if(rowIndex == -1){

            //             }else{
            //               selectedCheckBoxArray.splice(rowIndex, 1);
            //               $('.selectMark').prop('checked', false);
            //             }                
            //         })
            //     }
            //     console.log(selectedCheckBoxArray);
            // });


            // $("#datatable").on('draw.dt', function() {
            //   for (var i = 0; i < selectedCheckBoxArray.length; i++) {
            //     checkboxId = selectedCheckBoxArray[i];
            //     // const myArray = checkboxId.split("/");
            //     // var arr = myArray[3];
            //     var arr = checkboxId;
            //     $('#' + arr).attr('checked', true);
            //   }
            // });



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
</script>