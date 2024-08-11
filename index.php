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

        <p>Online Users: 381</p>



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

    const yBitcoin=[0.262151935724299,0.38180883734523363,0.0,0.0,0.5543245261276339,0.5167450573561407,0.6066644085187972];
    const yEthereum=[0.29179068614435894,0.4555105869538518,0.0,0.10148289819645058,0.6124030298657036,0.579965399978532,0.6718142144161882];
    const yBinance=[0.4757743895375779,0.5580362068686512,0.0,0.0,0.7530228725932173,0.6496652676809085,0.9909213180901135];
    const yRipple=[0.4999948529870963,0.21956523884629264,0.4988851099333674,0.0,0.7048659456448989,0.4935576770307737,0.758638670652853];
    const yDogecoin=[0.4999907899732593,0.4239443460280606,0.49973360845773523,0.0,0.7590934119906647,0.7031217532778389,0.9985778334859954];
    const yCardano=[0.49999712630188514,0.2916757187775083,0.49946119575998194,0.0,0.4660217876025432,0.6047830834514876,0.7954274970100499];
    const ySolano=[0.5047864610049279,0.18106322423013949,0.14002139300533745,0.0,0.5798978004533882,0.5058808772383511,0.5258000860432217];
    const yTron=[0.4999989030424439,0.40188223440151133,0.4999501309432939,0.0,0.47192640063097935,0.4409286518576927,0.6422711418696009];
    const yPolygon=[0.49999859893561566,0.3082488137843161,0.4992049847140644,0.0,0.6164448682570841,0.5661366441834008,0.7353927726413205];
    const yShiba=[0.4999999998757104,0.27431598563406895,0.49999996554838655,0.0,0.5980506701376682,0.6289477853411682,0.8602305475504327];


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

const time= ['14:27','13:27','12:27','11:27','10:27','09:27','08:27','07:27','06:27','05:27','04:27','03:27','02:27','01:27','00:27','23:27','22:27','21:27','20:27','19:27','16:27','15:27','14:27','13:27','12:27','11:27','10:27','09:27','08:27','07:27','06:27','05:27','04:27','03:27','02:27','01:27','00:27','23:27','22:27','21:27','20:27','19:27','18:27','17:27','16:27','15:27','14:27','13:27','12:27','11:27','10:27','09:27','08:27','07:27','06:27','05:27','04:27','03:27','02:27','01:27','00:27','23:27','22:27','21:27','20:27','19:27','18:27','17:27','16:27','15:27','14:27','13:27','12:27','11:27','10:27','09:27','08:27','07:27','06:27','05:27','04:27','03:27','02:27','01:27','00:27','23:27','22:27','21:27','20:27','19:27','18:27','17:27','16:27','15:27','14:27','13:27','12:27','11:27','10:27','09:27','08:27','07:27','06:27','05:27','04:27','03:27','02:27','01:27','00:27','23:27','22:27','21:27','20:27','19:27','18:27','17:27','16:27','15:27','14:27','13:27','12:27'];

const projection=[,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,1.383e-05,1.3821506102003854e-05,1.3835041928805983e-05,1.386269383876638e-05,1.3785306084892335e-05,1.3806259686059907e-05,1.3804895618206797e-05,1.3882189579794292e-05,1.3846630056614924e-05,1.38247027922698e-05,1.3827590452204694e-05,1.3825342082361228e-05,1.3852753328575278e-05,1.3858990459860104e-05,1.3862847955194393e-05,1.3891530317611315e-05,1.3873479851326167e-05,1.3895767765085821e-05,1.3829565527731795e-05,1.3791252933973663e-05];
const btc=[1.365e-05,1.38e-05,1.367e-05,1.326e-05,1.329e-05,1.321e-05,1.315e-05,1.29e-05,1.301e-05,1.298e-05,1.294e-05,1.292e-05,1.285e-05,1.287e-05,1.328e-05,1.344e-05,1.329e-05,1.324e-05,1.331e-05,1.335e-05,1.326e-05,1.333e-05,1.332e-05,1.333e-05,1.329e-05,1.344e-05,1.323e-05,1.358e-05,1.37e-05,1.381e-05,1.37e-05,1.367e-05,1.366e-05,1.372e-05,1.404e-05,1.432e-05,1.43e-05,1.417e-05,1.414e-05,1.404e-05,1.412e-05,1.389e-05,1.4e-05,1.402e-05,1.411e-05,1.419e-05,1.404e-05,1.397e-05,1.4e-05,1.393e-05,1.388e-05,1.392e-05,1.378e-05,1.389e-05,1.392e-05,1.394e-05,1.399e-05,1.404e-05,1.405e-05,1.403e-05,1.402e-05,1.402e-05,1.4e-05,1.403e-05,1.399e-05,1.394e-05,1.396e-05,1.402e-05,1.406e-05,1.406e-05,1.409e-05,1.409e-05,1.41e-05,1.409e-05,1.398e-05,1.402e-05,1.4e-05,1.404e-05,1.405e-05,1.412e-05,1.409e-05,1.408e-05,1.415e-05,1.413e-05,1.409e-05,1.417e-05,1.425e-05,1.442e-05,1.447e-05,1.434e-05,1.438e-05,1.437e-05,1.44e-05,1.437e-05,1.428e-05,1.43e-05,1.41e-05,1.401e-05,1.393e-05,1.381e-05,1.383e-05];

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


        
        <tr>
        <th>Bitcoin</th>
        <td class="60398.73">60398.73</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        </tr>
        
        <tr>
        <th>Ethereum</th>
        <td class="2620.07">2620.07</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        </tr>
        
        <tr>
        <th>Binance Coin</th>
        <td class="522.92">522.92</td>
        <td class="buy">Buy</td>
        <td class="sell">Sell</td>
        <td class="hold">Hold</td>
        <td class="sell">Sell</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        </tr>
        
        <tr>
        <th>Ripple</th>
        <td class="0.56933">0.56933</td>
        <td class="buy">Buy</td>
        <td class="sell">Sell</td>
        <td class="hold">Hold</td>
        <td class="sell">Sell</td>
        <td class="hold">Hold</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        </tr>
        
        <tr>
        <th>Dogecoin</th>
        <td class="0.105258">0.105258</td>
        <td class="buy">Buy</td>
        <td class="sell">Sell</td>
        <td class="sell">Sell</td>
        <td class="sell">Sell</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        </tr>
        
        <tr>
        <th>Cardano</th>
        <td class="0.342164">0.342164</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="sell">Sell</td>
        <td class="hold">Hold</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        </tr>
        
        <tr>
        <th>Solana</th>
        <td class="149.2605">149.2605</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="buy">Buy</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        </tr>
        
        <tr>
        <th>TRON</th>
        <td class="0.128296">0.128296</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        </tr>
        
        <tr>
        <th>Polygon</th>
        <td class="0.419484">0.419484</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="sell">Sell</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        </tr>
        
        <tr>
        <th>Shiba Inu</th>
        <td class="1.383e-05">1.383e-05</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        <td class="sell">Sell</td>
        <td class="hold">Hold</td>
        <td class="buy">Buy</td>
        <td class="hold">Hold</td>
        <td class="hold">Hold</td>
        </tr>
        
      </tbody>
    </table>


</body>
</html>
