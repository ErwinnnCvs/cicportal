<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Contract Data</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
            <!-- Bar chart -->
            <div class="card card-primary card-outline">
              
              <div class="card-body">
                  <table class="table"> 
                    <thead>
                      <tr>
                        <th></th>
                        <?php
                          $get_three_months = $dbh->query("SELECT fld_date FROM tbcins WHERE fld_date >= now()-interval 1 month ORDER BY fld_date ASC;");
                          while ($gtm=$get_three_months->fetch_array()) {
                        ?>
                        <th><?php echo date("d-M-Y", strtotime($gtm['fld_date'])); ?></th>
                        <?php
                          }
                        ?>
                      </tr>
                    </thead>
                    <tbody> 
                        <tr>  
                            <th>Credit Cards</th>
                            <?php
                              $get_three_months_cc = $dbh->query("SELECT fld_date, fld_contract_credit_card as cc FROM tbcins WHERE fld_date >= now()-interval 1 month ORDER BY fld_date ASC;");
                              while ($gtmcc=$get_three_months_cc->fetch_array()) {
                            ?>
                              <td><?php echo number_format($gtmcc['cc']); ?></td>
                            <?php
                              }
                            ?>
                        </tr>
                        <tr>
                            <th>Installment</th>
                            <?php
                              $get_three_months_ci = $dbh->query("SELECT fld_date, fld_contract_installment as ci FROM tbcins WHERE fld_date >= now()-interval 1 month ORDER BY fld_date ASC;");
                              while ($gtmci=$get_three_months_ci->fetch_array()) {
                            ?>
                              <td><?php echo number_format($gtmci['ci']); ?></td>
                            <?php
                              }
                            ?>
                        </tr>
                        <tr>
                            <th>Non-Installment</th>
                            <?php
                              $get_three_months_cn = $dbh->query("SELECT fld_date, fld_contract_non_installment as cn FROM tbcins WHERE fld_date >= now()-interval 1 month ORDER BY fld_date ASC;");
                              while ($gtmcn=$get_three_months_cn->fetch_array()) {
                            ?>
                              <td><?php echo number_format($gtmcn['cn']); ?></td>
                            <?php
                              }
                            ?>
                        </tr>
                        <tr>
                          <th>Total</th>
                          <?php
                          $get_total = $dbh->query("SELECT * FROM tbcins WHERE fld_date >= now()-interval 1 month ORDER BY fld_date ASC;");
                          while ($gt=$get_total->fetch_array()) {
                          ?>
                          <td><b><?php echo number_format($gt['fld_contract_credit_card'] + $gt['fld_contract_installment'] + $gt['fld_contract_non_installment']); ?></b></td>
                          <?php
                            }
                          ?>
                        </tr>
                    </tbody>
                  </table>  
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->

            <!-- Donut chart -->
            
          </div>
          <!-- /.col -->

          <div class="col-md-6">
            <!-- Bar chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Total Contracts
                </h3>
              </div>
              <div class="card-body">
                <div id="total-contracts-bar-chart" style="height: 300px;"></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->

            <!-- Donut chart -->
            
          </div>
          <!-- /.col -->
      </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

  

</section>
<!-- /.content -->
