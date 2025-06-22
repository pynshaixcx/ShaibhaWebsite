<?php
$page_title = "Shipping Policy";
$page_description = "Learn about our shipping options, delivery times, and policies for pre-loved fashion orders.";
include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Shipping Policy Header -->
    <section class="policy-header">
        <div class="container">
            <div class="policy-header-content">
                <h1 class="page-title">Shipping Policy</h1>
                <p class="page-subtitle">Everything you need to know about shipping and delivery</p>
                <div class="last-updated">
                    <p>Last updated: <?php echo date('F j, Y'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Shipping Policy Content -->
    <section class="policy-content">
        <div class="container">
            <div class="policy-layout">
                <!-- Table of Contents -->
                <aside class="policy-sidebar">
                    <div class="toc-card">
                        <h3>Quick Navigation</h3>
                        <ul class="toc-list">
                            <li><a href="#shipping-options">Shipping Options</a></li>
                            <li><a href="#delivery-times">Delivery Times</a></li>
                            <li><a href="#shipping-costs">Shipping Costs</a></li>
                            <li><a href="#delivery-areas">Delivery Areas</a></li>
                            <li><a href="#order-processing">Order Processing</a></li>
                            <li><a href="#tracking">Order Tracking</a></li>
                            <li><a href="#delivery-issues">Delivery Issues</a></li>
                            <li><a href="#contact">Contact Us</a></li>
                        </ul>
                    </div>
                </aside>

                <!-- Policy Content -->
                <div class="policy-main">
                    <!-- Shipping Options -->
                    <section id="shipping-options" class="policy-section">
                        <h2>Shipping Options</h2>
                        <div class="shipping-options-grid">
                            <div class="shipping-option">
                                <div class="option-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9,22 9,12 15,12 15,22"></polyline>
                                    </svg>
                                </div>
                                <h3>Cash on Delivery (COD)</h3>
                                <p>Our primary shipping method. Pay for your order when it's delivered to your doorstep.</p>
                                <ul>
                                    <li>No advance payment required</li>
                                    <li>Secure and convenient</li>
                                    <li>Available across India</li>
                                    <li>Maximum order value: ₹10,000</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Delivery Times -->
                    <section id="delivery-times" class="policy-section">
                        <h2>Delivery Times</h2>
                        <div class="delivery-times-table">
                            <table class="info-table">
                                <thead>
                                    <tr>
                                        <th>Location Type</th>
                                        <th>Delivery Time</th>
                                        <th>Coverage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Metro Cities</strong></td>
                                        <td>2-3 business days</td>
                                        <td>Mumbai, Delhi, Bangalore, Chennai, Kolkata, Hyderabad, Pune</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tier 1 Cities</strong></td>
                                        <td>3-4 business days</td>
                                        <td>Ahmedabad, Jaipur, Lucknow, Kanpur, Nagpur, Indore, Bhopal</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tier 2 Cities</strong></td>
                                        <td>4-5 business days</td>
                                        <td>Agra, Nashik, Faridabad, Meerut, Rajkot, Varanasi</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Other Areas</strong></td>
                                        <td>5-7 business days</td>
                                        <td>All other serviceable locations</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="delivery-note">
                            <p><strong>Note:</strong> Delivery times are estimated and may vary during peak seasons, festivals, or due to unforeseen circumstances. We'll keep you updated on any delays.</p>
                        </div>
                    </section>

                    <!-- Shipping Costs -->
                    <section id="shipping-costs" class="policy-section">
                        <h2>Shipping Costs</h2>
                        <div class="shipping-costs-info">
                            <div class="cost-card">
                                <div class="cost-icon">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 12l2 2 4-4"></path>
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                </div>
                                <h3>Free Shipping</h3>
                                <p>Orders above ₹1,999</p>
                                <span class="cost-amount">₹0</span>
                            </div>
                            <div class="cost-card">
                                <div class="cost-icon">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M16 3h5v5"></path>
                                        <path d="M8 3H3v5"></path>
                                        <path d="M12 22v-8.3a4 4 0 0 0-4-4H3"></path>
                                        <path d="M21 9.5V8a2 2 0 0 0-2-2h-7"></path>
                                    </svg>
                                </div>
                                <h3>Standard Shipping</h3>
                                <p>Orders below ₹1,999</p>
                                <span class="cost-amount">₹99</span>
                            </div>
                        </div>
                        <div class="shipping-tips">
                            <h4>Money-Saving Tips:</h4>
                            <ul>
                                <li>Add items to reach ₹1,999 for free shipping</li>
                                <li>Combine multiple items in one order</li>
                                <li>Check our featured collections for curated bundles</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Delivery Areas -->
                    <section id="delivery-areas" class="policy-section">
                        <h2>Delivery Areas</h2>
                        <div class="delivery-coverage">
                            <div class="coverage-info">
                                <h3>We Currently Deliver To:</h3>
                                <div class="coverage-grid">
                                    <div class="coverage-item">
                                        <h4>All Major Cities</h4>
                                        <p>Complete coverage in all metro and tier 1 cities across India</p>
                                    </div>
                                    <div class="coverage-item">
                                        <h4>Tier 2 & 3 Cities</h4>
                                        <p>Expanding coverage to smaller cities and towns</p>
                                    </div>
                                    <div class="coverage-item">
                                        <h4>Rural Areas</h4>
                                        <p>Limited coverage in rural areas with reliable postal services</p>
                                    </div>
                                </div>
                            </div>
                            <div class="coverage-check">
                                <h4>Check Delivery Availability</h4>
                                <p>Enter your pincode during checkout to confirm delivery availability and estimated delivery time for your location.</p>
                                <div class="pincode-note">
                                    <p><strong>Note:</strong> If your area is not currently serviceable, we're constantly expanding our delivery network. Please check back soon or contact us for updates.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Order Processing -->
                    <section id="order-processing" class="policy-section">
                        <h2>Order Processing</h2>
                        <div class="processing-timeline">
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <span>1</span>
                                </div>
                                <div class="timeline-content">
                                    <h4>Order Confirmation</h4>
                                    <p>We'll call you within 24 hours to confirm your order details and delivery address.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <span>2</span>
                                </div>
                                <div class="timeline-content">
                                    <h4>Quality Check</h4>
                                    <p>Each item undergoes a final quality inspection before packaging.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <span>3</span>
                                </div>
                                <div class="timeline-content">
                                    <h4>Secure Packaging</h4>
                                    <p>Items are carefully packaged to ensure they arrive in perfect condition.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <span>4</span>
                                </div>
                                <div class="timeline-content">
                                    <h4>Dispatch</h4>
                                    <p>Your order is handed over to our delivery partner with tracking information.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Order Tracking -->
                    <section id="tracking" class="policy-section">
                        <h2>Order Tracking</h2>
                        <div class="tracking-info">
                            <div class="tracking-methods">
                                <h3>How to Track Your Order:</h3>
                                <div class="tracking-options">
                                    <div class="tracking-option">
                                        <h4>SMS Updates</h4>
                                        <p>Receive automatic SMS updates on your order status and delivery progress.</p>
                                    </div>
                                    <div class="tracking-option">
                                        <h4>Phone Support</h4>
                                        <p>Call our customer service team for real-time order status updates.</p>
                                    </div>
                                    <div class="tracking-option">
                                        <h4>Order Confirmation</h4>
                                        <p>Use your order number to inquire about delivery status and estimated arrival time.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tracking-statuses">
                                <h3>Order Status Meanings:</h3>
                                <ul class="status-list">
                                    <li><strong>Confirmed:</strong> Your order has been verified and is being prepared</li>
                                    <li><strong>Processing:</strong> Items are being quality-checked and packaged</li>
                                    <li><strong>Shipped:</strong> Your order is on its way to you</li>
                                    <li><strong>Out for Delivery:</strong> Your order will be delivered today</li>
                                    <li><strong>Delivered:</strong> Your order has been successfully delivered</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Delivery Issues -->
                    <section id="delivery-issues" class="policy-section">
                        <h2>Delivery Issues</h2>
                        <div class="delivery-issues-info">
                            <div class="issue-category">
                                <h3>Common Issues & Solutions:</h3>
                                <div class="issues-grid">
                                    <div class="issue-card">
                                        <h4>Delayed Delivery</h4>
                                        <p>If your order is delayed beyond the estimated delivery time, we'll proactively contact you with updates and a revised delivery schedule.</p>
                                    </div>
                                    <div class="issue-card">
                                        <h4>Incorrect Address</h4>
                                        <p>Contact us immediately if you need to change the delivery address. Changes may be possible if the order hasn't been dispatched.</p>
                                    </div>
                                    <div class="issue-card">
                                        <h4>Failed Delivery Attempt</h4>
                                        <p>Our delivery partner will attempt delivery 2-3 times. Ensure someone is available to receive the package during business hours.</p>
                                    </div>
                                    <div class="issue-card">
                                        <h4>Damaged Package</h4>
                                        <p>Inspect your package upon delivery. If damaged, refuse the delivery and contact us immediately for a replacement.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="emergency-contact">
                                <h3>Need Immediate Help?</h3>
                                <p>For urgent delivery issues, contact our customer service team:</p>
                                <div class="contact-details">
                                    <p><strong>Phone:</strong> +91 9876543210</p>
                                    <p><strong>Email:</strong> hello@shaibha.com</p>
                                    <p><strong>Hours:</strong> Monday-Saturday, 10 AM - 7 PM</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Contact -->
                    <section id="contact" class="policy-section">
                        <h2>Contact Us</h2>
                        <div class="contact-info">
                            <p>Have questions about shipping or need help with your order? We're here to help!</p>
                            <div class="contact-methods">
                                <div class="contact-method">
                                    <h4>Customer Service</h4>
                                    <p>Phone: +91 9876543210</p>
                                    <p>Email: hello@shaibha.com</p>
                                    <p>Hours: Monday-Saturday, 10 AM - 7 PM</p>
                                </div>
                                <div class="contact-method">
                                    <h4>Shipping Inquiries</h4>
                                    <p>For specific shipping questions or to track your order, please have your order number ready when contacting us.</p>
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
/* Shipping Policy Page Styles */
.policy-header {
    padding: var(--spacing-2xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
    text-align: center;
}

.last-updated {
    margin-top: var(--spacing-md);
    color: var(--color-gray-600);
    font-size: 0.9rem;
}

.policy-content {
    padding: var(--spacing-3xl) 0;
}

.policy-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: var(--spacing-3xl);
}

.policy-sidebar {
    position: sticky;
    top: 100px;
    height: fit-content;
}

.toc-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
}

