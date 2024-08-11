import time
import ccxt
import re
from datetime import datetime, timedelta
import numpy as np
import praw
from sklearn.tree import DecisionTreeClassifier
import random
import os
import shutil
import pandas as pd
from ta.utils import dropna
from ta.volatility import BollingerBands
from ta.momentum import RSIIndicator
from ta.trend import MACD, MassIndex, CCIIndicator


exchange = ccxt.huobipro()




coin_names = {
    "BTC": "Bitcoin",
    "ETH": "Ethereum",
    "BNB": "Binance Coin",
    "XRP": "Ripple",
    "DOGE": "Dogecoin",
    "ADA": "Cardano",
    "SOL": "Solana",
    "TRX": "TRON",
    "MATIC": "Polygon",
    "SHIB": "Shiba Inu"
}





def extract_comments(text):
    
    comment_blocks = re.findall(r'(Comment from \d+\.\d+\.\d+\.\d+ at '+
    '\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}:\n)(.+)', text)
    return comment_blocks


def clean_comments(comment_blocks):
    patterns_to_exclude = ['http', '<div>', '<?php']
    whitelist_websites = ['youtube', 'reddit', 'facebook', 'github']
    result = []

    for timestamp, comment in comment_blocks:
       
        if any(pattern in comment for pattern in patterns_to_exclude):
            continue

        
        has_whitelisted_website = any(website in comment for website in whitelist_websites)

       
        if not has_whitelisted_website:
            result.append((timestamp, comment))

    return result

def get_date(comment_block):
    
    date_str= re.search(r'\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}',comment_block[0]).group()
    return datetime.strptime(date_str, "%Y-%m-%d %H:%M:%S")

def sort_comments_by_date(comment_blocks):
    
    sorted_comments = sorted(comment_blocks, key=get_date, reverse=True)
    return sorted_comments



def create_table(analysis_rows):
    
    table_content=""
    for row in analysis_rows:
        table_content += f"""
        <tr>
        <th>{row[0]}</th>
        <td class="{row[1]}">{row[1]}</td>
        <td class="{row[2].lower()}">{row[2]}</td>
        <td class="{row[3].lower()}">{row[3]}</td>
        <td class="{row[4].lower()}">{row[4]}</td>
        <td class="{row[5].lower()}">{row[5]}</td>
        <td class="{row[6].lower()}">{row[6]}</td>
        <td class="{row[7].lower()}">{row[7]}</td>
        <td class="{row[8].lower()}">{row[8]}</td>
        <td class="{row[9].lower()}">{row[9]}</td>
        </tr>
        """
    return table_content

def copy_files(source_folder, destination_folder):
    if not os.path.exists(destination_folder):
        os.makedirs(destination_folder)

    for filename in os.listdir(source_folder):
        source_path = os.path.join(source_folder, filename)
        destination_path = os.path.join(destination_folder, filename)

        if os.path.isfile(source_path):
            shutil.copy(source_path, destination_path)
            
        
def construct_html_from_comments(sorted_comments):
    comments_html = "\n".join(['<div class="article"><p>'
    +get_date(comment).strftime("%Y-%m-%d %H:%M:%S")+'</p>'+'<p>' 
    + comment[1] 
    + '</p><p></p></div>' for comment in sorted_comments])
    return comments_html

def combine_final_html(html_file,signal_rows,prices):

        visitors = "<p>Online Users: "+users_online+"</p>"

        html_file = html_file.replace("<p>Online Users:</p>",visitors)
        html_file = combine_chart_data(html_file, signal_rows)
        html_file = put_bitcoin_price(html_file, prices)
        html_file = put_time_stamp(html_file)
        html_file = put_projection_price(html_file, prices)
        return html_file



def min_max_scale(value, min_scale, max_scale):
    if value > max_scale:
        value = max_scale
    elif value < min_scale:
        value = min_scale

    numerator = value - min_scale
    denominator = max_scale - min_scale
    
    if denominator == 0:
        
        result = 0.0
    else:
        result = numerator / denominator

    return result


def get_bollinger_band_signal(df):

    indicator_bb = BollingerBands(close=df['close'], window=20, window_dev=2)
    result  = indicator_bb.bollinger_lband()[100] - closing_prices[100]

    result = min_max_scale(result,-75 ,75)
    return result

def get_rsi(df):

    indicator_rsi=RSIIndicator(close=df['close'], window= 20)
    result = indicator_rsi.rsi()[100]

    result = min_max_scale(result, 25, 75)
    return result

def get_macd(df):

    indicator_macd = MACD(close=df['close'])
    result = indicator_macd.macd_diff()[100] 

    result =  min_max_scale(result, -1.0, 1.0)
    return result

def get_volatility(df):
    
    result = df['close'][100] / df['close'][99]

    result = min_max_scale(result,.9917,1.008)
    return result

def get_cross_over(df):

    ema_10 = np.mean(df['close'][90])
    ema_50 = np.mean(df['close'][50])

    result = ema_10/ema_50
    result = min_max_scale(result, .95, 1.05)

    return result

def get_mass_index(df):

    indicator_mass = MassIndex(high=df['high'],low=df['low'])
    result = indicator_mass.mass_index()[100] 

    result= min_max_scale(result, 20, 30)
    return result

def get_cci(df):

    indicator_cci = CCIIndicator(high=df['high'],low=df['low'],close=df['close'])
    result = indicator_cci.cci()[100]

    result = min_max_scale(result,-100, 100 )
    return result

def transform_signal(signal):

    result = "Hold"

    if signal > .7:
        result ="Sell"
    elif signal < .3:
        result= "Buy"
    
    return result

