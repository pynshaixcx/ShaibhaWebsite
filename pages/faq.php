<?php
$page_title = "Frequently Asked Questions";
$page_description = "Find answers to common questions about ShaiBha's pre-loved fashion and services.";
include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- FAQ Header -->
    <section class="faq-header">
        <div class="container">
            <div class="faq-header-content">
                <h1 class="page-title">
                    <span class="word" data-delay="0">Frequently</span>
                    <span class="word" data-delay="200">Asked</span>
                    <span class="word" data-delay="400">Questions</span>
                </h1>
                <p class="page-subtitle">
                    <span class="word" data-delay="600">Find</span>
                    <span class="word" data-delay="800">answers</span>
                    <span class="word" data-delay="1000">to</span>
                    <span class="word" data-delay="1200">your</span>
                    <span class="word" data-delay="1400">questions</span>
                </p>
                <div class="hero-description">
                    <p>Everything you need to know about shopping pre-loved fashion with ShaiBha.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Content -->
    <section class="faq-content">
        <div class="container">
            <!-- FAQ Categories -->
            <div class="faq-categories">
                <div class="category-tabs">
                    <button class="tab-btn active" onclick="showFAQCategory('general')">General</button>
                    <button class="tab-btn" onclick="showFAQCategory('ordering')">Ordering</button>
                    <button class="tab-btn" onclick="showFAQCategory('shipping')">Shipping</button>
                    <button class="tab-btn" onclick="showFAQCategory('returns')">Returns</button>
                    <button class="tab-btn" onclick="showFAQCategory('quality')">Quality</button>
                </div>

                <!-- General FAQs -->
                <div id="general" class="faq-category active">
                    <h2>General Questions</h2>
                    <div class="faq-list">
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>What is ShaiBha?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>ShaiBha is a curated marketplace for pre-loved fashion. We specialize in high-quality, authentic designer and branded clothing that has been carefully selected for its condition, style, and value. Our mission is to make sustainable fashion accessible while offering unique pieces at affordable prices.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>What does "pre-loved" mean?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Pre-loved refers to clothing that has been previously owned but is still in excellent condition. All our items are carefully inspected, authenticated, and graded based on their condition. We only accept pieces that meet our high standards for quality and wearability.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>How do you ensure authenticity?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Every item goes through our rigorous authentication process. Our team of experts examines materials, construction, hardware, and other brand-specific details. We also verify provenance when possible and only work with trusted sources.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>Are your prices negotiable?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Our prices are carefully set based on the item's condition, rarity, and market value. While we don't negotiate on individual items, we regularly offer sales and promotions. Sign up for our newsletter to be notified of special offers.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ordering FAQs -->
                <div id="ordering" class="faq-category">
                    <h2>Ordering & Payment</h2>
                    <div class="faq-list">
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>What payment methods do you accept?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Currently, we only accept Cash on Delivery (COD). This allows you to pay for your order when it's delivered to your doorstep. We're working on adding more payment options in the future.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>Is there a maximum order value for COD?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Yes, the maximum order value for Cash on Delivery is ₹10,000. This helps us manage risk and ensure smooth delivery operations.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>Can I modify or cancel my order?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>You can modify or cancel your order within 2 hours of placing it by calling our customer service. After this time, we begin processing your order and changes may not be possible.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>Do I need to create an account to shop?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>No, you can shop as a guest. However, creating an account allows you to track orders, save addresses, view order history, and receive personalized recommendations.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping FAQs -->
                <div id="shipping" class="faq-category">
                    <h2>Shipping & Delivery</h2>
                    <div class="faq-list">
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>How long does delivery take?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Delivery times vary by location: Metro cities (2-3 days), Tier 1 cities (3-4 days), Tier 2 cities (4-5 days), and other areas (5-7 days). These are business days and exclude weekends and holidays.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>Do you offer free shipping?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Yes! We offer free shipping on orders above ₹1,999. For orders below this amount, shipping costs ₹99.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>Can I track my order?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Yes, you'll receive SMS updates about your order status. You can also call our customer service team for real-time updates using your order number.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>What if I'm not available for delivery?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Our delivery partner will attempt delivery 2-3 times. If you're unavailable, they'll leave a note with contact information to reschedule delivery at your convenience.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Returns FAQs -->
                <div id="returns" class="faq-category">
                    <h2>Returns & Exchanges</h2>
                    <div class="faq-list">
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>What is your return policy?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>We offer a 7-day return policy from the date of delivery. Items must be in original condition, unworn, with tags attached. Return shipping costs apply unless the item was defective or incorrectly described.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>How do I initiate a return?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Contact our customer service at +91 9876543210 or email hello@shaibha.com with your order number and reason for return. We'll provide a Return Authorization Number and instructions.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>Can I exchange an item for a different size?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Yes, subject to availability. Size exchanges follow the same 7-day policy. Contact us to check if your desired size is available before initiating the exchange.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>How long do refunds take?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Once we receive and inspect your return, refunds are processed within 5-7 business days via bank transfer to your provided account details.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quality FAQs -->
                <div id="quality" class="faq-category">
                    <h2>Quality & Condition</h2>
                    <div class="faq-list">
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>How do you grade item conditions?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>We use four condition grades: Excellent (like new), Very Good (minimal wear), Good (light wear but well-maintained), and Fair (noticeable wear but still stylish). Each item is thoroughly inspected and accurately described.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>Are items cleaned before sale?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Yes, all items undergo professional cleaning and any necessary minor repairs before being listed. However, we recommend following the care instructions for ongoing maintenance.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>What if an item doesn't match the description?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>If an item significantly differs from our description, we'll cover return shipping and provide a full refund or exchange. We strive for accurate descriptions and welcome your feedback.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <h3>Do you provide care instructions?</h3>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Yes, each item includes care instructions. We also have a comprehensive care guide on our website with tips for different fabrics and materials to help you maintain your purchases.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="faq-contact">
                <h2>Still have questions?</h2>
                <p>Can't find what you're looking for? Our customer service team is here to help!</p>
                <div class="contact-options">
                    <div class="contact-option">
                        <div class="contact-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <div class="contact-details">
                            <h4>Call Us</h4>
                            <p>+91 9876543210</p>
                            <span>Mon-Sat, 10 AM - 7 PM</span>
                        </div>
                    </div>
                    <div class="contact-option">
                        <div class="contact-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <div class="contact-details">
                            <h4>Email Us</h4>
                            <p>hello@shaibha.com</p>
                            <span>We'll respond within 24 hours</span>
                        </div>
                    </div>
                </div>
                <a href="contact.php" class="btn btn-primary">Contact Us</a>
            </div>
        </div>
    </section>
