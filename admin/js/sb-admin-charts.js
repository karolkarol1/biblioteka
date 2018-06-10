// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
// -- Area Chart Example
var ctx2 = document.getElementById("myAreaChart2");

var dane;
var dane2;

$.ajaxSetup({
async: false
});

$.getJSON(
    'http://localhost/biblioteka/admin/statystyki.php', 
    function(data) { dane=data; }
);

$.getJSON(
    'http://localhost/biblioteka/admin/statystyki2.php', 
    function(data) { dane2=data; }
);

var maxx=Math.max.apply(Math,dane[1]);

maxx=Math.floor((maxx+999)/1000)*1000;


var maxx2=Math.max.apply(Math,dane2[1]);

maxx2=Math.floor((maxx2+999)/1000)*1000;


// -- area chart2


$.getJSON(
    'http://localhost/biblioteka/admin/statystyki3.php', 
    function(data) { dane3=data; }
);


var maxx3=Math.max.apply(Math,dane3[1]);

maxx3=Math.floor((maxx3+9)/10)*10;


var myLineChart2 = new Chart(ctx2, {
  type: 'line',
  data: {
    labels: dane3[0],
    datasets: [{
      label: "Ilość",
      lineTension: 0.3,
      backgroundColor: "rgba(2,117,216,0.2)",
      borderColor: "rgba(2,117,216,1)",
      pointRadius: 5,
      pointBackgroundColor: "rgba(2,117,216,1)",
      pointBorderColor: "rgba(255,255,255,0.8)",
      pointHoverRadius: 5,
      pointHoverBackgroundColor: "rgba(2,117,216,1)",
      pointHitRadius: 20,
      pointBorderWidth: 2,
      data: dane3[1],
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: maxx3,
          maxTicksLimit: 5
        },
        gridLines: {
          color: "rgba(0, 0, 0, .125)",
        }
      }],
    },
    legend: {
      display: false
    }
  }
});





// -- Bar Chart Example
var ctx = document.getElementById("myBarChart");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: dane2[0],
    datasets: [{
      label: "Revenue",
      backgroundColor: "rgba(2,117,216,1)",
      borderColor: "rgba(2,117,216,1)",
      data: dane2[1],
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'month'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 6
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: maxx2,
          maxTicksLimit: 5
        },
        gridLines: {
          display: true
        }
      }],
    },
    legend: {
      display: false
    }
  }
});



$.getJSON(
    'http://localhost/biblioteka/admin/statystyki4.php', 
    function(data) { dane4=data; }
);


// -- Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: dane4[0],
    datasets: [{
      data: dane4[1],
      backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745'],
    }],
  },
});
