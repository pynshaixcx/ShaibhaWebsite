<?php
$page_title = "Contact Us";
$page_description = "Get in touch with ShaiBha for any questions about our pre-loved fashion collection.";
require_once '../includes/functions.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $subject = sanitizeInput($_POST['subject'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all required fields';
    } elseif (!validateEmail($email)) {
        $error = 'Please enter a valid email address';
    } else {
        // Save contact message
        $sql = "INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
        $result = executeQuery($sql, [$name, $email, $phone, $subject, $message]);
        
        if ($result) {
            $success = 'Thank you for your message! We\'ll get back to you soon.';
            // Clear form data
            $_POST = [];
        } else {
            $error = 'Sorry, there was an error sending your message. Please try again.';
        }
    }
}

include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Contact Header -->
    <section class="contact-header">
        <div class="container">
            <div class="contact-header-content">
                <h1 class="page-title">
                    <span class="word" data-delay="0">Get</span>
                    <span class="word" data-delay="200">In</span>
                    <span class="word" data-delay="400">Touch</span>
                </h1>
                <p class="page-subtitle">
                    <span class="word" data-delay="600">We'd</span>
                    <span class="word" data-delay="800">love</span>
                    <span class="word" data-delay="1000">to</span>
                    <span class="word" data-delay="1200">hear</span>
                    <span class="word" data-delay="1400">from</span>
                    <span class="word" data-delay="1600">you</span>
                </p>
                <div class="hero-description">
                    <p>Have questions about our products, need styling advice, or want to learn more about sustainable fashion? We're here to help!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="contact-content">
        <div class="container">
            <div class="contact-layout">
                <!-- Contact Form -->
                <div class="contact-form-section">
                    <div class="form-card">
                        <h2>Send us a message</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-error">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" class="contact-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Full Name *</label>
                                    <input type="text" id="name" name="name" required 
                                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                                           placeholder="Your full name">
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <input type="email" id="email" name="email" required 
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                           placeholder="your@email.com">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                                           placeholder="+91 9876543210">
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject">Subject *</label>
                                    <select id="subject" name="subject" required>
                                        <option value="">Select a subject</option>
                                        <option value="General Inquiry" <?php echo ($_POST['subject'] ?? '') === 'General Inquiry' ? 'selected' : ''; ?>>General Inquiry</option>
                                        <option value="Product Question" <?php echo ($_POST['subject'] ?? '') === 'Product Question' ? 'selected' : ''; ?>>Product Question</option>
                                        <option value="Order Support" <?php echo ($_POST['subject'] ?? '') === 'Order Support' ? 'selected' : ''; ?>>Order Support</option>
                                        <option value="Styling Advice" <?php echo ($_POST['subject'] ?? '') === 'Styling Advice' ? 'selected' : ''; ?>>Styling Advice</option>
                                        <option value="Partnership" <?php echo ($_POST['subject'] ?? '') === 'Partnership' ? 'selected' : ''; ?>>Partnership</option>
                                        <option value="Other" <?php echo ($_POST['subject'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Message *</label>
                                <textarea id="message" name="message" rows="6" required 
                                          placeholder="Tell us how we can help you..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary submit-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22,2 15,22 11,13 2,9 22,2"></polygon>
                                </svg>
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="contact-info-section">
                    <div class="info-card">
                        <h3>Contact Information</h3>
                        <div class="contact-methods">
                            <div class="contact-method">
                                <div class="method-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </div>
                                <div class="method-content">
                                    <h4>Email</h4>
                                    <p>hello@shaibha.com</p>
                                    <span>We'll respond within 24 hours</span>
                                </div>
                            </div>
                            
                            <div class="contact-method">
                                <div class="method-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </div>
                                <div class="method-content">
                                    <h4>Phone</h4>
                                    <p>+91 9876543210</p>
                                    <span>Mon-Sat, 10 AM - 7 PM</span>
                                </div>
                            </div>
                            
                            <div class="contact-method">
                                <div class="method-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div class="method-content">
                                    <h4>Address</h4>
                                    <p>123 Fashion Street<br>Mumbai, Maharashtra 400001</p>
                                    <span>Visit our showroom</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hours-card">
                        <h3>Business Hours</h3>
                        <div class="hours-list">
                            <div class="hours-item">
                                <span>Monday - Friday</span>
                                <span>10:00 AM - 7:00 PM</span>
                            </div>
                            <div class="hours-item">
                                <span>Saturday</span>
                                <span>10:00 AM - 6:00 PM</span>
                            </div>
                            <div class="hours-item">
                                <span>Sunday</span>
                                <span>Closed</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-card">
                        <h3>Follow Us</h3>
                        <p>Stay connected for the latest updates and sustainable fashion tips</p>
                        <div class="social-links">
                            <a href="#" class="social-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.221.085.402-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.846-1.378l-.774 2.978c-.287 1.102-1.043 2.482-1.588 3.322 1.190.365 2.458.570 3.773.570 6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2 class="section-title">
                <span class="title-line">Frequently Asked Questions</span>
            </h2>
            <div class="faq-grid">
                <div class="faq-item">
                    <h4>How do I know the condition of pre-loved items?</h4>
                    <p>Each item is carefully inspected and rated on our condition scale: Excellent, Very Good, Good, or Fair. We provide detailed descriptions and photos of any wear.</p>
                </div>
                <div class="faq-item">
                    <h4>What is your return policy?</h4>
                    <p>We offer a 7-day return policy for items in their original condition. Return shipping costs apply unless the item was misrepresented.</p>
                </div>
                <div class="faq-item">
                    <h4>Do you offer international shipping?</h4>
                    <p>Currently, we only ship within India. We're working on expanding our shipping options to serve international customers soon.</p>
                </div>
                <div class="faq-item">
                    <h4>How can I sell my items to ShaiBha?</h4>
                    <p>We're always looking for quality pre-loved pieces! Contact us with photos and details of your items, and our team will review them for potential inclusion.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Contact Page Styles */
.contact-header {
    padding: var(--spacing-3xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
}

.contact-header-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.contact-content {
    padding: var(--spacing-3xl) 0;
}

.contact-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: var(--spacing-3xl);
}

.form-card,
.info-card,
.hours-card,
.social-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-2xl);
    margin-bottom: var(--spacing-lg);
}

