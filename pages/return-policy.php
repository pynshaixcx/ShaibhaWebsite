<?php
$page_title = "Return Policy";
$page_description = "Learn about our return and exchange policy for pre-loved fashion items.";
include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Return Policy Header -->
    <section class="policy-header">
        <div class="container">
            <div class="policy-header-content">
                <h1 class="page-title">Return & Exchange Policy</h1>
                <p class="page-subtitle">Your satisfaction is our priority</p>
                <div class="last-updated">
                    <p>Last updated: <?php echo date('F j, Y'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Return Policy Content -->
    <section class="policy-content">
        <div class="container">
            <div class="policy-layout">
                <!-- Table of Contents -->
                <aside class="policy-sidebar">
                    <div class="toc-card">
                        <h3>Quick Navigation</h3>
                        <ul class="toc-list">
                            <li><a href="#return-window">Return Window</a></li>
                            <li><a href="#return-conditions">Return Conditions</a></li>
                            <li><a href="#return-process">Return Process</a></li>
                            <li><a href="#refund-policy">Refund Policy</a></li>
                            <li><a href="#exchange-policy">Exchange Policy</a></li>
                            <li><a href="#non-returnable">Non-Returnable Items</a></li>
                            <li><a href="#return-shipping">Return Shipping</a></li>
                            <li><a href="#contact">Contact Us</a></li>
                        </ul>
                    </div>
                </aside>

                <!-- Policy Content -->
                <div class="policy-main">
                    <!-- Return Window -->
                    <section id="return-window" class="policy-section">
                        <h2>Return Window</h2>
                        <div class="return-window-info">
                            <div class="window-card">
                                <div class="window-icon">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12,6 12,12 16,14"></polyline>
                                    </svg>
                                </div>
                                <h3>7-Day Return Window</h3>
                                <p>You have 7 days from the date of delivery to initiate a return or exchange request.</p>
                            </div>
                        </div>
                        <div class="window-details">
                            <h4>Important Notes:</h4>
                            <ul>
                                <li>The 7-day period starts from the date of delivery, not the date of purchase</li>
                                <li>Return requests must be initiated within this timeframe</li>
                                <li>Items must be returned within 14 days of initiating the return request</li>
                                <li>Late returns may not be accepted at our discretion</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Return Conditions -->
                    <section id="return-conditions" class="policy-section">
                        <h2>Return Conditions</h2>
                        <div class="conditions-grid">
                            <div class="condition-card acceptable">
                                <div class="condition-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 12l2 2 4-4"></path>
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                </div>
                                <h3>Acceptable Returns</h3>
                                <ul>
                                    <li>Items in original condition</li>
                                    <li>Unworn and unwashed items</li>
                                    <li>Items with original tags attached</li>
                                    <li>Items in original packaging</li>
                                    <li>No signs of wear, damage, or alteration</li>
                                </ul>
                            </div>
                            <div class="condition-card not-acceptable">
                                <div class="condition-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="15" y1="9" x2="9" y2="15"></line>
                                        <line x1="9" y1="9" x2="15" y2="15"></line>
                                    </svg>
                                </div>
                                <h3>Not Acceptable</h3>
                                <ul>
                                    <li>Worn or washed items</li>
                                    <li>Items with removed tags</li>
                                    <li>Damaged or stained items</li>
                                    <li>Items with odors (perfume, smoke, etc.)</li>
                                    <li>Altered or tailored items</li>
                                </ul>
                            </div>
                        </div>
                        <div class="condition-note">
                            <p><strong>Special Note for Pre-loved Items:</strong> Since our items are pre-loved, we understand they may show minimal signs of previous wear. However, returns should be in the same condition as when delivered to you.</p>
                        </div>
                    </section>

                    <!-- Return Process -->
                    <section id="return-process" class="policy-section">
                        <h2>Return Process</h2>
                        <div class="process-timeline">
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <span>1</span>
                                </div>
                                <div class="timeline-content">
                                    <h4>Contact Us</h4>
                                    <p>Call us at +91 9876543210 or email hello@shaibha.com with your order number and reason for return.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <span>2</span>
                                </div>
                                <div class="timeline-content">
                                    <h4>Return Authorization</h4>
                                    <p>We'll provide you with a Return Authorization Number and return instructions.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <span>3</span>
                                </div>
                                <div class="timeline-content">
                                    <h4>Package Items</h4>
                                    <p>Carefully package the items in their original condition with all tags and packaging.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <span>4</span>
                                </div>
                                <div class="timeline-content">
                                    <h4>Ship Back</h4>
                                    <p>Send the package to our return address using the provided return label or your preferred courier.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <span>5</span>
                                </div>
                                <div class="timeline-content">
                                    <h4>Processing</h4>
                                    <p>Once we receive your return, we'll inspect the items and process your refund or exchange within 5-7 business days.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Refund Policy -->
                    <section id="refund-policy" class="policy-section">
                        <h2>Refund Policy</h2>
                        <div class="refund-info">
                            <div class="refund-methods">
                                <h3>Refund Methods:</h3>
                                <div class="refund-options">
                                    <div class="refund-option">
                                        <h4>Cash on Delivery Orders</h4>
                                        <p>Since COD orders are paid upon delivery, refunds will be processed via bank transfer to your provided account details.</p>
                                        <ul>
                                            <li>Provide bank account details</li>
                                            <li>Refund processed within 5-7 business days</li>
                                            <li>Bank transfer fees may apply</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="refund-timeline">
                                <h3>Refund Timeline:</h3>
                                <table class="info-table">
                                    <thead>
                                        <tr>
                                            <th>Step</th>
                                            <th>Timeline</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Return Received</td>
                                            <td>Day 1</td>
                                            <td>We receive and acknowledge your return</td>
                                        </tr>
                                        <tr>
                                            <td>Quality Check</td>
                                            <td>Day 1-3</td>
                                            <td>Items are inspected for return conditions</td>
                                        </tr>
                                        <tr>
                                            <td>Refund Approval</td>
                                            <td>Day 3-5</td>
                                            <td>Refund is approved and initiated</td>
                                        </tr>
                                        <tr>
                                            <td>Refund Processed</td>
                                            <td>Day 5-7</td>
                                            <td>Money is transferred to your account</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                    <!-- Exchange Policy -->
                    <section id="exchange-policy" class="policy-section">
                        <h2>Exchange Policy</h2>
                        <div class="exchange-info">
                            <div class="exchange-types">
                                <h3>Exchange Options:</h3>
                                <div class="exchange-cards">
                                    <div class="exchange-card">
                                        <h4>Size Exchange</h4>
                                        <p>Exchange for a different size of the same item (subject to availability).</p>
                                        <ul>
                                            <li>Same item, different size</li>
                                            <li>No price difference</li>
                                            <li>Subject to stock availability</li>
                                        </ul>
                                    </div>
                                    <div class="exchange-card">
                                        <h4>Item Exchange</h4>
                                        <p>Exchange for a different item of equal or lesser value.</p>
                                        <ul>
                                            <li>Different item, same or lower price</li>
                                            <li>Price difference refunded if applicable</li>
                                            <li>Additional payment required for higher-priced items</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="exchange-process">
                                <h3>Exchange Process:</h3>
                                <ol>
                                    <li>Contact us with your exchange request</li>
                                    <li>Check availability of desired item/size</li>
                                    <li>Return original item following return process</li>
                                    <li>New item shipped once return is processed</li>
                                    <li>Price adjustments made if applicable</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- Non-Returnable Items -->
                    <section id="non-returnable" class="policy-section">
                        <h2>Non-Returnable Items</h2>
                        <div class="non-returnable-info">
                            <div class="non-returnable-list">
                                <h3>The following items cannot be returned:</h3>
                                <div class="non-returnable-grid">
                                    <div class="non-returnable-item">
                                        <h4>Intimate Apparel</h4>
                                        <p>Undergarments, lingerie, and swimwear for hygiene reasons.</p>
                                    </div>
                                    <div class="non-returnable-item">
                                        <h4>Customized Items</h4>
                                        <p>Items that have been altered or customized at your request.</p>
                                    </div>
                                    <div class="non-returnable-item">
                                        <h4>Final Sale Items</h4>
                                        <p>Items marked as "Final Sale" or purchased during clearance events.</p>
                                    </div>
                                    <div class="non-returnable-item">
                                        <h4>Damaged Returns</h4>
                                        <p>Items returned in damaged condition or not meeting return criteria.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="hygiene-note">
                                <h4>Hygiene and Safety:</h4>
                                <p>For health and hygiene reasons, certain items cannot be returned once delivered. These items are clearly marked on the product page before purchase.</p>
                            </div>
                        </div>
                    </section>

                    <!-- Return Shipping -->
                    <section id="return-shipping" class="policy-section">
                        <h2>Return Shipping</h2>
                        <div class="shipping-info">
                            <div class="shipping-costs">
                                <h3>Shipping Costs:</h3>
                                <div class="shipping-scenarios">
                                    <div class="scenario-card">
                                        <h4>Customer Responsibility</h4>
                                        <p>You are responsible for return shipping costs in the following cases:</p>
                                        <ul>
                                            <li>Change of mind</li>
                                            <li>Size doesn't fit</li>
                                            <li>Color/style preference</li>
                                            <li>Ordering wrong item</li>
                                        </ul>
                                    </div>
                                    <div class="scenario-card">
                                        <h4>Our Responsibility</h4>
                                        <p>We cover return shipping costs when:</p>
                                        <ul>
                                            <li>Item received is defective</li>
                                            <li>Wrong item was sent</li>
                                            <li>Item significantly different from description</li>
                                            <li>Damage during shipping</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="shipping-instructions">
                                <h3>Return Shipping Instructions:</h3>
                                <ol>
                                    <li>Use original packaging when possible</li>
                                    <li>Include Return Authorization Number</li>
                                    <li>Use trackable shipping method</li>
                                    <li>Keep shipping receipt until refund is processed</li>
                                    <li>Ship to the address provided in return instructions</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- Contact -->
                    <section id="contact" class="policy-section">
                        <h2>Contact Us for Returns</h2>
                        <div class="contact-info">
                            <p>Need help with a return or have questions about our policy? We're here to assist you!</p>
                            <div class="contact-methods">
                                <div class="contact-method">
                                    <h4>Return Support</h4>
                                    <p>Phone: +91 9876543210</p>
                                    <p>Email: hello@shaibha.com</p>
                                    <p>Hours: Monday-Saturday, 10 AM - 7 PM</p>
                                </div>
                                <div class="contact-method">
                                    <h4>What to Include</h4>
                                    <p>When contacting us about a return, please have the following information ready:</p>
                                    <ul>
                                        <li>Order number</li>
                                        <li>Item(s) you wish to return</li>
                                        <li>Reason for return</li>
                                        <li>Photos if item is defective</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Return Policy Page Styles - Inherits from shipping-policy.php styles */
.return-window-info {
    margin-bottom: var(--spacing-lg);
}

.window-card {
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
    text-align: center;
    max-width: 400px;
    margin: 0 auto;
}

.window-icon {
    width: 60px;
    height: 60px;
    background: var(--color-black);
    color: var(--color-white);
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md);
}

