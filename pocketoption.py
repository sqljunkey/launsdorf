
import yfinance as yf
import time
from bs4 import BeautifulSoup

# Define the ticker symbol for the EUR/USD trading pair
ticker_symbol = "EURUSD=X"  # EUR/USD trading pair on Yahoo Finance
signal=""
# Create a Ticker object
ticker = yf.Ticker(ticker_symbol)
html_code = '''
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="refresh" content="3"> <!-- Refresh and redirect every 3 seconds -->
  <title>Launsdorf Pocket Option Signals</title>
  <style>
    /* Apply background and text color to body */
    body {
      background-color: #333333; /* Dark gray background */
      color: #ffffff; /* White text color */
      margin: 0; /* Remove default margin */
      font-family: Arial, sans-serif; /* Set font family */
    }

    /* Apply styles to header */
    .header {
      padding: 20px;
      text-align: center;
    }

    /* Apply styles to h1 in header */
    .header h1 {
      margin: 0;
      font-size: 24px;
    }

    /* Apply styles to signal element */
    .signal {
      padding: 2px;
      text-align: center;
      background-color: #444444; /* Slightly lighter gray */
    }
    .price {
      padding: 10px;
      text-align: center;
      background-color: #444444; /* Slightly lighter gray */
    }
    /* Apply styles to p in signal element */
    .signal p {
      margin: 0;
      font-size: 40px;
    }
    .price p {
      margin: 0;
      font-size: 18px;
    }    
  </style>
</head>
<body>
  <div class="header">
    <h1>Pocket Option 1 minute signal.</h1>
  </div>
  <div class="signal">
    <p>hold</p> 
  </div>
  <div class="price">
    <p>Scanning...</p> 
  </div> 
  <img src="winrate.jpg" alt="Image Description">  
</body>
</html>

'''
def calculate_ema(data, period):
    ema = []
    multiplier = 2 / (period + 1)
    for i in range(len(data)):
        if i < period:
            ema.append(None)
        elif i == period:
            ema.append(sum(data[i - period: i]) / period)
        else:
            ema.append((data[i] - ema[i - 1]) * multiplier + ema[i - 1])
    return ema
while True:
    signal_text = ""
    current_price = ticker.history(period="1d")["Close"][-1]

    try:
        # Fetch historical data for the last 3 periods (adjust period and interval as needed)
        historical_data = ticker.history(period="1d", interval="1m")
        # Fetch historical data for the last 50 periods (adjust period and interval as needed)
        historical_data2 = ticker.history(period="1d", interval="1m")

        if len(historical_data) >= 6:
            close_prices = historical_data2["Close"].tolist()
        
            # Calculate 9-period and 21-period EMAs
            ema9 = calculate_ema(close_prices, period=3)
            ema21 = calculate_ema(close_prices, period=4)

            # Check for EMA crossings
  
  
                
            # Get the close prices of the last three candles
            close_prices = historical_data["Close"].iloc[-3:]

            # Check if all close prices are increasing
            if all(close_prices[i] < close_prices[i + 1] for i in range(1)):
             if ema9[-2] < ema21[-2] and ema9[-1] > ema21[-1]:
              signal='buy'
         
   
            # Check if all close prices are decreasing
            elif all(close_prices[i] > close_prices[i + 1] for i in range(1)):
              signal='sell'

              
            else:
              signal='hold'
    except Exception as e:
        print("An error occurred:", e)
    soup = BeautifulSoup(html_code, 'html.parser')

# Update the signal element
    signal_element = soup.find(class_='signal').find('p')
    if signal=='sell':
     signal_element.string = f'Place:{signal} signal' # Replace with the actual signal
    if signal=='buy':
     signal_element.string = f'Place:{signal} signal'
    if signal=='hold':
     signal_element.string = f'Scanning...'     
# Update the price element
    price_element = soup.find(class_='price').find('p')
    price_element.string = f'EUR USD Price:{current_price}'  # Replace with the actual current price

# Print the modified HTML code
    with open('pocketoption.html', 'w') as file:
     file.write(soup.prettify())
           


    time.sleep(1)  # Wait for 1 second before fetching again
      