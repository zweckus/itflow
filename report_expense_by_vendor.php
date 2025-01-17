<?php include("inc_all_reports.php"); ?>
<?php 

if(isset($_GET['year'])){
  $year = intval($_GET['year']);
}else{
  $year = date('Y');
}

if(isset($_GET['year'])){
  $year = intval($_GET['year']);
}else{
  $year = date('Y');
}

$sql_payment_years = mysqli_query($mysqli,"SELECT DISTINCT YEAR(payment_date) AS payment_year FROM payments WHERE company_id = $session_company_id UNION SELECT DISTINCT YEAR(revenue_date) AS payment_year FROM revenues WHERE company_id = $session_company_id ORDER BY payment_year DESC");

$sql_vendors = mysqli_query($mysqli,"SELECT * FROM vendors WHERE company_id = $session_company_id");

?>

<div class="card card-dark">
  <div class="card-header py-2">
    <h3 class="card-title mt-2"><i class="fa fa-fw fa-building"></i> Expense By Vendor</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-primary d-print-none" onclick="window.print();"><i class="fas fa-fw fa-print"></i> Print</button>
    </div>
  </div>
  <div class="card-body p-0">
    <form class="p-3">
      <select onchange="this.form.submit()" class="form-control" name="year">
        <?php 
                
        while($row = mysqli_fetch_array($sql_payment_years)){
          $payment_year = $row['payment_year'];
        ?>
        <option <?php if($year == $payment_year){ ?> selected <?php } ?> > <?php echo $payment_year; ?></option>
        
        <?php
        }
        ?>

      </select>
    </form>

    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Vendor</th>
            <th>Paid</th>
          </tr>
        </thead>
        <tbody>
          <?php
          while($row = mysqli_fetch_array($sql_vendors)){
            $vendor_id = $row['vendor_id'];
            $vendor_name = $row['vendor_name'];

            $sql_amount_paid = mysqli_query($mysqli,"SELECT SUM(expense_amount) AS amount_paid FROM expenses WHERE YEAR(expense_date) = $year AND expense_vendor_id = $vendor_id");
            $row = mysqli_fetch_array($sql_amount_paid);
            
            $amount_paid = $row['amount_paid'];

            if($amount_paid > 599){

              ?>

              <tr>
                <td><?php echo $vendor_name; ?></td>
                <td><?php echo $amount_paid; ?></td>
              </tr>
              <?php 
              } 
            }
            ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include("footer.php"); ?>