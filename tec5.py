import pyautogui
import time
import requests
import pyautogui
API_KEY = "SNZJZWK9OES4P4RR"

def get_eur_usd_price_alphavantage():
    url = f"https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=EUR&to_currency=USD&apikey={API_KEY}"
    response = requests.get(url)
    data = response.json()

    if "Realtime Currency Exchange Rate" in data:
        eur_usd_price = float(data["Realtime Currency Exchange Rate"]["5. Exchange Rate"])
        return eur_usd_price
    else:
        print("Error fetching data from Alpha Vantage:", data.get("Error Message", "Unknown error"))
        return None

def get_eur_usd_price_cryptocompare():
    url = f"https://min-api.cryptocompare.com/data/price?fsym=EUR&tsyms=USD&api_key={API_KEY}"
    response = requests.get(url)
    data = response.json()

    if "USD" in data:
        eur_usd_price = data["USD"]
        return eur_usd_price
    else:
        print("Error fetching data from Cryptocompare:", data.get("Message", "Unknown error"))
        return None

def main():
    initial_price_alphavantage = get_eur_usd_price_alphavantage()
    initial_price_cryptocompare = get_eur_usd_price_cryptocompare()

    if initial_price_alphavantage is not None and initial_price_cryptocompare is not None:
        print(f"Initial prices (Alpha Vantage): {initial_price_alphavantage}")
        print(f"Initial prices (Cryptocompare): {initial_price_cryptocompare}")
    else:
        print("Failed to fetch initial prices.")

    while True:
        time.sleep(60)  # Sleep for 60 seconds

        current_price_alphavantage = get_eur_usd_price_alphavantage()
        current_price_cryptocompare = get_eur_usd_price_cryptocompare()

        if current_price_alphavantage is not None and current_price_cryptocompare is not None:
            if current_price_alphavantage > initial_price_alphavantage and current_price_cryptocompare > initial_price_cryptocompare:
                pyautogui.click(x=500, y=380)
            elif current_price_alphavantage < initial_price_alphavantage and current_price_cryptocompare < initial_price_cryptocompare:
                pyautogui.click(x=500, y=450)
        else:
            print("Failed to fetch current prices.")

if __name__ == "__main__":
    main()
