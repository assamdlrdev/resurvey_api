<div class="col-lg-12 col-md-12">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">Sync chitha_basic_nc with chitha_basic</div>
    <div class="text-center pb-1">
        <span><?php echo $village ? $village->loc_name : ''; ?></span>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> CHITHA BASIC
                            </h5>
                        </div>
                    </div>
                    <table class="table table-hover table-sm table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Dag</th>
                                <th>Bigha</th>
                                <th>Katha</th>
                                <th>Lessa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chitha_basic_dags as $index => $chitha_basic_dag): ?>
                                <tr>
                                    <td><?= $index ?></td>
                                    <td><?= $chitha_basic_dag->dag_no ?></td>
                                    <td><?= $chitha_basic_dag->dag_area_b ?></td>
                                    <td><?= $chitha_basic_dag->dag_area_k ?></td>
                                    <td><?= $chitha_basic_dag->dag_area_lc ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> CHITHA BASIC NC
                            </h5>
                        </div>
                    </div>
                    <table class="table table-hover table-sm table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Dag</th>
                                <th>Bigha</th>
                                <th>Katha</th>
                                <th>Lessa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chitha_basic_nc_dags as $index => $chitha_basic_nc_dag): ?>
                                <tr class="<?= $chitha_basic_nc_dag->is_synced !='Y' ? 'bg-danger' : ''; ?>">
                                    <td><?= $index ?></td>
                                    <td><?= $chitha_basic_nc_dag->dag_no ?></td>
                                    <td><?= $chitha_basic_nc_dag->dag_area_b ?></td>
                                    <td><?= $chitha_basic_nc_dag->dag_area_k ?></td>
                                    <td><?= $chitha_basic_nc_dag->dag_area_lc ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>