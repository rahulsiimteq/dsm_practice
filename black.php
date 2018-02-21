<?php
  $conn = mysqli_connect("localhost","root","K!ller.21896#","dsm_new");

  $shape_id = "";
    if (isset($_POST['shape'])) {
      $shape_id = $_POST['shape'];
    $diamond_black = mysqli_query($conn,"SELECT * FROM diamonds WHERE diamond_shape_id = '$shape_id' AND diamond_type ='Black' AND diamond_lot_no LIKE 'B%'");
    }else {
  $diamond_black = mysqli_query($conn,"SELECT * FROM diamonds WHERE diamond_shape_id = '2' AND diamond_type ='Black' AND diamond_lot_no LIKE 'B%'");
    }
  $shape = "SELECT distinct(`attribute_name`),`attribute_id`,attribute_label FROM `attributes` where `attribute_type` = 'Shape'";
  $sql2 = mysqli_query($conn, $shape);
  $status_table = "SELECT distinct(diamond_status) AS status,count(diamond_status) AS count,CEIL(sum(diamond_size)) AS carat FROM `diamonds` WHERE diamond_type = 'Black' AND diamond_lot_no LIKE 'B%' group by diamond_status ORDER BY diamond_id DESC";
  $status_table_result = mysqli_query($conn, $status_table);
$black_office = mysqli_query($conn,"SELECT office_name,office_id from offices  WHERE office_id IN (SELECT DISTINCT(office_id) FROM diamonds where  diamond_type = 'Black' AND diamond_lot_no LIKE 'B%')");

  ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Diamond | Black</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>
  <body>


    <div class="container-fluid">
     <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Diamond Inventory</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="nav navbar-nav ml-auto">
            <li class="nav-item ">
              <a class="nav-link" href="index.php">Certified </span></a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="black.php">Black <span class="sr-only">(current)</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="fancy.php">Fancy</a>
            </li>
         <li class="nav-item">
              <a class="nav-link" href="matchingpair.php">Matching Pair</a>
            </li>
          </ul>
        </div>
      </nav>


    <div class="row">
      <div class="form-group col-md-5">
                <label for="search_table" class="control-label">Search Inventory</label>
                <input type="text" name="search_table" id="search_table" placeholder="Search Inventory" class="form-control" />
      </div>
  </div>

  <div class="col-md-4" style="float:right;">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Status</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Location</a>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent" >
      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <table class="table table-sm col-md-4 table-bordered" style="border:1px solid black;">
        <thead>
          <th>Status</th>
          <th>Count</th>
          <th>Carat</th>
         </thead>
         <tbody>

           <?php while($status = mysqli_fetch_assoc($status_table_result)):
            if ($status['status'] == 'InTranist'){ ?>
              <tr style="background-color: #f9e79f ;">
              <td><button type="button" class="btn btn-link status2" value="<?= $status['status'] ?>"><?php echo $status['status'] ?></button></td>
              <td><?php echo $status['count'] ?></td>
              <td><?php echo $status['carat'] ?></td>
            </tr>
          <?php } if($status['status'] == 'Available'){ ?>
            <tr style="background-color: #FDFEFE;">
            <td><button type="button" class="btn btn-link status2"  value="<?= $status['status'] ?>"><?php echo $status['status'] ?></button></td>
            <td><?php echo $status['count'] ?></td>
            <td><?php echo $status['carat'] ?></td>
          </tr>
        <?php } if($status['status'] == 'Reserve'){ ?>
            <tr style="background-color:#c4dbea;">
            <td><button type="button" class="btn btn-link status2"  value="<?= $status['status'] ?>"><?php echo $status['status'] ?></button></td>
            <td><?php echo $status['count'] ?></td>
            <td><?php echo $status['carat'] ?></td>
          </tr>
        <?php } if($status['status'] == 'In Transfer Process'){ ?>
            <tr style="background-color:#a9dfbf;">
            <td><button type="button" class="btn btn-link status2"  value="<?= $status['status'] ?>"><?php echo $status['status'] ?></button></td>
            <td><?php echo $status['count'] ?></td>
            <td><?php echo $status['carat'] ?></td>
          </tr>
        <?php } if($status['status'] == 'On Consignment') { ?>
          <tr style="background-color: #f1c4c0;">
          <td><button type="button" class="btn btn-link status2"  value="<?= $status['status'] ?>"><?php echo $status['status'] ?></button></td>
          <td><?php echo $status['count'] ?></td>
          <td><?php echo $status['carat'] ?></td>
        </tr>
          <?php } endwhile; ?>

         </tbody>
      </table></div>
      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <table class="table col-md-4 table-bordered" style="border:1px solid black;">
          <thead>
            <th>Available Location</th>
            <th>Count</th>
            <th>Carat</th>
          </thead>
          <tbody>
              <?php while($offices = mysqli_fetch_assoc($black_office)):
                $count_office = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(office_id) as count,sum(diamond_size) as carat FROM diamonds where diamond_type = 'Black' AND diamond_lot_no LIKE 'B%' AND office_id = '".$offices['office_id']."'"));
                ?>

            <tr>

            <th><?=$offices['office_name']?></th>

            <td><?=$count_office['count']?></td>
            <td><?=$count_office['carat']?></td>
            <?php endwhile; ?>
          </tr>

          </tbody>
        </table>
      </div>


    </div>