.toc-card h3 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.toc-list {
    list-style: none;
}

.toc-list li {
    margin-bottom: var(--spacing-xs);
}

.toc-list a {
    color: var(--color-gray-600);
    text-decoration: none;
    font-size: 0.9rem;
    transition: color var(--transition-fast);
}

.toc-list a:hover {
    color: var(--color-black);
}

.policy-main {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-2xl);
}

.policy-section {
    margin-bottom: var(--spacing-3xl);
    padding-bottom: var(--spacing-xl);
    border-bottom: 1px solid var(--color-gray-200);
}

.policy-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.policy-section h2 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
    font-size: 1.5rem;
}

.shipping-options-grid {
    display: grid;
    gap: var(--spacing-lg);
}

.shipping-option {
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-md);
}

.option-icon {
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

.shipping-option h3 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.shipping-option p {
    margin-bottom: var(--spacing-md);
    color: var(--color-gray-600);
}

.shipping-option ul {
    list-style: none;
}

.shipping-option li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
    font-size: 0.9rem;
}

.shipping-option li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #10b981;
    font-weight: bold;
}

.info-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--color-white);
    border-radius: var(--border-radius-md);
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: var(--spacing-lg);
}

.info-table th {
    background: var(--color-black);
    color: var(--color-white);
    padding: var(--spacing-md);
    text-align: left;
    font-weight: 600;
}

