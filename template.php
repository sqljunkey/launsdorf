<?php
$ip = $_SERVER['REMOTE_ADDR'];
$file = __DIR__ . 'visitors.txt';



$timestamp = date('Y-m-d H:i:s');

$data = "$timestamp - $ip\n";
file_put_contents($file, $data, FILE_APPEND);



  






if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    
    
    $commentFile = __DIR__ . '/comments.txt';
    $commentData = "Comment from $ip at $timestamp:\n$comment\n\n";
    $success=file_put_contents($commentFile, $commentData, FILE_APPEND);
    if($success){
        header('Location: index.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<head>
  <title>Launsdorf</title>

  <style>
     
    body {
      background-color: #111;
      color: white;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        
    textarea {
  width: 800px;
  height: 150px;
}
        .article {
            background-color: #222;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            text-wrap: balance;
            word-wrap: break-word;
        }

        h1 {
            color: #00cc44;
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
            text-wrap: balance;
        }

        strong {
            color: #00cc44;
        }

        h2 {
            color: #00cc44;
            font-size: 20px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        ul {
            list-style-type: disc;
            margin-left: 20px;
            margin-bottom: 20px;
        }

        li {
            font-size: 16px;
            line-height: 1.5;
        }

        a {
            color: #00cc44;
            text-decoration: none;
            font-weight: bold;
        }

    .header a {
      color: #00cc44;
      text-decoration: none;
      font-weight: bold;
    }

    .analysis-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .analysis-table th, .analysis-table td {
      border: none;
      padding: 12px;
      text-align: center;
      font-weight: bold;
      font-size: 14px;
    }

    .analysis-table th {
      background-color: #333;
      color: white;
    }

    .buy {
      color: #00cc44;
    }


    .sell {
      color: #ff4444;
    }

    .hold {
      color: #bbb;
    }
    
    h1 {
      text-align: center;
      padding-bottom: 20px;
    }
  </style>
  
</head>
<body>


<?php

$userAgent = $_SERVER['HTTP_USER_AGENT'];
$ipAddress = $_SERVER['REMOTE_ADDR'];


$ipAddressesToOmit = ['72.206.125.1', '10.0.0.2']; 


if (!in_array($ipAddress, $ipAddressesToOmit)) {
    
    $userInfo = "Visit Index.php User Agent: $userAgent\nIP Address: $ipAddress\n\n";

    
    $result = file_put_contents("visitors.txt", $userInfo, FILE_APPEND);
}
?>


  <div class="container">
    <div class="header">
      <h1>Launsdorf</h1>
      <div>
	    <img src="launsdorf5.png" alt="My Image">	  
        <a href="index.php">Home</a>

        <p>Online Users:</p>



    </div>

        <div class="article">
            <h1>Crypto Signals</h1>
            <p>Welcome to our website providing free trading signals for the top 10 cryptocurrencies! We update these signals every 10 minutes to help you stay informed about market movements.</p>

<p>Disclaimer:

Please remember that cryptocurrency trading carries inherent risks, and these signals are for informational purposes only. We do not guarantee profits, and it's essential to conduct your research and consider your risk tolerance before making any trading decisions.</p><p>

Due to abuse we removed the comment section. If you want features goto my github sqljunkey!</p>
        </div>
    <canvas id="myChart" style="width:100%;max-width:800px"></canvas>
    <canvas id="Projections" style="width:100%;max-width:800px"></canvas>
    <script>
   
   
    const xValues = [
       'Bollinger',
       'RSI',
       'MACD',
       'CCI',
       'Volatility',
       'Mass Index',
       'Crossover'  
    ];

    const yBitcoin = [.5,.5,.5,.5,.5,.5,.5];
    const yEthereum = [.5,.5,.5,.5,.5,.5,.5];
    const yBinance = [.5,.5,.5,.5,.5,.5,.5];
    const yRipple = [.5,.5,.5,.5,.5,.5,.5];
    const yDogecoin = [.5,.5,.5,.5,.5,.5,.5];
    const yCardano = [.5,.5,.5,.5,.5,.5,.5];
    const ySolano = [.5,.5,.5,.5,.5,.5,.5];
    const yTron = [.5,.5,.5,.5,.5,.5,.5];
    const yPolygon = [.5,.5,.5,.5,.5,.5,.5];
    const yShiba = [.5,.5,.5,.5,.5,.5,.5];


    new Chart("myChart", {
  type: "line",
  data: {
    labels: xValues,
    datasets: [{
      label: "Bitcoin", 
      data: yBitcoin,
      borderColor: "red",
      fill: false,
      tension: .5
    },{
      label: "Ethereum", 
      data: yEthereum,
      borderColor: "green",
      fill: false,
      tension: .5
    },{
      label: "Binance", 
      data: yBinance,
      borderColor: "pink",
      fill: false,
      tension: .5
    },{
      label: "Ripple", 
      data: yRipple,
      borderColor: "navy",
      fill: false,
      tension: .5
    },{
      label: "Dogecoin", 
      data: yDogecoin,
      borderColor: "orange",
      fill: false,
      tension: .5
    },{
      label: "Cardano", 
      data: yCardano,
      borderColor: "blue",
      fill: false,
      tension: .5
    },{
      label: "Solano", 
      data: ySolano,
      borderColor: "fuchsia",
      fill: false,
      tension: .5
    },{
      label: "Tron", 
      data: yBinance,
      borderColor: "purple",
      fill: false,
      tension: .5
    }, {
      label: "Polygon", 
      data: yPolygon,
      borderColor: "yellow",
      fill: false,
      tension: .5
    },{
      label: "Shiba", 
      data: yShiba,
      borderColor: "aqua",
      fill: false,
      tension: .5
    }]
  },
  options: {
    legend: {display: false}
  }
});

const time = [1,2,3,4,5];

const projection = [,,,26000,26000,26000,26000];
const btc = [.5,.5,.5,.5,.5,.5,.5];

new Chart("Projections", {
  type: "line",
  data: {
    labels: time,
    datasets: [{
      label: "Bitcoin Price", 
      data: btc,
      borderColor: "white",
      fill: false,
      tension: .1
    }, {
      label: "Bitcoin Projection", 
      data: projection,
      borderColor: "orange",
      fill: false,
      tension: .1
    }]
  },
  options: {
    legend: {display: false}
  }
});


    setTimeout(function() {
      location.reload();
    },60000); 
  </script>

    <table class="analysis-table">
      <thead>
        <tr>
          <th></th>
          <th>Price</th>
          <th>CCI Index</th>
          <th>Volatility</th>
          <th>Mass Index</th>
          <th>Crossover</th>
          <th>Bollinger</th>
          <th>RSI</th>
          <th>MACD</th>
          <th>Average</th>
          
		  
        </tr>
      </thead>
      <tbody>
        <tr>


        <!-- Repeat rows for other cryptocurrencies... -->
      </tbody>
    </table>


</body>
</html>