</div>


          <form method="POST" action="">

          <fieldset class="form-group">
              <?php while($row_shape = mysqli_fetch_assoc($sql2)): ?>
                <?php
                     $sql_count = mysqli_query($conn, "SELECT COUNT(*) from `diamonds` WHERE diamond_type = 'Black' AND `diamond_shape_id` = '".$row_shape['attribute_id']."' AND diamond_lot_no LIKE 'B%'");
                     $row_count = mysqli_fetch_array($sql_count);
                      if($row_count[0] != 0){
                    ?>
                <button class="btn btn-outline-primary" value="<?=$row_shape['attribute_id']?>"  name = 'shape' type="submit" id="demo3" ><img height="20" width="20" src="img\round.png"><?= $row_shape['attribute_label']."(".$row_count[0].")" ?></button>
              <?php } endwhile;?>
          </fieldset>

        </form>

  </div>
    <table class="table table-bordered" id="searchtable">
      <thead>
        <th>LOT NO #</th>
        <th>LOC</th>
        <th>SHAPE</th>
        <th>CARAT</th>
        <th>Desc</th>
        <th>MEASUREMENT L*B*H</span></th>
        <th>ORIGINAL P/C </th>
        <th>ORIGINAL TOTAL</th>
        <th>REVALUATED P/C</th>
        <th>REVALUATED TOTAL</th>
        <th>FINAL P/C</th>
        <th>FINAL TOTAL</th>
        <th>SELLING P/C PRICE</th>
        <th>SELLING TOTAL</th>
        <th>STATUS</th>
        <th>CUSTOMER</th>
        <th>FRONT VIEW</th>
        <th>PURCHASE DATE (DD/MM/YYYY)</th>
        <th>PARTY</th>
      </thead>
      <tbody>
        <?php while ($diamond = mysqli_fetch_assoc($diamond_black)) :
                    $black_customer = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users where user_id = '".$diamond['diamond_customer_id']."'"));
                    $black_shape = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM attributes where attribute_id = '".$diamond['diamond_shape_id']."'"));
                    $black_office = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM offices where office_id = '".$diamond['office_id']."'"));
            ?>
      <?php  if ($diamond['diamond_status'] == 'InTranist'){ ?>
          <tr style="background-color: #f9e79f ;">
        <td><?=$diamond['diamond_lot_no']?></td>
        <td><?=$black_shape['attribute_name']?></td>
        <td><?=$black_office['office_name']?></td>
        <td><?=$diamond['diamond_size']?></td>
        <td><?=$diamond['diamond_desc']?></td>
        <td><?=$diamond['diamond_meas1']?> X <?=$diamond['diamond_meas2']?> X <?=$diamond['diamond_meas3']?></td>
        <td><?="$ ".round($diamond['diamond_price_perct'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_total'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_perct_revaluated'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_total_revaluated'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_perct_final'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_total_final'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_perct_sell'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_sell'],2)?></td>
        <td><?=$diamond['diamond_status']?></td>
        <td><?=$black_customer['company_name']?></td>
        <td><?=$diamond['diamond_status_front']?></td>
        <td><?=date('d/m/Y',strtotime($diamond['diamond_purchase_date'])) ?></td>
        <td><?=$diamond['diamond_party_name']?></td>
      </tr>
    <?php } if($diamond['diamond_status'] == 'Available'){ ?>
      <tr style="background-color: #FDFEFE;">
        <td><?=$diamond['diamond_lot_no']?></td>
        <td><?=$black_shape['attribute_name']?></td>
        <td><?=$black_office['office_name']?></td>
        <td><?=$diamond['diamond_size']?></td>
        <td><?=$diamond['diamond_desc']?></td>
        <td><?=$diamond['diamond_meas1']?> X <?=$diamond['diamond_meas2']?> X <?=$diamond['diamond_meas3']?></td>
        <td><?="$ ".round($diamond['diamond_price_perct'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_total'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_perct_revaluated'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_total_revaluated'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_perct_final'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_total_final'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_perct_sell'],2)?></td>
        <td><?="$ ".round($diamond['diamond_price_sell'],2)?></td>
        <td><?=$diamond['diamond_status']?></td>
        <td><?=$black_customer['company_name']?></td>
        <td><?=$diamond['diamond_status_front']?></td>
        <td><?=date('d/m/Y',strtotime($diamond['diamond_purchase_date'])) ?></td>
        <td><?=$diamond['diamond_party_name']?></td>
        </tr>
      <?php } if($diamond['diamond_status'] == 'Reserve'){ ?>
          <tr style="background-color:#c4dbea;">
            <td><?=$diamond['diamond_lot_no']?></td>
            <td><?=$black_shape['attribute_name']?></td>
            <td><?=$black_office['office_name']?></td>
            <td><?=$diamond['diamond_size']?></td>
            <td><?=$diamond['diamond_desc']?></td>
            <td><?=$diamond['diamond_meas1']?> X <?=$diamond['diamond_meas2']?> X <?=$diamond['diamond_meas3']?></td>
            <td><?="$ ".round($diamond['diamond_price_perct'],2)?></td>
            <td><?="$ ".round($diamond['diamond_price_total'],2)?></td>
            <td><?="$ ".round($diamond['diamond_price_perct_revaluated'],2)?></td>
            <td><?="$ ".round($diamond['diamond_price_total_revaluated'],2)?></td>
            <td><?="$ ".round($diamond['diamond_price_perct_final'],2)?></td>
            <td><?="$ ".round($diamond['diamond_price_total_final'],2)?></td>
            <td><?="$ ".round($diamond['diamond_price_perct_sell'],2)?></td>
            <td><?="$ ".round($diamond['diamond_price_sell'],2)?></td>
            <td><?=$diamond['diamond_status']?></td>
            <td><?=$black_customer['company_name']?></td>
            <td><?=$diamond['diamond_status_front']?></td>
            <td><?=date('d/m/Y',strtotime($diamond['diamond_purchase_date'])) ?></td>
            <td><?=$diamond['diamond_party_name']?></td>
            </tr>
          <?php } if($diamond['diamond_status'] == 'In Transfer Process'){ ?>
              <tr style="background-color:#a9dfbf;">
                <td><?=$diamond['diamond_lot_no']?></td>
                <td><?=$black_shape['attribute_name']?></td>
                <td><?=$black_office['office_name']?></td>
                <td><?=$diamond['diamond_size']?></td>
                <td><?=$diamond['diamond_desc']?></td>
                <td><?=$diamond['diamond_meas1']?> X <?=$diamond['diamond_meas2']?> X <?=$diamond['diamond_meas3']?></td>
                <td><?="$ ".round($diamond['diamond_price_perct'],2)?></td>
                <td><?="$ ".round($diamond['diamond_price_total'],2)?></td>
                <td><?="$ ".round($diamond['diamond_price_perct_revaluated'],2)?></td>
                <td><?="$ ".round($diamond['diamond_price_total_revaluated'],2)?></td>
                <td><?="$ ".round($diamond['diamond_price_perct_final'],2)?></td>
                <td><?="$ ".round($diamond['diamond_price_total_final'],2)?></td>
                <td><?="$ ".round($diamond['diamond_price_perct_sell'],2)?></td>
                <td><?="$ ".round($diamond['diamond_price_sell'],2)?></td>
                <td><?=$diamond['diamond_status']?></td>
                <td><?=$black_customer['company_name']?></td>
                <td><?=$diamond['diamond_status_front']?></td>
                <td><?=date('d/m/Y',strtotime($diamond['diamond_purchase_date'])) ?></td>
                <td><?=$diamond['diamond_party_name']?></td>
                </tr>
              <?php } if($diamond['diamond_status'] == 'On Consignment') { ?>
                <tr style="background-color: #f1c4c0;">
                  <td><?=$diamond['diamond_lot_no']?></td>
                  <td><?=$black_shape['attribute_name']?></td>
                  <td><?=$black_office['office_name']?></td>
                  <td><?=$diamond['diamond_size']?></td>
                  <td><?=$diamond['diamond_desc']?></td>
                  <td><?=$diamond['diamond_meas1']?> X <?=$diamond['diamond_meas2']?> X <?=$diamond['diamond_meas3']?></td>
                  <td><?="$ ".round($diamond['diamond_price_perct'],2)?></td>
                  <td><?="$ ".round($diamond['diamond_price_total'],2)?></td>
                  <td><?="$ ".round($diamond['diamond_price_perct_revaluated'],2)?></td>
                  <td><?="$ ".round($diamond['diamond_price_total_revaluated'],2)?></td>
                  <td><?="$ ".round($diamond['diamond_price_perct_final'],2)?></td>
                  <td><?="$ ".round($diamond['diamond_price_total_final'],2)?></td>
                  <td><?="$ ".round($diamond['diamond_price_perct_sell'],2)?></td>
                  <td><?="$ ".round($diamond['diamond_price_sell'],2)?></td>
                  <td><?=$diamond['diamond_status']?></td>
                  <td><?=$black_customer['company_name']?></td>
                  <td><?=$diamond['diamond_status_front']?></td>
                  <td><?=date('d/m/Y',strtotime($diamond['diamond_purchase_date'])) ?></td>
                  <td><?=$diamond['diamond_party_name']?></td>
                  </tr>
<?php }  endwhile; ?>
      </tbody>

</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <script type="text/javascript">
       $("#search_table").keyup(function(){
         _this = this;
         // Show only matching TR, hide rest of them
         $.each($("#searchtable tbody tr"), function() {
         if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
             $(this).hide();
             else
             $(this).show();
         });
       });
       </script>
       <script type="text/javascript">
    $('.status2').click(function() {
      var id = $(this).val();
      $('#search_table').val(id);
      _this = this;
      // Show only matching TR, hide rest of them
      $.each($("#searchtable tbody tr"), function() {
      if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
          $(this).hide();
          else
          $(this).show();
      });
    });
  </script>

  </body>
</html>