.window-card h3 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
    font-size: 1.3rem;
}

.window-card p {
    color: var(--color-gray-600);
}

.window-details {
    background: var(--color-gray-100);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.window-details h4 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.window-details ul {
    list-style: none;
}

.window-details li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
}

.window-details li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--color-black);
    font-weight: bold;
}

.conditions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-xl);
    margin-bottom: var(--spacing-lg);
}

.condition-card {
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
    text-align: center;
}

.condition-card.acceptable {
    background: #d1fae5;
    border: 1px solid #a7f3d0;
}

.condition-card.not-acceptable {
    background: #fee2e2;
    border: 1px solid #fecaca;
}

.condition-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md);
}

.acceptable .condition-icon {
    background: #10b981;
    color: var(--color-white);
}

.not-acceptable .condition-icon {
    background: #ef4444;
    color: var(--color-white);
}

.condition-card h3 {
    margin-bottom: var(--spacing-md);
}

.acceptable h3 {
    color: #065f46;
}

.not-acceptable h3 {
    color: #7f1d1d;
}

.condition-card ul {
    list-style: none;
    text-align: left;
}

.condition-card li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    font-size: 0.9rem;
}

.acceptable li {
    color: #065f46;
}

.not-acceptable li {
    color: #7f1d1d;
}

