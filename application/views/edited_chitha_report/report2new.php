<div class="row login">
    <div class="col-lg-12 ">
        <div class="col-lg-6 col-lg-offset-3">
            <ol class="progtrckr" data-progtrckr-steps="4">
                <li class="progtrckr-done">First Step</li>
                <li class="progtrckr-done">Second Step</li>
                <li class="progtrckr-done">Third Step</li>
                <li class="progtrckr-todo">Fourth Step</li>
            </ol>
        </div>
    </div>
    <div class="col-lg-12 ">
        <div class="col-lg-6">
            <table class="table table-striped table-hover ">
                <tbody>
                    <tr class="info">
                        <td>District</td>
                        <td>:</td>
                        <td><strong><?php echo  $namedata[0]->district; ?></strong></td>
                    </tr>
                    <tr>
                        <td>Sub-Division</td>
                        <td>:</td>
                        <td><strong><?php echo $namedata[1]->subdiv; ?></strong></td>
                    </tr>
                    <tr>
                        <td>Circle</td>
                        <td>:</td>
                        <td><strong><?php echo $namedata[2]->circle; ?></strong></td>
                    </tr>
                    <tr class="success">
                        <td>Mouza</td>
                        <td>:</td>
                        <td><strong><?php echo $namedata[3]->mouza; ?></strong></td>
                    </tr>
                    <tr>
                        <td>Lot No</td>
                        <td>:</td>
                        <td><strong><?php echo $namedata[4]->lot_no; ?></strong></td>
                    </tr>
                    <tr class="info">
                        <td>Village/Town</td>
                        <td>:</td>
                        <td><strong><?php echo $namedata[5]->village; ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="<?php echo base_url() . 'index.php/chithareportnew/generateChitha' ?>">
                        <label for="select" class="col-lg-3 control-label">Select Patta Type</label>
                        <div class="col-lg-9">
                            <select class="form-control" id="select_patta_type" required name="patta_code" required>
                                <option disabled selected>Select pattatype</option>
                                <?php foreach ($pattatype as $patta) : ?>
                                    <?php
                                    $typeCode = $patta->type_code;
                                    $pattatype = $patta->patta_type;
                                    // session_start();
                                    // $_SESSION['DBname']= $location;
                                    ?>
                                    <option value="<?php echo $typeCode; ?>"><?php echo $pattatype; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Select Multiple Dags</h3>
                </div>
                <div class="panel-body">
                    <label for="select" class="col-lg-2 control-label">From :</label>
                    <div class="col-lg-3">
                        <select class="form-control" id="select" name="dag_no_lower" required>
                            <option disabled selected>Select A Lower Range</option>
                            <?php
                            foreach ($dagrange as $dag) : ?>
                                <?php
                                $dag_numbr = $dag->dag_no;
                                $dag_numbre = $dag->dag_no;
                                // session_start();
                                // $_SESSION['DBname']= $location;
                                ?>
                                <option value="<?php echo  $dag_numbr; ?>"><?php echo $dag_numbre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label for="select" class="col-lg-2 control-label">To</label>
                    <div class="col-lg-3">
                        <select class="form-control" id="select" name="dag_no_upper" required>
                            <option disabled selected>Select A Upper Range</option>
                            <?php
                            foreach ($dagrange as $dag) : ?>
                                <?php
                                $dag_numbr = $dag->dag_no;
                                $dag_numbre = $dag->dag_no;
                                // session_start();
                                // $_SESSION['DBname']= $location;
                                ?>
                                <option value="<?php echo  $dag_numbr; ?>"><?php echo $dag_numbre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>