.form-card h2,
.info-card h3,
.hours-card h3,
.social-card h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
}

.alert {
    padding: var(--spacing-md);
    border-radius: var(--border-radius-md);
    margin-bottom: var(--spacing-lg);
}

.alert-error {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
}

.form-group {
    margin-bottom: var(--spacing-lg);
}

.form-group label {
    display: block;
    margin-bottom: var(--spacing-xs);
    font-weight: 600;
    color: var(--color-black);
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: var(--spacing-md);
    border: 1px solid var(--color-gray-300);
    border-radius: var(--border-radius-md);
    font-size: 1rem;
    transition: border-color var(--transition-fast);
    background: var(--color-white);
    font-family: var(--font-primary);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--color-black);
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

.submit-btn {
    width: 100%;
    justify-content: center;
}

.contact-methods {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.contact-method {
    display: flex;
    gap: var(--spacing-md);
    align-items: flex-start;
}

.method-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    background: var(--color-black);
    color: var(--color-white);
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
}

.method-content h4 {
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
}

.method-content p {
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
    font-weight: 600;
}

.method-content span {
    color: var(--color-gray-600);
    font-size: 0.9rem;
}

.hours-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.hours-item {
    display: flex;
    justify-content: space-between;
    padding: var(--spacing-sm) 0;
    border-bottom: 1px solid var(--color-gray-200);
}

.hours-item:last-child {
    border-bottom: none;
}

.social-card p {
    color: var(--color-gray-600);
    margin-bottom: var(--spacing-lg);
}

.social-links {
    display: flex;
    gap: var(--spacing-md);
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: var(--color-black);
    color: var(--color-white);
    border-radius: var(--border-radius-md);
    text-decoration: none;
    transition: all var(--transition-fast);
}

.social-link:hover {
    background: var(--color-gray-800);
    transform: translateY(-2px);
}

.faq-section {
    padding: var(--spacing-3xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-xl);
}

.faq-item {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
}

.faq-item h4 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.faq-item p {
    color: var(--color-gray-700);
    line-height: 1.6;
}

/* Responsive */
@media (max-width: 992px) {
    .contact-layout {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .contact-info-section {
        order: -1;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
}

@media (max-width: 768px) {
    .form-card,
    .info-card,
    .hours-card,
    .social-card {
        padding: var(--spacing-xl);
    }
    
    .contact-method {
        flex-direction: column;
        text-align: center;
    }
    
    .method-icon {
        align-self: center;
    }
}
</style>

<?php include_once '../includes/footer.php'; ?>