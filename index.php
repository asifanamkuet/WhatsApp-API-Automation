<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WhatsApp Message Sender</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #25D366;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 15px 0 5px;
        }

        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            background-color: #25D366;
            color: white;
            font-weight: bold;
            border: none;
            margin-top: 20px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1ebc59;
        }

        .status {
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Send WhatsApp Message</h2>
    <form id="messageForm">
        <label for="target_name">Contact Name</label>
        <input type="text" id="target_name" name="target_name" required>

        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5" required>Hello ❤️</textarea>

        <button type="submit">Send Message</button>

        <div class="status" id="status"></div>
    </form>
</div>

<script>
    const form = document.getElementById('messageForm');
    const statusDiv = document.getElementById('status');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        fetch('send_api.php', {
            method: 'POST',
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok) {
                statusDiv.textContent = 'Message sent successfully!';
                statusDiv.className = 'status success';
            } else {
                statusDiv.textContent = 'Error: ' + (data.error || 'Unknown error');
                statusDiv.className = 'status error';
            }
        })
        .catch(err => {
            statusDiv.textContent = 'Network error: ' + err;
            statusDiv.className = 'status error';
        });
    });
</script>

</body>
</html>