.acceptable li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #10b981;
    font-weight: bold;
}

.not-acceptable li::before {
    content: '✗';
    position: absolute;
    left: 0;
    color: #ef4444;
    font-weight: bold;
}

.condition-note {
    background: #f0f9ff;
    border: 1px solid #0ea5e9;
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.condition-note p {
    color: #0369a1;
    margin: 0;
}

.refund-methods {
    margin-bottom: var(--spacing-xl);
}

.refund-options {
    display: grid;
    gap: var(--spacing-lg);
}

.refund-option {
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.refund-option h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.refund-option p {
    margin-bottom: var(--spacing-md);
    color: var(--color-gray-600);
}

.refund-option ul {
    list-style: none;
}

.refund-option li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
    font-size: 0.9rem;
}

.refund-option li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--color-black);
    font-weight: bold;
}

.exchange-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.exchange-card {
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.exchange-card h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.exchange-card p {
    margin-bottom: var(--spacing-md);
    color: var(--color-gray-600);
}

.exchange-card ul {
    list-style: none;
}

.exchange-card li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
    font-size: 0.9rem;
}

.exchange-card li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #10b981;
    font-weight: bold;
}

.exchange-process {
    background: var(--color-gray-100);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.exchange-process h3 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.exchange-process ol {
    padding-left: var(--spacing-lg);
}

.exchange-process li {
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
}

.non-returnable-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
}

.non-returnable-item {
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.non-returnable-item h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.non-returnable-item p {
    color: var(--color-gray-600);
    font-size: 0.9rem;
}

.hygiene-note {
    background: #fef3c7;
    border: 1px solid #f59e0b;
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.hygiene-note h4 {
    margin-bottom: var(--spacing-sm);
    color: #92400e;
}

.hygiene-note p {
    color: #92400e;
    margin: 0;
}

.shipping-scenarios {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.scenario-card {
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.scenario-card h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.scenario-card p {
    margin-bottom: var(--spacing-md);
    color: var(--color-gray-600);
}

.scenario-card ul {
    list-style: none;
}

.scenario-card li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
    font-size: 0.9rem;
}

.scenario-card li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--color-black);
    font-weight: bold;
}

.shipping-instructions {
    background: var(--color-gray-100);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.shipping-instructions h3 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.shipping-instructions ol {
    padding-left: var(--spacing-lg);
}

.shipping-instructions li {
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
}

/* Responsive */
@media (max-width: 992px) {
    .conditions-grid,
    .shipping-scenarios {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    .exchange-cards,
    .non-returnable-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
}
</style>

<?php include_once '../includes/footer.php'; ?>