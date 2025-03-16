import tkinter as tk
import webbrowser

def open_login_page():
    # Open the browser with the specified URL
    webbrowser.open("http://localhost/23/login.php")

# Create the main window
root = tk.Tk()
root.title("My Application")

# Set the window size
root.geometry("300x200")

# Create a button that acts as the logo
logo_button = tk.Button(root, text="My Logo", command=open_login_page, font=("Helvetica", 16), width=20)
logo_button.pack(pady=50)  # Add some vertical Padding

# Display the application window
root.mainloop()