.info-table td {
    padding: var(--spacing-md);
    border-bottom: 1px solid var(--color-gray-200);
    color: var(--color-gray-700);
}

.info-table tr:last-child td {
    border-bottom: none;
}

.info-table tr:nth-child(even) {
    background: var(--color-gray-100);
}

.delivery-note {
    background: #f0f9ff;
    border: 1px solid #0ea5e9;
    border-radius: var(--border-radius-md);
    padding: var(--spacing-md);
    margin-top: var(--spacing-lg);
}

.delivery-note p {
    color: #0369a1;
    margin: 0;
}

.shipping-costs-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
}

.cost-card {
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
    text-align: center;
    transition: all var(--transition-medium);
}

.cost-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.cost-icon {
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

.cost-card h3 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.cost-card p {
    margin-bottom: var(--spacing-md);
    color: var(--color-gray-600);
}

.cost-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #10b981;
}

.shipping-tips {
    background: var(--color-gray-100);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.shipping-tips h4 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.shipping-tips ul {
    list-style: none;
}

.shipping-tips li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
}

.shipping-tips li::before {
    content: '💡';
    position: absolute;
    left: 0;
}

.coverage-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
}

.coverage-item {
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-md);
}

.coverage-item h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.coverage-item p {
    color: var(--color-gray-600);
    font-size: 0.9rem;
}