</main>

<style>
/* FAQ Page Styles */
.faq-header {
    padding: var(--spacing-3xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
}

.faq-header-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.faq-content {
    padding: var(--spacing-3xl) 0;
}

.faq-categories {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    overflow: hidden;
    margin-bottom: var(--spacing-3xl);
}

.category-tabs {
    display: flex;
    border-bottom: 1px solid var(--color-gray-200);
    overflow-x: auto;
}

.tab-btn {
    flex: 1;
    padding: var(--spacing-lg);
    background: transparent;
    border: none;
    cursor: pointer;
    font-weight: 600;
    color: var(--color-gray-600);
    transition: all var(--transition-fast);
    white-space: nowrap;
}

.tab-btn.active,
.tab-btn:hover {
    background: var(--color-black);
    color: var(--color-white);
}

.faq-category {
    display: none;
    padding: var(--spacing-xl);
}

.faq-category.active {
    display: block;
}

.faq-category h2 {
    margin-bottom: var(--spacing-xl);
    color: var(--color-black);
    font-size: 1.5rem;
}

.faq-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.faq-item {
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-md);
    overflow: hidden;
    transition: all var(--transition-medium);
}

.faq-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-lg);
    cursor: pointer;
    background: var(--color-white);
    transition: background-color var(--transition-fast);
}

.faq-question:hover {
    background: var(--color-gray-100);
}

.faq-question h3 {
    margin: 0;
    color: var(--color-black);
    font-size: 1.1rem;
}

.faq-toggle {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--color-gray-600);
    transition: transform var(--transition-medium);
}

.faq-item.active .faq-toggle {
    transform: rotate(45deg);
}

.faq-answer {
    padding: 0 var(--spacing-lg);
    background: var(--color-gray-100);
    max-height: 0;
    overflow: hidden;
    transition: all var(--transition-medium);
}

.faq-item.active .faq-answer {
    padding: var(--spacing-lg);
    max-height: 500px;
}

.faq-answer p {
    color: var(--color-gray-700);
    line-height: 1.6;
    margin: 0;
}

.faq-contact {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-2xl);
    text-align: center;
}

.faq-contact h2 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.faq-contact p {
    color: var(--color-gray-600);
    margin-bottom: var(--spacing-xl);
}

.contact-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.contact-option {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-lg);
    background: var(--color-white);
    border-radius: var(--border-radius-md);
    border: 1px solid var(--color-gray-200);
}

.contact-icon {
    width: 48px;
    height: 48px;
    background: var(--color-black);
    color: var(--color-white);
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.contact-details h4 {
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
}

.contact-details p {
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
    font-weight: 600;
}

.contact-details span {
    color: var(--color-gray-600);
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .category-tabs {
        flex-direction: column;
    }
    
    .tab-btn {
        text-align: left;
    }
    
    .faq-category,
    .faq-contact {
        padding: var(--spacing-lg);
    }
    
    .contact-option {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
function showFAQCategory(categoryId) {
    // Hide all categories
    document.querySelectorAll('.faq-category').forEach(category => {
        category.classList.remove('active');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected category
    document.getElementById(categoryId).classList.add('active');
    event.target.classList.add('active');
}

function toggleFAQ(element) {
    const faqItem = element.parentElement;
    const isActive = faqItem.classList.contains('active');
    
    // Close all FAQ items
    document.querySelectorAll('.faq-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Open clicked item if it wasn't active
    if (!isActive) {
        faqItem.classList.add('active');
    }
}
</script>

<?php include_once '../includes/footer.php'; ?>