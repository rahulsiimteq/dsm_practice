<?php
  $conn = mysqli_connect("localhost","root","K!ller.21896#","dsm_new");
$shape_id = "";
  if (isset($_POST['shape'])) {
    $shape_id = $_POST['shape'];

    $diamond  = "SELECT * FROM `diamonds` WHERE diamond_shape_id = '$shape_id' AND diamond_type = 'Certified' AND diamond_lot_no LIKE 'C%' AND diamond_status NOT IN ('Invoiced','Deleted') ORDER BY diamond_id DESC";
    $sql = mysqli_query($conn, $diamond);
  }else {
    $diamond  = "SELECT * FROM `diamonds` WHERE diamond_shape_id = 2 AND `diamond_size` >= 0.90 AND diamond_type = 'Certified' AND diamond_lot_no LIKE 'C%' AND diamond_status NOT IN ('Invoiced','Deleted') ORDER BY diamond_lot_no DESC";
    $sql = mysqli_query($conn, $diamond);
  }
  $shape = "SELECT distinct(`attribute_name`),`attribute_id`,attribute_label FROM `attributes` where `attribute_type` = 'Shape'";
  $sql2 = mysqli_query($conn, $shape);
  $status_table = "SELECT distinct(diamond_status) AS status,count(diamond_status) AS count,CEIL(sum(diamond_size)) AS carat FROM `diamonds` WHERE diamond_type = 'Certified' AND diamond_lot_no LIKE 'C%' AND diamond_status NOT IN ('Invoiced','Deleted') group by diamond_status ORDER BY diamond_id DESC";
  $status_table_result = mysqli_query($conn, $status_table);

  $total_cert = mysqli_fetch_assoc(mysqli_query($conn,"SELECT
 ROUND(SUM(`diamond_price_total`),2) AS sumTotal,
 ROUND(SUM(`diamond_price_total_revaluated`),2) AS revalTotal,
 ROUND(SUM(`diamond_price_total_final`),2) AS finalTotal,
 ROUND(SUM(`diamond_price_sell`),2) AS sellTotal,
 ROUND(SUM(`diamond_size`),2) AS caratTotal,
 COUNT(*) AS certCount

FROM
 diamonds
WHERE
	`diamond_type` = 'Certified'
AND `diamond_lot_no` LIKE 'C%'
AND `diamond_status` NOT IN ('Invoiced','Deleted')"));

$total_cert_temp = mysqli_fetch_assoc(mysqli_query($conn,"SELECT
ROUND(SUM(`diamond_price_total`),2) AS TempsumTotal,
ROUND(SUM(`diamond_price_total_revaluated`),2) AS TemprevalTotal,
ROUND(SUM(`diamond_price_total_final`),2) AS TempfinalTotal,
ROUND(SUM(`diamond_price_sell`),2) AS TempsellTotal,
ROUND(SUM(`diamond_size`),2) AS TempcaratTotal,
COUNT(*) AS tempCount
FROM
diamonds
WHERE
`diamond_type` = 'Certified'
AND `diamond_lot_no` LIKE 'T%'
AND `diamond_status` NOT IN ('Invoiced','Deleted')"));

?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>DSM | Certified</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    </head>
  <body>
    <div class="container-fluid text-center">
      <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Diamond Inventory</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="nav navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="index.php">Certified <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="black.php">Black</a>
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
    <?php $started_at = microtime(true); ?>

<div class="row">
  <div class="form-group col-md-5">
            <label for="search_table" class="control-label">Search Inventory</label>
            <input type="text" name="search_table" id="search_table" placeholder="Search Inventory" class="form-control" />
  </div>


<div class="col-md-4" style="float:right;">
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Status</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Certified</a>
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
          <th>Desc/type</th>
          <th>Certified</th>
          <th>Temp.Cert</th>
          <th>Total</th>
        </thead>
        <tbody>
          <tr>
          <th>Orig.Price</th>
          <td><?=$total_cert['sumTotal']?></td>
          <td><?=$total_cert_temp['TempsumTotal']?></td>
          <td><?=$total_cert['sumTotal'] + $total_cert_temp['TempsumTotal']?></td>
        </tr>
          <tr>
          <th>Rev.Price</th>
          <td><?=$total_cert['revalTotal']?></td>
          <td><?=$total_cert_temp['TemprevalTotal']?></td>
          <td><?= $total_cert['revalTotal'] + $total_cert_temp['TemprevalTotal'] ?></td>
        </tr>
        <tr>
        <th>Fin.Price</th>
        <td><?=$total_cert['finalTotal']?></td>
        <td><?=$total_cert_temp['TempfinalTotal']?></td>
        <td><?= $total_cert['finalTotal'] + $total_cert_temp['TempfinalTotal'] ?></td>
      </tr>
      <tr>
      <th>Sell.Price</th>
      <td><?=$total_cert['sellTotal']?></td>
      <td><?=$total_cert_temp['TempsellTotal']?></td>
      <td><?=$total_cert['sellTotal'] + $total_cert_temp['TempsellTotal'] ?></td>
    </tr>
    <tr>
    <th>Carat</th>
    <td><?= $total_cert['caratTotal']?></td>
    <td><?= $total_cert_temp['TempcaratTotal']?></td>
    <td><?= $total_cert['caratTotal'] + $total_cert_temp['TempcaratTotal'] ?></td>
  </tr>
  <tr>
  <th># Diamonds</th>
  <td><?= $total_cert['certCount']?></td>
  <td><?= $total_cert_temp['tempCount']?></td>
  <td><?= $total_cert['certCount'] + $total_cert_temp['tempCount'] ?></td>
</tr>

        </tbody>
      </table>
    </div>


  </div>




</div>

</div>

<div class="row">


      <form method="POST" action="">

      <fieldset class="form-group">
          <?php while($row_shape = mysqli_fetch_assoc($sql2)): ?>
            <?php
                 $sql_count = mysqli_query($conn, "SELECT COUNT(*) from `diamonds` WHERE diamond_type = 'Certified' AND `diamond_shape_id` = '".$row_shape['attribute_id']."' AND diamond_lot_no LIKE 'C%' AND diamond_status NOT IN ('Invoiced','Deleted') ");
                 $row_count = mysqli_fetch_array($sql_count);
                  if($row_count[0] != 0){
                ?>
            <button class="btn btn-outline-primary" value="<?=$row_shape['attribute_id']?>"  name = 'shape' type="submit" id="demo3" ><img height="20" width="20" src="img\round.png"><?= $row_shape['attribute_label']."(".$row_count[0].")" ?></button>
          <?php } endwhile;?>
      </fieldset>

    </form>
</div>

    <table class="table table-bordered" id="searchtable">
      <thead class="table-inverse">
        <th>LOT NO #</th>
        <th>LOC</th>
        <th>SHAPE</th>
        <th>CARAT</th>
        <th>LAB</th>
        <th>CERTIFICATE</th>
        <th>CLR</th>
        <th>CLA</th>
        <th>FLR</th>
        <th>FCUT</th>
        <th>POL</th>
        <th>SYM</th>
        <th>INS</th>
        <th>AINS</th>
        <th>MEASUREMENT L*B*H</span></th>
        <th>ORIGINAL RAPNET</th>
        <th>ORGINAL DISCOUNT</th>
        <th>ORIGINAL P/C </th>
        <th>ORIGINAL TOTAL</th>
        <th>REVALUATED DISCOUNT</th>
        <th>REVALUATED P/C</th>
        <th>REVALUATED TOTAL</th>
        <th>FINAL RAPNET</th>
        <th>FINAL DISCOUNT</th>
        <th>FINAL P/C</th>
        <th>FINAL TOTAL</th>
        <th>SELLING DISCOUNT</th>
        <th>SELLING P/C PRICE</th>
        <th>SELLING TOTAL</th>
        <th>STATUS</th>
        <th>CUSTOMER</th>
        <th>FRONT VIEW</th>
        <th>RAPNET VIEW</th>
        <th>PURCHASE DATE (DD/MM/YYYY)</th>
        <th>PARTY</th>
        <th>APPROVAL NO</th>
        <th>APPROVAL DATE</th>
      </thead>
      <tbody id = "data">
        <?php while($array =  mysqli_fetch_assoc($sql)){
              $office_name_query = "SELECT `office_name` from offices WHERE `office_id` = '".$array['office_id']."'";
              $office_name = mysqli_query($conn, $office_name_query);
              $office_array = mysqli_fetch_assoc($office_name);
              $shape_name_query = "SELECT `attribute_name` as `shape` from attributes WHERE `attribute_id` = '".$array['diamond_shape_id']."'";
              $shape_name = mysqli_query($conn, $shape_name_query);
              $shape_array = mysqli_fetch_assoc($shape_name);
              $lab_name_query = "SELECT `attribute_name` as `lab` from attributes WHERE `attribute_id` = '".$array['diamond_lab_id']."'";
              $lab_name = mysqli_query($conn, $lab_name_query);
              $lab_array = mysqli_fetch_assoc($lab_name);
              $clr_name_query = "SELECT `attribute_name` as `clr` from attributes WHERE `attribute_id` = '".$array['diamond_clr_id']."'";
              $clr_name = mysqli_query($conn, $clr_name_query);
              $clr_array = mysqli_fetch_assoc($clr_name);
              $cla_name_query = "SELECT `attribute_name` as `cla` from attributes WHERE `attribute_id` = '".$array['diamond_cla_id']."'";
              $cla_name = mysqli_query($conn, $cla_name_query);
              $cla_array = mysqli_fetch_assoc($cla_name);
              $flr_name_query = "SELECT `attribute_name` as `flr` from attributes WHERE `attribute_id` = '".$array['diamond_flr_id']."'";
              $flr_name = mysqli_query($conn, $flr_name_query);
              $flr_array = mysqli_fetch_assoc($flr_name);
              $fcut_name_query = "SELECT `attribute_name` as `fcut` from attributes WHERE `attribute_id` = '".$array['diamond_fcut_id']."'";
              $fcut_name = mysqli_query($conn, $fcut_name_query);
              $fcut_array = mysqli_fetch_assoc($fcut_name);
              $pol_name_query = "SELECT `attribute_name` as `pol` from attributes WHERE `attribute_id` = '".$array['diamond_pol_id']."'";
              $pol_name = mysqli_query($conn, $pol_name_query);
              $pol_array = mysqli_fetch_assoc($pol_name);
              $sym_name_query = "SELECT `attribute_name` as `sym` from attributes WHERE `attribute_id` = '".$array['diamond_sym_id']."'";
              $sym_name = mysqli_query($conn, $sym_name_query);
              $sym_array = mysqli_fetch_assoc($sym_name);
              $diamond_company_name = "SELECT `company_name` from users WHERE `user_id` = '".$array['diamond_customer_id']."'";
              $company_name = mysqli_query($conn, $diamond_company_name);
              $company_array = mysqli_fetch_assoc($company_name);
if ($array['diamond_status'] == 'InTranist') {

           ?>

        <tr style="background-color: #f9e79f ;">
        <td><?php echo $array['diamond_lot_no'];?></td>
        <td><?=$office_array['office_name']?></td>
        <td><?=$shape_array['shape']?></td>
        <td><?=$array['diamond_size']?></td>
        <td><?=$lab_array['lab']?></td>
        <td><?=$array['diamond_cert']?></td>
        <td><?=$clr_array['clr']?></td>
        <td><?=$cla_array['cla']?></td>
        <td><?=$flr_array['flr']?></td>
        <td><?=$fcut_array['fcut']?></td>
        <td><?=$pol_array['pol']?></td>
        <td><?=$sym_array['sym']?></td>
        <td><?=$array['diamond_ins']?></td>
        <td><?=$array['diamond_ains']?></td>
        <td><?=$array['diamond_meas1']?> X <?=$array['diamond_meas2']?> X <?=$array['diamond_meas3']?></td>
        <td><?="$".round($array['diamond_price_rapnet'],2)?></td>
        <td><?=round($array['diamond_discount'],2). "%" ?></td>
        <td><?="$".round($array['diamond_price_perct'],2)?></td>
        <td><?="$".round($array['diamond_price_total'],2)?></td>
        <td><?=round($array['diamond_discount_revaluated'],2)."%" ?></td>
        <td><?="$".round($array['diamond_price_perct_revaluated'],2)?></td>
        <td><?="$".round($array['diamond_price_total_revaluated'],2)?></td>
        <td><?="$".round($array['diamond_price_rapnet_final'],2)?></td>
        <td><?=round($array['diamond_discount_final'],2)."%" ?></td>
        <td><?="$".round($array['diamond_price_perct_final'],2)?></td>
        <td><?="$".round($array['diamond_price_total_final'],2)?></td>

          <td><?=round($array['diamond_discount_sell'],2)."%" ?></td>
        <td><?="$".round($array['diamond_price_perct_sell'],2)?></td>
      <td><?="$".round($array['diamond_price_sell'],2)?></td>
        <td><?=$array['diamond_status']?></td>
        <td><?=$company_array['company_name']?></td>
        <td><?=$array['diamond_status_front']?></td>
        <td><?=$array['diamond_show_rapnet']?></td>
        <td><?=date('d/m/Y',strtotime($array['diamond_purchase_date'])) ?></td>
        <td><?=$array['diamond_party_name']?></td>
        <td><?=$array['diamond_approval_no']?></td>
        <td></td>
      </tr>
    <?php }
    if ($array['diamond_status'] == 'Available') {

               ?>

            <tr style="background-color: #FDFEFE;">
            <td><?php echo $array['diamond_lot_no'];?></td>
            <td><?=$office_array['office_name']?></td>
            <td><?=$shape_array['shape']?></td>
            <td><?=$array['diamond_size']?></td>
            <td><?=$lab_array['lab']?></td>
            <td><?=$array['diamond_cert']?></td>
            <td><?=$clr_array['clr']?></td>
            <td><?=$cla_array['cla']?></td>
            <td><?=$flr_array['flr']?></td>
            <td><?=$fcut_array['fcut']?></td>
            <td><?=$pol_array['pol']?></td>
            <td><?=$sym_array['sym']?></td>
            <td><?=$array['diamond_ins']?></td>
            <td><?=$array['diamond_ains']?></td>
            <td><?=$array['diamond_meas1']?> X <?=$array['diamond_meas2']?> X <?=$array['diamond_meas3']?></td>
            <td><?="$".round($array['diamond_price_rapnet'],2)?></td>
            <td><?=round($array['diamond_discount'],2). "%" ?></td>
            <td><?="$".round($array['diamond_price_perct'],2)?></td>
            <td><?="$".round($array['diamond_price_total'],2)?></td>
            <td><?=round($array['diamond_discount_revaluated'],2)."%" ?></td>
            <td><?="$".round($array['diamond_price_perct_revaluated'],2)?></td>
            <td><?="$".round($array['diamond_price_total_revaluated'],2)?></td>
            <td><?="$".round($array['diamond_price_rapnet_final'],2)?></td>
            <td><?=round($array['diamond_discount_final'],2)."%" ?></td>
            <td><?="$".round($array['diamond_price_perct_final'],2)?></td>
            <td><?="$".round($array['diamond_price_total_final'],2)?></td>

              <td><?=round($array['diamond_discount_sell'],2)."%" ?></td>
            <td><?="$".round($array['diamond_price_perct_sell'],2)?></td>
          <td><?="$".round($array['diamond_price_sell'],2)?></td>
            <td><?=$array['diamond_status']?></td>
            <td><?=$company_array['company_name']?></td>
            <td><?=$array['diamond_status_front']?></td>
            <td><?=$array['diamond_show_rapnet']?></td>
            <td><?=date('d/m/Y',strtotime($array['diamond_purchase_date'])) ?></td>
            <td><?=$array['diamond_party_name']?></td>
            <td><?=$array['diamond_approval_no']?></td>
            <td></td>
          </tr>
        <?php } ?>
      <?php
      if ($array['diamond_status'] == 'On Consignment') {

                 ?>

              <tr style="background-color: #f1c4c0;">
              <td><?php echo $array['diamond_lot_no'];?></td>
              <td><?=$office_array['office_name']?></td>
              <td><?=$shape_array['shape']?></td>
              <td><?=$array['diamond_size']?></td>
              <td><?=$lab_array['lab']?></td>
              <td><?=$array['diamond_cert']?></td>
              <td><?=$clr_array['clr']?></td>
              <td><?=$cla_array['cla']?></td>
              <td><?=$flr_array['flr']?></td>
              <td><?=$fcut_array['fcut']?></td>
              <td><?=$pol_array['pol']?></td>
              <td><?=$sym_array['sym']?></td>
              <td><?=$array['diamond_ins']?></td>
              <td><?=$array['diamond_ains']?></td>
              <td><?=$array['diamond_meas1']?> X <?=$array['diamond_meas2']?> X <?=$array['diamond_meas3']?></td>
              <td><?="$".round($array['diamond_price_rapnet'],2)?></td>
              <td><?=round($array['diamond_discount'],2). "%" ?></td>
              <td><?="$".round($array['diamond_price_perct'],2)?></td>
              <td><?="$".round($array['diamond_price_total'],2)?></td>
              <td><?=round($array['diamond_discount_revaluated'],2)."%" ?></td>
              <td><?="$".round($array['diamond_price_perct_revaluated'],2)?></td>
              <td><?="$".round($array['diamond_price_total_revaluated'],2)?></td>
              <td><?="$".round($array['diamond_price_rapnet_final'],2)?></td>
              <td><?=round($array['diamond_discount_final'],2)."%" ?></td>
              <td><?="$".round($array['diamond_price_perct_final'],2)?></td>
              <td><?="$".round($array['diamond_price_total_final'],2)?></td>

                <td><?=round($array['diamond_discount_sell'],2)."%" ?></td>
              <td><?="$".round($array['diamond_price_perct_sell'],2)?></td>
            <td><?="$".round($array['diamond_price_sell'],2)?></td>
              <td><?=$array['diamond_status']?></td>
              <td><?=$company_array['company_name']?></td>
              <td><?=$array['diamond_status_front']?></td>
              <td><?=$array['diamond_show_rapnet']?></td>
              <td><?=date('d/m/Y',strtotime($array['diamond_purchase_date'])) ?></td>
              <td><?=$array['diamond_party_name']?></td>
              <td><?=$array['diamond_approval_no']?></td>
              <td></td>
            </tr>
          <?php } ?>
          <?php
          if ($array['diamond_status'] == 'Reserve') {

                     ?>

                  <tr style="background-color:#c4dbea;">
                  <td><?php echo $array['diamond_lot_no'];?></td>
                  <td><?=$office_array['office_name']?></td>
                  <td><?=$shape_array['shape']?></td>
                  <td><?=$array['diamond_size']?></td>
                  <td><?=$lab_array['lab']?></td>
                  <td><?=$array['diamond_cert']?></td>
                  <td><?=$clr_array['clr']?></td>
                  <td><?=$cla_array['cla']?></td>
                  <td><?=$flr_array['flr']?></td>
                  <td><?=$fcut_array['fcut']?></td>
                  <td><?=$pol_array['pol']?></td>
                  <td><?=$sym_array['sym']?></td>
                  <td><?=$array['diamond_ins']?></td>
                  <td><?=$array['diamond_ains']?></td>
                  <td><?=$array['diamond_meas1']?> X <?=$array['diamond_meas2']?> X <?=$array['diamond_meas3']?></td>
                  <td><?="$".round($array['diamond_price_rapnet'],2)?></td>
                  <td><?=round($array['diamond_discount'],2). "%" ?></td>
                  <td><?="$".round($array['diamond_price_perct'],2)?></td>
                  <td><?="$".round($array['diamond_price_total'],2)?></td>
                  <td><?=round($array['diamond_discount_revaluated'],2)."%" ?></td>
                  <td><?="$".round($array['diamond_price_perct_revaluated'],2)?></td>
                  <td><?="$".round($array['diamond_price_total_revaluated'],2)?></td>
                  <td><?="$".round($array['diamond_price_rapnet_final'],2)?></td>
                  <td><?=round($array['diamond_discount_final'],2)."%" ?></td>
                  <td><?="$".round($array['diamond_price_perct_final'],2)?></td>
                  <td><?="$".round($array['diamond_price_total_final'],2)?></td>

                    <td><?=round($array['diamond_discount_sell'],2)."%" ?></td>
                  <td><?="$".round($array['diamond_price_perct_sell'],2)?></td>
                <td><?="$".round($array['diamond_price_sell'],2)?></td>
                  <td><?=$array['diamond_status']?></td>
                  <td><?=$company_array['company_name']?></td>
                  <td><?=$array['diamond_status_front']?></td>
                  <td><?=$array['diamond_show_rapnet']?></td>
                  <td><?=date('d/m/Y',strtotime($array['diamond_purchase_date'])) ?></td>
                  <td><?=$array['diamond_party_name']?></td>
                  <td><?=$array['diamond_approval_no']?></td>
                  <td></td>
                </tr>
              <?php } ?>
              <?php
              if ($array['diamond_status'] == 'In Transfer Process') {

                         ?>

                      <tr style="background-color:#a9dfbf;">
                      <td><?php echo $array['diamond_lot_no'];?></td>
                      <td><?=$office_array['office_name']?></td>
                      <td><?=$shape_array['shape']?></td>
                      <td><?=$array['diamond_size']?></td>
                      <td><?=$lab_array['lab']?></td>
                      <td><?=$array['diamond_cert']?></td>
                      <td><?=$clr_array['clr']?></td>
                      <td><?=$cla_array['cla']?></td>
                      <td><?=$flr_array['flr']?></td>
                      <td><?=$fcut_array['fcut']?></td>
                      <td><?=$pol_array['pol']?></td>
                      <td><?=$sym_array['sym']?></td>
                      <td><?=$array['diamond_ins']?></td>
                      <td><?=$array['diamond_ains']?></td>
                      <td><?=$array['diamond_meas1']?> X <?=$array['diamond_meas2']?> X <?=$array['diamond_meas3']?></td>
                      <td><?="$".round($array['diamond_price_rapnet'],2)?></td>
                      <td><?=round($array['diamond_discount'],2). "%" ?></td>
                      <td><?="$".round($array['diamond_price_perct'],2)?></td>
                      <td><?="$".round($array['diamond_price_total'],2)?></td>
                      <td><?=round($array['diamond_discount_revaluated'],2)."%" ?></td>
                      <td><?="$".round($array['diamond_price_perct_revaluated'],2)?></td>
                      <td><?="$".round($array['diamond_price_total_revaluated'],2)?></td>
                      <td><?="$".round($array['diamond_price_rapnet_final'],2)?></td>
                      <td><?=round($array['diamond_discount_final'],2)."%" ?></td>
                      <td><?="$".round($array['diamond_price_perct_final'],2)?></td>
                      <td><?="$".round($array['diamond_price_total_final'],2)?></td>

                        <td><?=round($array['diamond_discount_sell'],2)."%" ?></td>
                      <td><?="$".round($array['diamond_price_perct_sell'],2)?></td>
                    <td><?="$".round($array['diamond_price_sell'],2)?></td>
                      <td><?=$array['diamond_status']?></td>
                      <td><?=$company_array['company_name']?></td>
                      <td><?=$array['diamond_status_front']?></td>
                      <td><?=$array['diamond_show_rapnet']?></td>
                      <td><?=date('d/m/Y',strtotime($array['diamond_purchase_date'])) ?></td>
                      <td><?=$array['diamond_party_name']?></td>
                      <td><?=$array['diamond_approval_no']?></td>
                      <td></td>
                    </tr>
                  <?php } ?>
        <?php } ?>
      </tbody>
    </table>
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

           <?php echo 'Cool, that only took ' . (microtime(true) - $started_at) ?>
  </body>

</html>
