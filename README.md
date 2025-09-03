# AstraKart – E-Commerce Web Application

AstraKart is a simple yet powerful e-commerce web application built using **PHP and MySQL**.  
It allows customers to browse products, place orders, and manage their order history.  
The project is designed for learning full-stack development and implementing core e-commerce features.

---

## 📌 Features

- **User Authentication**  
  Secure login and registration system with PHP sessions.

- **Product Management**  
  Users can view product listings and detailed descriptions.

- **Cart & Checkout**  
  Add products to cart, update quantity, and proceed to checkout.

- **Order History**  
  - Displays all past orders with details.  
  - Randomly generated **order status** (Pending, Processing, Shipped, Delivered).  
  - Randomly assigned **payment methods** (Cash on Delivery, UPI, Credit/Debit Card, Net Banking).

- **Responsive Design**  
  Mobile-friendly tables and layouts for smooth browsing.

- **Admin Ready**  
  Easily extendable for admin features like product upload, order management, and reporting.

---

## 🛠️ Tech Stack

- **Frontend:** HTML5, CSS3, JavaScript  
- **Backend:** PHP (Core PHP)  
- **Database:** MySQL  
- **Version Control:** Git & GitHub

---

---

## 💻 Installation & Setup

Follow these steps to run AstraKart on your local machine:

1. **Clone the repository**
   ```bash
   git clone https://github.com/toshak10243/AstraKart.git
   cd AstraKart
Setup database

Import the provided SQL file (if available) into MySQL.

Create database astrakart and required tables (users, products, orders, order_items).

Configure database connection
Open includes/db.php and update your local database credentials:
$conn = new mysqli("localhost", "root", "", "astrakart");

Run the project
Place the project inside your server’s root directory (e.g., htdocs for XAMPP or www for WAMP).
Start Apache & MySQL, then open:
http://localhost/AstraKart

🧪 Demo Flow

Register a new user or login with existing credentials.

Browse products on the homepage.

Add products to the cart and checkout.

Go to My Orders to see:

Randomly generated order statuses (Pending → Processing → Shipped → Delivered).

Random payment method assigned to each order.

🔮 Future Enhancements

Admin Dashboard for product & order management.

Search & Filters for products.

Payment Gateway Integration (Razorpay / PayPal).

Email Notifications for order confirmation & status updates.

Wishlist & Reviews to improve customer experience.

👨‍💻 Author

Toshak Sharma
📍 MCA in Artificial Intelligence & Machine Learning (JECRC University)
Passionate about software engineering, web development, and AI/ML projects.

GitHub: @toshak10243

📜 License

This project is licensed under the MIT License – feel free to use and modify it for learning purposes.