.coverage-check {
    background: var(--color-gray-100);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.coverage-check h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.pincode-note {
    margin-top: var(--spacing-md);
    padding: var(--spacing-sm);
    background: var(--color-white);
    border-radius: var(--border-radius-sm);
}

.processing-timeline {
    display: grid;
    gap: var(--spacing-lg);
}

.timeline-item {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-md);
}

.timeline-icon {
    width: 40px;
    height: 40px;
    background: var(--color-black);
    color: var(--color-white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    flex-shrink: 0;
}

.timeline-content h4 {
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
}

.timeline-content p {
    color: var(--color-gray-600);
}

.tracking-methods {
    margin-bottom: var(--spacing-xl);
}

.tracking-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-lg);
}

.tracking-option {
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-md);
}

.tracking-option h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.tracking-option p {
    color: var(--color-gray-600);
    font-size: 0.9rem;
}

.status-list {
    list-style: none;
}

.status-list li {
    margin-bottom: var(--spacing-sm);
    color: var(--color-gray-700);
}

.issues-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.issue-card {
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.issue-card h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.issue-card p {
    color: var(--color-gray-600);
    font-size: 0.9rem;
}

.emergency-contact {
    background: #fee2e2;
    border: 1px solid #fecaca;
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.emergency-contact h3 {
    margin-bottom: var(--spacing-sm);
    color: #dc2626;
}

.emergency-contact p {
    margin-bottom: var(--spacing-md);
    color: #7f1d1d;
}

.contact-details p {
    margin-bottom: var(--spacing-xs);
    color: #7f1d1d;
    font-weight: 600;
}

.contact-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
}

.contact-method {
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
}

.contact-method h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.contact-method p {
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-600);
}

/* Responsive */
@media (max-width: 992px) {
    .policy-layout {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .policy-sidebar {
        position: static;
        order: -1;
    }
    
    .shipping-costs-info,
    .coverage-grid,
    .tracking-options,
    .issues-grid,
    .contact-methods {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
}

@media (max-width: 768px) {
    .policy-main {
        padding: var(--spacing-lg);
    }
    
    .shipping-option {
        flex-direction: column;
        text-align: center;
    }
    
    .timeline-item {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<?php include_once '../includes/footer.php'; ?>