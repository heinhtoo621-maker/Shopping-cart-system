<?php

/**
 * Interface ProductInterface
 * Defines the contract for an e-commerce product.
 */
interface ProductInterface {
    public function getId(): string;
    public function getName(): string;
    public function getPrice(): float;
}

/**
 * Class Product
 * Implements ProductInterface to model a real-world store item.
 */
class Product implements ProductInterface {
    private string $id;
    private string $name;
    private float $price;

    public function __construct(string $id, string $name, float $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }
}

/**
 * Class CartItem
 * Manages a product instance coupled with its selected quantity inside the cart.
 */
class CartItem {
    private ProductInterface $product;
    private int $quantity;

    public function __construct(ProductInterface $product, int $quantity) {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getProduct(): ProductInterface { return $this->product; }
    public function getQuantity(): int { return $this->quantity; }
    
    public function increaseQuantity(int $amount): void {
        $this->quantity += $amount;
    }

    public function getTotalPrice(): float {
        return $this->product->getPrice() * $this->quantity;
    }
}

/**
 * Interface DiscountStrategyInterface
 * Implements Strategy Pattern to isolate various discount logics.
 */
interface DiscountStrategyInterface {
    public function calculateDiscount(float $subtotal): float;
}

/**
 * Class PercentageDiscount
 * Applies a percentage-based discount to the order.
 */
class PercentageDiscount implements DiscountStrategyInterface {
    private float $percentage;

    public function __construct(float $percentage) {
        $this->percentage = $percentage;
    }

    public function calculateDiscount(float $subtotal): float {
        return ($subtotal * $this->percentage) / 100;
    }
}

/**
 * Class OrderProcessor
 * Handles the calculation of the final bill invoice.
 * Adheres to Dependency Inversion Principle by depending on abstractions.
 */
class OrderProcessor {
    private array $cartItems = [];
    private ?DiscountStrategyInterface $discountStrategy = null;

    public function addItem(ProductInterface $product, int $quantity = 1): void {
        $productId = $product->getId();
        if (isset($this->cartItems[$productId])) {
            $this->cartItems[$productId]->increaseQuantity($quantity);
        } else {
            $this->cartItems[$productId] = new CartItem($product, $quantity);
        }
    }

    public function setDiscountStrategy(DiscountStrategyInterface $strategy): void {
        $this->discountStrategy = $strategy;
    }

    public function generateInvoice(): array {
        $subtotal = 0;
        foreach ($this->cartItems as $item) {
            $subtotal += $item->getTotalPrice();
        }

        $discount = 0;
        if ($this->discountStrategy !== null) {
            $discount = $this->discountStrategy->calculateDiscount($subtotal);
        }

        $grandTotal = $subtotal - $discount;

        return [
            'subtotal'    => $subtotal,
            'discount'    => $discount,
            'grand_total' => $grandTotal
        ];
    }
}

// ==========================================
// Execution & Architecture Testing
// ==========================================

// 1. Initialize Products
$phone = new Product("PROD-001", "Smartphone Alpha", 450000);
$case  = new Product("PROD-002", "Silicon Clear Case", 15000);

// 2. Setup Order Processor and add items to the cart
$processor = new OrderProcessor();
$processor->addItem($phone, 1);
$processor->addItem($case, 2); // Subtotal: 450,000 + 30,000 = 480,000 MMK

// 3. Dynamic Strategy Injection (Apply 5% Seasonal Discount)
$seasonalPromo = new PercentageDiscount(5);
$processor->setDiscountStrategy($seasonalPromo);

// 4. Compile the Final Statement
$invoice = $processor->generateInvoice();

echo "--- 🛍️ E-Commerce Invoice Statement --- \n";
echo "Subtotal Amount  : " . $invoice['subtotal'] . " MMK\n";
echo "Discount Deduct  : " . $invoice['discount'] . " MMK\n";
echo "----------------------------------------\n";
echo "Grand Total Bill : " . $invoice['grand_total'] . " MMK\n";
