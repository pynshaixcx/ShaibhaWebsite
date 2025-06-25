<?php
$page_title = "Shipping Policy";
$page_description = "Our shipping policy for pre-loved fashion orders.";
include_once '../includes/header.php';
?>

<main class="main-content">
    <div class="container" style="max-width: 800px; margin: 0 auto; padding: 80px 20px;">
        <div class="section-header" style="margin-bottom: 40px; text-align: center;">
            <h1 class="section-title" style="font-size: 2.5rem; margin-bottom: 20px; color: #333; line-height: 1.3;">
                Shipping Policy
            </h1>
            <div class="divider" style="width: 80px; height: 3px; background: #333; margin: 0 auto 40px;"></div>
        </div>
        
        <div class="policy-content" style="background: #fff; padding: 50px; border-radius: 10px; box-shadow: 0 5px 25px rgba(0,0,0,0.05);">
            <div class="shipping-info" style="margin-bottom: 40px;">
                <p style="font-size: 1.2rem; line-height: 1.8; color: #555; margin-bottom: 25px; text-align: center;">
                    We currently offer shipping to Meghalaya only. All orders are processed within 1-2 business days and typically delivered within 3-5 business days.
                </p>
            </div>
            
            <div class="shipping-details" style="background: #f9f9f9; padding: 30px; border-radius: 8px; margin-top: 40px;">
                <h2 style="color: #333; margin-bottom: 25px; font-size: 1.5rem; text-align: center;">
                    Shipping Information
                </h2>
                <ul style="list-style-type: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 15px; padding-left: 35px; position: relative; color: #555; font-size: 1.1rem;">
                        <i class="fas fa-check-circle" style="color: #4CAF50; position: absolute; left: 0; top: 5px; font-size: 1.2rem;"></i>
                        Free shipping on all orders within Meghalaya
                    </li>
                    <li style="margin-bottom: 15px; padding-left: 35px; position: relative; color: #555; font-size: 1.1rem;">
                        <i class="fas fa-check-circle" style="color: #4CAF50; position: absolute; left: 0; top: 5px; font-size: 1.2rem;"></i>
                        Order tracking available for all shipments
                    </li>
                    <li style="margin-bottom: 15px; padding-left: 35px; position: relative; color: #555; font-size: 1.1rem;">
                        <i class="fas fa-check-circle" style="color: #4CAF50; position: absolute; left: 0; top: 5px; font-size: 1.2rem;"></i>
                        Contact us for any shipping inquiries or special requests
                    </li>
                </ul>
                
                <div style="margin-top: 40px; padding-top: 25px; border-top: 1px solid #eee; text-align: center;">
                    <p style="color: #666; margin-bottom: 15px; font-size: 1rem;">
                        Need help with your order?
                    </p>
                    <a href="/pages/contact.php" class="btn" style="display: inline-block; background: #333; color: #fff; padding: 12px 28px; border-radius: 4px; text-decoration: none; font-weight: 500; transition: all 0.3s ease;">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once '../includes/footer.php'; ?>