

import json
import time
from telethon.sync import TelegramClient
from telethon.errors.rpcerrorlist import ChatWriteForbiddenError
# Replace with your OpenAI API key

# Replace with your own values
api_id = '25165470'
api_hash = 'c8c93c9229d2c8c85cfe5149d177cbed'
phone_number = '+31686014964'
interval = 5  # Time interval between sending messages (in seconds)

# Load group IDs from the JSON file
with open('groups.json', 'r') as json_file:
    groups = json.load(json_file)

# Create a Telegram client
with TelegramClient(phone_number, api_id, api_hash) as client:
 while True:
    for group in groups:
        group_id = int(group['group_id'])
        title = group['title']

        generated_message = "Hello please visit launsdorf.com to have some fun chat!"
        try:

        # Send the generated message to the group
         client.send_message(group_id , generated_message)
         print(f"Message sent to group '{title}': {generated_message}")

        except ValueError:
         pass
        except ChatWriteForbiddenError:
            print(f"Could not send message to group '{title}': Write permission denied")
        # Wait for the specified interval before sending the next message
        time.sleep(interval)
    time.sleep(3000)