def combine_chart_data(html, signals):
    lst = ' = [.5,.5,.5,.5,.5,.5,.5];'
    chart_names = ['yBitcoin'
    , 'yEthereum'
    , 'yBinance'
    , 'yRipple'
    , 'yDogecoin'
    , 'yCardano'
    , 'ySolano'
    , 'yTron'
    , 'yPolygon'
    , 'yShiba']
    
    for chart_name, signal in zip(chart_names, signals):
        
        signal_str = [str(value) for value in signal]
        html = html.replace(chart_name + lst, chart_name + convert_signal(signal_str))
    
    return html


def put_projection_price(html, price):

    return html




def put_time_stamp(html):
    lst = 'const time = [1,2,3,4,5];'
    
    current_time = datetime.now()


    timestamps = []

    for i in range(20):
   
        timestamp = current_time - timedelta(hours=i + 1)
        timestamps.append(timestamp.strftime('%H:%M'))

    for i in range(101):
   
        timestamp = current_time - timedelta(hours=i - 1)
        timestamps.append(timestamp.strftime('%H:%M'))
        
    date_str = '= [' + ','.join(["'" + time + "'" for time in timestamps]) + '];'
    result = html.replace(lst, 'const time' + date_str)

    return result


def put_bitcoin_price(html, price):
    lst = ' = [.5,.5,.5,.5,.5,.5,.5];'
    const_btc = 'const btc'
    
    signal_str = [str(value) for value in price]
    html = html.replace(const_btc + lst, const_btc + convert_signal(signal_str))
    
    return html

def put_projection_price(html, prices):
    lst = ' = [,,,26000,26000,26000,26000];'
    const_projection = 'const projection'

    empty_list=create_empty(100)
    projection_price = generate_ou_projection(prices, .7)

    signal_str =empty_list + [str(value) for value in projection_price]
    html = html.replace(const_projection + lst, const_projection + convert_signal(signal_str))
    
    return html

def generate_ou_projection(bitcoin_prices, mean_reversion_speed, initial_price=-1, time_step=1.0):
  
    num_steps = 20
    volatility = np.std(bitcoin_prices)

    if initial_price == -1:
        initial_price = bitcoin_prices[-1]

    projection = np.zeros(num_steps)
    projection[0] = initial_price

    for t in range(1, num_steps):
        drift = mean_reversion_speed * (initial_price - projection[t - 1]) * time_step
        randomness = np.random.normal(0, volatility * np.sqrt(time_step) * 0.1)
        projection[t] = projection[t - 1] + drift + randomness

    return projection

def create_empty(num):
    empty_list = [''] * num  
    return empty_list

def convert_signal(signal):
    return '=[' + ','.join(signal) + '];'


while True:
    historical_data = {}  
    users_online = str(random.randint(256, 532))
    

    for coin_pair in coin_names:
        ohlcv = exchange.fetch_ohlcv(f"{coin_pair}/USDT", timeframe="1h", limit=101)  

        time_prices = np.array(ohlcv)[:, 0] 
        open_prices = np.array(ohlcv)[:, 1] 
        high_prices = np.array(ohlcv)[:, 2] 
        low_prices  = np.array(ohlcv)[:, 3]
        closing_prices = np.array(ohlcv)[:, 4]
        volumes = np.array(ohlcv)[:, 5]

        coin_name = coin_names[coin_pair]
        

        historical_data[coin_name] = {
            'time': time_prices,
            'open': open_prices,
            'high': high_prices,
            'low': low_prices,
            'closing_prices': closing_prices,
            'volumes': volumes,
           
        }
        print(coin_name," : ",closing_prices[-1])

        

    analysis_rows = []  
    signal_rows = []
    
    for coin_name, data in historical_data.items():

       
        open_prices = data['open']
        low = data['low']
        high  = data['high']
        closing_prices = data['closing_prices']
        volumes = data['volumes']
       
        df = pd.DataFrame({
        'open': open_prices,
        'high': high,
        'low': low,
        'close': closing_prices,
        'volume': volumes})

        try:
            bb_signal  = get_bollinger_band_signal(df)
            rsi_signal = get_rsi(df)
            macd_signal = get_macd(df)
            volatility_signal = get_volatility(df)
            crossover_signal = get_cross_over(df)
            mass_index_signal = get_mass_index(df)
            cci_signal = get_cci(df)
        
        


            signal = [
            bb_signal,
            rsi_signal,
            macd_signal,
            cci_signal,
            volatility_signal,
            mass_index_signal,
            crossover_signal
            ]

            print (signal)

            average = np.average(signal)


            analysis_row = (
                coin_name
                , closing_prices[-1]
                , transform_signal(cci_signal)
                , transform_signal(volatility_signal)
                , transform_signal(mass_index_signal)
                , transform_signal(crossover_signal)
                , transform_signal(bb_signal)
                , transform_signal(rsi_signal)
                , transform_signal(macd_signal)
                , transform_signal(average)
            )
            analysis_rows.append(analysis_row)
            signal_rows.append(signal)
        except:
            print ('Loading Error')
    
   
        

    with open("template.php", "r") as template_file:
            
        html_content = template_file.read()
            
        table_content = create_table(analysis_rows)
        html = html_content.replace("<!-- Repeat rows for other cryptocurrencies... -->",
        table_content)

        html = combine_final_html(html, signal_rows, closing_prices)
   

     
           

        with open("index.php", "w") as output_file:
            output_file.write(html)
            
        copy_files("/home/junkey/website","/home/admin/web/launsdorf.com/public_html")

            
    time.sleep(30)  