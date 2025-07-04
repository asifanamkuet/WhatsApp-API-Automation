import sys
import os
import time
import base64
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def send_whatsapp_message(target_name, message, delay_seconds=0):
    profile_path = os.path.join(os.getcwd(), "whatsapp_profile")

    options = Options()
    options.add_argument(f"user-data-dir={profile_path}")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")
    options.add_argument("--lang=en-US.UTF-8")  # Ensure UTF-8 support
    # options.add_argument("--headless=new")  # Optional headless mode

    driver = webdriver.Chrome(options=options)
    wait = WebDriverWait(driver, 30)

    try:
        driver.get("https://web.whatsapp.com")
        wait.until(EC.presence_of_element_located((By.XPATH, '//div[@contenteditable="true"][@data-tab="3"]')))

        if delay_seconds > 0:
            time.sleep(delay_seconds)

        # Search contact
        search_box = driver.find_element(By.XPATH, '//div[@contenteditable="true"][@data-tab="3"]')
        search_box.click()
        search_box.clear()
        search_box.send_keys(target_name)
        time.sleep(5)

        wait.until(EC.presence_of_element_located((By.XPATH, f'//span[@title="{target_name}"]')))
        contact = driver.find_element(By.XPATH, f'//span[@title="{target_name}"]')
        contact.click()
        time.sleep(3)

        # Message input
        msg_box = wait.until(EC.presence_of_element_located((By.XPATH, '//div[@contenteditable="true"][@data-tab="10"]')))
        msg_box.click()

        # Send message with newlines and emojis
        for line in message.split('\n'):
            msg_box.send_keys(line)
            msg_box.send_keys(Keys.SHIFT, Keys.ENTER)

        msg_box.send_keys(Keys.ENTER)
        time.sleep(5)
        print("Message sent.")

    except Exception as e:
        print("Error:", str(e))

    finally:
        driver.quit()


if __name__ == "__main__":
    if len(sys.argv) >= 3:
        target = sys.argv[1]
        base64_msg = sys.argv[2]

        try:
            decoded_bytes = base64.b64decode(base64_msg)
            msg = decoded_bytes.decode('utf-8')  # Ensure emojis are decoded properly
        except Exception as e:
            print("Base64 decode error:", str(e))
            sys.exit(1)

        send_whatsapp_message(target, msg)
    else:
        print("Missing arguments: target_name and base64_encoded_message.")
