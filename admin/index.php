<?php
session_start();
if($_SESSION['status']<1){
exit();
}

require_once('header.php');
?>
      <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Statystyki</li>
      </ol>
      <!-- Area Chart Example-->

        
        
              <!-- Area Chart2 Example-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-area-chart"></i> Statystyki rezerwacji względem dni</div>
        <div class="card-body">
          <canvas id="myAreaChart2" width="100%" height="30"></canvas>
        </div>      </div>
        <div class="row">
        <div class="col-lg-8">
                  <!-- Example Bar Chart Card-->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-bar-chart"></i> Statystyki rezerwacji względem miesięcy</div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-8 my-auto">
                  <canvas id="myBarChart" width="100" height="50"></canvas>
                </div>
              </div>
            </div>          </div>
        </div>
                <div class="col-lg-4">
                  <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-pie-chart"></i> Statystyki rezerwacji względem kategorii z ostatniego miesiąca</div>
            <div class="card-body">
              <canvas id="myPieChart" width="100%" height="100"></canvas>
            </div>          </div>
        </div>
            </div>

    </div>  
<?php
require_once('header.php');
?>
    <!-- Custom scripts for this page-->
    <script src="js/sb-admin-datatables.min.js"></script>
    <script src="js/sb-admin-charts.js"></script>
</body>

</html>