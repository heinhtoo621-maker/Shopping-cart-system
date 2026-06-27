# E-Commerce Shopping Cart System

A robust, object-oriented PHP shopping cart system built with Layered Architecture and SOLID design principles. This project demonstrates advanced backend concepts, including the Strategy Pattern for dynamic discount calculations.

## 🚀 Key Features
- **Interface-Driven Design**: Uses `ProductInterface` and `DiscountStrategyInterface` for loose coupling and scalability.
- **Dynamic Discount Strategies**: Implements the Strategy Pattern, allowing new discount types (e.g., Seasonal Promo, Coupon Codes) to be added without modifying core cart logic.
- **Cart Management**: Handles dynamic product quantities and individual subtotal calculations seamlessly.

## 🛠️ Tech Stack & Architecture
- **Language**: PHP 8.x
- **Architecture**: Layered / Object-Oriented Programming (OOP)
- **Core Concepts**: 
  - SOLID Principles (Single Responsibility, Dependency Inversion)
  - Strategy Design Pattern
  - Type Hinting & Encapsulation

## 💻 Output Example
When the script is executed, it compiles the cart items and injects a 5% discount strategy to generate this final invoice:

```text
--- 🛍️ E-Commerce Invoice Statement --- 
Subtotal Amount  : 480000 MMK
Discount Deduct  : 24000 MMK
----------------------------------------
Grand Total Bill : 456000 MMK
