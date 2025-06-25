<?php
$page_title = "Return Policy";
$page_description = "Our return policy for pre-loved fashion items.";
include_once '../includes/header.php';
?>

<main class="main-content">
    <div class="container" style="max-width: 800px; margin: 0 auto; padding: 80px 20px;">
        <div class="section-header" style="margin-bottom: 40px; text-align: center;">
            <h1 class="section-title" style="font-size: 2.5rem; margin-bottom: 20px; color: #333; line-height: 1.3;">
                Return Policy
            </h1>
            <div class="divider" style="width: 80px; height: 3px; background: #333; margin: 0 auto 40px;"></div>
        </div>
        
        <div class="policy-content" style="background: #fff; padding: 50px; border-radius: 10px; box-shadow: 0 5px 25px rgba(0,0,0,0.05);">
            <div class="return-info" style="margin-bottom: 30px; text-align: center;">
                <div style="width: 80px; height: 80px; background: #f8f1e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                    <i class="fas fa-undo-alt" style="font-size: 2rem; color: #d4a76a;"></i>
                </div>
                <h2 style="color: #333; margin-bottom: 20px; font-size: 1.8rem;">Our Return Policy</h2>
                <p style="font-size: 1.2rem; line-height: 1.8; color: #555; margin-bottom: 25px;">
                    We want you to be completely satisfied with your pre-loved fashion finds. Please review our return policy below.
                </p>
            </div>
            
            <div class="return-details" style="background: #f9f9f9; padding: 30px; border-radius: 8px; margin-top: 30px;">
                <div class="policy-item" style="margin-bottom: 25px;">
                    <h3 style="color: #333; margin-bottom: 15px; font-size: 1.3rem; display: flex; align-items: center;">
                        <i class="fas fa-times-circle" style="color: #e74c3c; margin-right: 10px; font-size: 1.5rem;"></i>
                        All Sales Are Final
                    </h3>
                    <p style="color: #555; font-size: 1.1rem; line-height: 1.7; margin-left: 35px;">
                        Due to the unique nature of pre-loved items, all sales are final. We do not accept returns or exchanges unless the item arrives damaged or not as described.
                    </p>
                </div>
                
                <div class="policy-item" style="margin-bottom: 25px;">
                    <h3 style="color: #333; margin-bottom: 15px; font-size: 1.3rem; display: flex; align-items: center;">
                        <i class="fas fa-exclamation-triangle" style="color: #f39c12; margin-right: 10px; font-size: 1.5rem;"></i>
                        Damaged or Incorrect Items
                    </h3>
                    <p style="color: #555; font-size: 1.1rem; line-height: 1.7; margin-left: 35px;">
                        If your item arrives damaged or is not as described, please contact us within 48 hours of delivery with photos of the issue. We will work with you to resolve the matter.
                    </p>
                </div>
                
                <div class="policy-item">
                    <h3 style="color: #333; margin-bottom: 15px; font-size: 1.3rem; display: flex; align-items: center;">
                        <i class="fas fa-info-circle" style="color: #3498db; margin-right: 10px; font-size: 1.5rem;"></i>
                        Contact Us
                    </h3>
                    <p style="color: #555; font-size: 1.1rem; line-height: 1.7; margin-left: 35px; margin-bottom: 0;">
                        If you have any questions about our return policy, please don't hesitate to reach out to our customer service team.
                    </p>
                </div>
                
                <div style="margin-top: 40px; padding-top: 25px; border-top: 1px solid #eee; text-align: center;">
                    <p style="color: #666; margin-bottom: 15px; font-size: 1rem;">
                        Need assistance with your order?
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
