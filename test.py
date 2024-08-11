balance = 1000  # Example balance value

# Create the HTML content with the balance embedded
html_content = f"""
<!DOCTYPE html>
<html>
<head>
    <title>Balance Page</title>
</head>
<body>
    <h1>Your Balance</h1>
    <p>Your current balance is: ${balance}</p>
</body>
</html>
"""

# Write the HTML content to a file
with open('kucoin.html', 'w') as html_file:
    html_file.write(html_content)

print("HTML file generated with balance.")
