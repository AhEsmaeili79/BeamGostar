import random

def generate_captcha():
    """Generates a random numeric CAPTCHA."""
    num1 = random.randint(1, 9)
    num2 = random.randint(1, 9)
    return f"{num1} + {num2}", num1 + num2
