<link rel="stylesheet" href="<?php echo base_url(); ?>application/css/sweetalert2.min.css">
<script src="<?php echo base_url(); ?>application/views/js/sweetalert2/sweetalert2.all.min.js"></script>
<style>
  /*  select {
        font-family: verdana;
        font-size: 8pt;
        width: 150px;
        height: 30vh;
    }*/
    select.form-control[multiple], select.form-control[size]{
        height: 250px !important;
        width: 450px !important;
    }
    .selectLabel1{
        background-color: #ff681d;
        padding: 8px;
        color: #fff;
    }
    .selectLabel2{
        background-color: #209924;
        padding: 8px;
        color: #fff;
    }
</style>

<style>
    #button{
        display:block;
        margin:20px auto;
        padding:10px 30px;
        background-color:#eee;
        border:solid #ccc 1px;
        cursor: pointer;
    }
    #overlay{
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height:100%;
        display: none;
        background: rgba(0,0,0,0.6);
    }
    .cv-spinner {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px #ddd solid;
        border-top: 4px #2e93e6 solid;
        border-radius: 50%;
        animation: sp-anime 0.8s infinite linear;
    }
    @keyframes sp-anime {
        100% {
            transform: rotate(360deg);
        }
    }
    .is-hide{
        display:none;
    }
    @media (min-width: 576px){
        .modal-dialog {
            max-width: 677px !important;
            margin: 1.75rem auto;
        }
    }
    
</style>
<style type="text/css">
    .checkBoxD{
        width: 20px;
        height: 20px;
    }
</style>
<div class="row login form-top">
    <div class="col-lg-12 ">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading bg-success text-white  my-2">
                    <h3 class="panel-title text-center font-weight-bold">Dag Flagging</h3>
                </div>
                <div class="panel-body">
                    <form method="POST" action="" id="PartialMappingForm">
                        <input type="hidden" name="selectedDags" id="selectedDags">
                        <input type="hidden" class="districtselect" name="dist_code" id="dist_code" value="<?php echo $datas['dist_code']; ?>">
                        <input type="hidden" class="subdivselect" name="subdiv_code" id="subdiv_code" value="<?php echo $datas['subdiv_code']; ?>">
                        <input type="hidden" class="circleselect" name="cir_code" id="cir_code" value="<?php echo $datas['cir_code']; ?>">
                        <input type="hidden" class="mouza_pargona_code" name="mouza_pargona_code" id="mouza_pargona_code" value="<?php echo $datas['mouza_pargona_code']; ?>">
                        <input type="hidden" class="lot_no" name="lot_no" id="lot_no" value="<?php echo $datas['lot_no']; ?>">
                        <input type="hidden" class="vill_townprt_code" name="vill_townprt_code" id="vill_townprt_code" value="<?php echo $datas['vill_code']; ?>">
                    <div class="" role="alert" style="text-align:center">
                        <h4><?php echo $this->lang->line('district');?> : <kbd><?php echo $datas['dist_name']; ?></kbd> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('subdivision');?> : <kbd><?php echo $datas['sub_div_name']; ?></kbd> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('circle');?> : <kbd><?php echo $datas['cir_name']; ?></kbd> <?php echo $this->lang->line('vill_town');?> : <kbd><?php echo $datas['vill_name']; ?></kbd> </h4>
                    </div>
                    <table class="datatable table table-stripped" id='datatable'>
                        <thead style="font-size:7px">
                            <tr>
                                <th>Dag no.</th>
                                <th>Area Flag</th>
                                <th>Is eroded</th>
                                <th>No land class</th>
                                <th>Is Chaad dag</th>                              
                                <th>Land Class<button type="button" class="search_button btn btn-sm btn-success">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        Search Dag
                                    </button></th>
                                    <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>


                
                

          
                </form>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    /* .dataTables_filter, .dataTables_info { display: none; } */

    .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
        visibility: hidden;
        }
 </style>
<script src="<?php echo base_url(); ?>application/views/js/blockUI.js"></script>
<script type="text/javascript">
    function showSuccessMessage(text) {
        Swal.fire({
            title: "Success !",
            text: text,
            icon: 'success',
            position: 'top',
            showConfirmButton: true,
            timer: 5000,
        }).then(function(){
                window.location.href =  baseurl + "ChithaFlag/locationDetails";
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



    $('#PartialMappingForm').submit(function (e) {

        e.preventDefault();
        if(!confirm("Are you sure you want to forward to CO?"))
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
            url: baseurl + "ChithaFlag/forwardToCO",
            type: 'POST',
            data: $("#PartialMappingForm").serialize(),
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

    
    $(document).ready(function() {
        $('#mapping_datatable').DataTable({
        "bLengthChange": false,
        "showNEntries" : false,
        "bSort" :   false,
        "bnew" :    false,
        "pageLength": 10
    });
  
});
       
</script>
<script>
     var selectedCheckBoxArray = [];
    $(document).ready(function ()
    {
        // $('#rural, #category, #occupation').change(function(){
        //     var rural = $('#rural').val();
        //     var category = $('#category').val();
        //     var occupation = $('#occupation').val();
        //     $('#datatable').DataTable().destroy();

        //     load_data(category,rural,occupation);
    
        // });

        load_data();

        function load_data()
        {

            var base_url = "<?php echo base_url();?>";

            $('#datatable thead th:nth-of-type(1)').each(function () {
                // var title = $(this).text();
                // $(this).html(title+' <input type="text" class="input_search form-control form-control-sm" placeholder="Search ' + title + '" data-column-index="0" />');
                var title = 'Dag No';
                $(this).html('<input type="text" class="input_search form-control form-control-sm" placeholder="Search '+title+'" data-column-index="0">');
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
                    url: base_url+'index.php/ChithaFlag/getAllDagsForMappingView',
                    type:'POST',
                    data: {
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

                // order: [[2, 'asc']],
        
                // columnDefs: [{
                //   targets: 0,
                //   // checkboxes: {
                //   //   'selectRow': true
                //   // },
                //   data: "is_visible",
                //   // 'render': function (data, type, row) {
                //   //   // let text = row[0];
                //   //   // const myArray = text.split("/");
                //   //   // var arr = myArray[3];
                //   //   return '<input type="checkbox" class="checkBoxD selectMark" value='+row[0]+' id='+row[0]+' name="selectMark[]">';
                //   // }
                // }],
                    
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
        
    });



function openModalForFlag(){
        if(selectedCheckBoxArray.length == 0){
            showErrorMessage("Please select one dag for proceed...");
            return false;
        }
       var btn = document.getElementById("myBtn");

       var span_close = document.getElementsByClassName("edit-enc-close")[0];

        $('#myLargeModalLabel').modal('show');
        // console.log(selectedCheckBoxArray);
        $("#dags_view").html(selectedCheckBoxArray.toString());
        $("#dag_list").val(selectedCheckBoxArray);
        $('#vill_code').val($("#vill_townprt_code").val());
       
   
       span_close.onclick = function() {
           $('#myLargeModalLabel').modal('hide');
           // table.destroy();
       }
   }

   $('#ajaxMappingForm').submit(function (e) {

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
            url: baseurl + "ChithaFlag/saveMappingWithChitha",
            type: 'POST',
            data: $("#ajaxMappingForm").serialize(),
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