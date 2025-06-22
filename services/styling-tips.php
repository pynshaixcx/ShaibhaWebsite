<?php
$page_title = "Styling Tips";
$page_description = "Discover expert styling tips to make the most of your pre-loved fashion pieces.";
include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Styling Tips Header -->
    <section class="styling-header">
        <div class="container">
            <div class="styling-header-content">
                <h1 class="page-title">
                    <span class="word" data-delay="0">Styling</span>
                    <span class="word" data-delay="200">Tips</span>
                </h1>
                <p class="page-subtitle">
                    <span class="word" data-delay="400">Create</span>
                    <span class="word" data-delay="600">stunning</span>
                    <span class="word" data-delay="800">looks</span>
                    <span class="word" data-delay="1000">with</span>
                    <span class="word" data-delay="1200">pre-loved</span>
                    <span class="word" data-delay="1400">pieces</span>
                </p>
                <div class="hero-description">
                    <p>Transform your wardrobe with expert styling advice. Learn how to mix, match, and style pre-loved pieces to create unique, fashionable looks that express your personal style.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Styling Tips Content -->
    <section class="styling-content">
        <div class="container">
            <!-- Essential Styling Principles -->
            <div class="principles-section">
                <h2 class="section-title">
                    <span class="title-line">Essential Styling Principles</span>
                </h2>
                <div class="principles-grid">
                    <div class="principle-card">
                        <div class="principle-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                        </div>
                        <h3>Balance & Proportion</h3>
                        <p>Create visual harmony by balancing fitted and loose pieces. If you're wearing a flowy top, pair it with fitted bottoms, and vice versa.</p>
                    </div>
                    <div class="principle-card">
                        <div class="principle-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                <path d="M2 17l10 5 10-5"></path>
                                <path d="M2 12l10 5 10-5"></path>
                            </svg>
                        </div>
                        <h3>Color Coordination</h3>
                        <p>Master the art of color mixing. Use the 60-30-10 rule: 60% dominant color, 30% secondary color, and 10% accent color for balanced looks.</p>
                    </div>
                    <div class="principle-card">
                        <div class="principle-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            </svg>
                        </div>
                        <h3>Texture & Pattern</h3>
                        <p>Mix textures and patterns thoughtfully. Combine smooth with textured, and if mixing patterns, vary the scale - pair large prints with small ones.</p>
                    </div>
                    <div class="principle-card">
                        <div class="principle-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <h3>Fit & Tailoring</h3>
                        <p>Proper fit is everything. Don't hesitate to tailor pre-loved pieces to achieve the perfect fit - it's often worth the investment.</p>
                    </div>
                </div>
            </div>

            <!-- Styling by Occasion -->
            <div class="occasion-styling-section">
                <h2 class="section-title">
                    <span class="title-line">Styling by Occasion</span>
                </h2>
                
                <div class="occasion-tabs">
                    <div class="tab-buttons">
                        <button class="tab-btn active" onclick="showOccasionTab('work')">Work</button>
                        <button class="tab-btn" onclick="showOccasionTab('casual')">Casual</button>
                        <button class="tab-btn" onclick="showOccasionTab('evening')">Evening</button>
                        <button class="tab-btn" onclick="showOccasionTab('weekend')">Weekend</button>
                        <button class="tab-btn" onclick="showOccasionTab('special')">Special Events</button>
                    </div>
                    
                    <div class="tab-content">
                        <div id="work" class="occasion-tab active">
                            <div class="styling-guide">
                                <h3>Professional & Work Styling</h3>
                                <div class="styling-tips">
                                    <div class="tip-section">
                                        <h4>Key Pieces</h4>
                                        <ul>
                                            <li>Well-fitted blazers in neutral colors</li>
                                            <li>Classic button-down shirts</li>
                                            <li>Tailored trousers or pencil skirts</li>
                                            <li>Quality leather shoes and accessories</li>
                                        </ul>
                                    </div>
                                    <div class="tip-section">
                                        <h4>Styling Tips</h4>
                                        <ul>
                                            <li>Invest in quality basics that mix and match</li>
                                            <li>Add personality with accessories like scarves or jewelry</li>
                                            <li>Ensure all pieces are well-pressed and in excellent condition</li>
                                            <li>Choose comfortable shoes you can wear all day</li>
                                        </ul>
                                    </div>
                                    <div class="outfit-examples">
                                        <h4>Outfit Ideas</h4>
                                        <div class="outfit-cards">
                                            <div class="outfit-card">
                                                <h5>Classic Professional</h5>
                                                <p>Navy blazer + white button-down + black trousers + pointed-toe flats</p>
                                            </div>
                                            <div class="outfit-card">
                                                <h5>Modern Chic</h5>
                                                <p>Structured dress + statement necklace + blazer + block heels</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="casual" class="occasion-tab">
                            <div class="styling-guide">
                                <h3>Casual Day Styling</h3>
                                <div class="styling-tips">
                                    <div class="tip-section">
                                        <h4>Key Pieces</h4>
                                        <ul>
                                            <li>Well-fitted jeans in classic washes</li>
                                            <li>Comfortable knit tops and sweaters</li>
                                            <li>Versatile cardigans and light jackets</li>
                                            <li>Comfortable sneakers or casual flats</li>
                                        </ul>
                                    </div>
                                    <div class="tip-section">
                                        <h4>Styling Tips</h4>
                                        <ul>
                                            <li>Layer pieces for versatility and interest</li>
                                            <li>Mix casual and slightly dressy pieces</li>
                                            <li>Use accessories to elevate simple outfits</li>
                                            <li>Focus on comfort without sacrificing style</li>
                                        </ul>
                                    </div>
                                    <div class="outfit-examples">
                                        <h4>Outfit Ideas</h4>
                                        <div class="outfit-cards">
                                            <div class="outfit-card">
                                                <h5>Effortless Chic</h5>
                                                <p>Dark jeans + striped tee + denim jacket + white sneakers</p>
                                            </div>
                                            <div class="outfit-card">
                                                <h5>Cozy Comfort</h5>
                                                <p>Leggings + oversized sweater + ankle boots + crossbody bag</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="evening" class="occasion-tab">
                            <div class="styling-guide">
                                <h3>Evening & Date Night Styling</h3>
                                <div class="styling-tips">
                                    <div class="tip-section">
                                        <h4>Key Pieces</h4>
                                        <ul>
                                            <li>Little black dress or elegant midi dress</li>
                                            <li>Statement jewelry and accessories</li>
                                            <li>Heels or dressy flats</li>
                                            <li>Clutch or small evening bag</li>
                                        </ul>
                                    </div>
                                    <div class="tip-section">
                                        <h4>Styling Tips</h4>
                                        <ul>
                                            <li>Choose one statement piece and keep the rest simple</li>
                                            <li>Pay attention to fabric quality and drape</li>
                                            <li>Consider the venue and dress code</li>
                                            <li>Don't forget about outerwear - a nice coat completes the look</li>
                                        </ul>
                                    </div>
                                    <div class="outfit-examples">
                                        <h4>Outfit Ideas</h4>
                                        <div class="outfit-cards">
                                            <div class="outfit-card">
                                                <h5>Classic Elegance</h5>
                                                <p>Black midi dress + pearl earrings + nude heels + clutch</p>
                                            </div>
                                            <div class="outfit-card">
                                                <h5>Modern Glamour</h5>
                                                <p>Silk blouse + high-waisted trousers + statement necklace + heels</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="weekend" class="occasion-tab">
                            <div class="styling-guide">
                                <h3>Weekend & Leisure Styling</h3>
                                <div class="styling-tips">
                                    <div class="tip-section">
                                        <h4>Key Pieces</h4>
                                        <ul>
                                            <li>Comfortable jeans or casual pants</li>
                                            <li>Soft knits and casual tops</li>
                                            <li>Comfortable shoes for walking</li>
                                            <li>Practical bags for carrying essentials</li>
                                        </ul>
                                    </div>
                                    <div class="tip-section">
                                        <h4>Styling Tips</h4>
                                        <ul>
                                            <li>Prioritize comfort and practicality</li>
                                            <li>Layer for changing weather conditions</li>
                                            <li>Choose pieces that can transition from day to evening</li>
                                            <li>Have fun with colors and patterns</li>
                                        </ul>
                                    </div>
                                    <div class="outfit-examples">
                                        <h4>Outfit Ideas</h4>
                                        <div class="outfit-cards">
                                            <div class="outfit-card">
                                                <h5>Brunch Ready</h5>
                                                <p>Midi skirt + graphic tee + denim jacket + sandals</p>
                                            </div>
                                            <div class="outfit-card">
                                                <h5>Shopping Day</h5>
                                                <p>Comfortable jeans + soft sweater + sneakers + tote bag</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="special" class="occasion-tab">
                            <div class="styling-guide">
                                <h3>Special Events Styling</h3>
                                <div class="styling-tips">
                                    <div class="tip-section">
                                        <h4>Key Pieces</h4>
                                        <ul>
                                            <li>Formal dresses or elegant separates</li>
                                            <li>Quality accessories and jewelry</li>
                                            <li>Appropriate footwear for the venue</li>
                                            <li>Evening coat or wrap</li>
                                        </ul>
                                    </div>
                                    <div class="tip-section">
                                        <h4>Styling Tips</h4>
                                        <ul>
                                            <li>Research the dress code and venue</li>
                                            <li>Choose timeless pieces over trendy ones</li>
                                            <li>Ensure everything fits perfectly</li>
                                            <li>Plan your entire look including undergarments</li>
                                        </ul>
                                    </div>
                                    <div class="outfit-examples">
                                        <h4>Outfit Ideas</h4>
                                        <div class="outfit-cards">
                                            <div class="outfit-card">
                                                <h5>Wedding Guest</h5>
                                                <p>Floral midi dress + nude heels + delicate jewelry + clutch</p>
                                            </div>
                                            <div class="outfit-card">
                                                <h5>Cocktail Party</h5>
                                                <p>Black cocktail dress + statement earrings + heels + wrap</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seasonal Styling -->
            <div class="seasonal-styling-section">
                <h2 class="section-title">
                    <span class="title-line">Seasonal Styling Guide</span>
                </h2>
                <div class="seasons-grid">
                    <div class="season-card">
                        <div class="season-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="5"></circle>
                                <line x1="12" y1="1" x2="12" y2="3"></line>
                                <line x1="12" y1="21" x2="12" y2="23"></line>
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                <line x1="1" y1="12" x2="3" y2="12"></line>
                                <line x1="21" y1="12" x2="23" y2="12"></line>
                                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                            </svg>
                        </div>
                        <h3>Spring/Summer</h3>
                        <ul>
                            <li>Light, breathable fabrics like cotton and linen</li>
                            <li>Bright colors and floral patterns</li>
                            <li>Layering with light cardigans and jackets</li>
                            <li>Comfortable sandals and breathable shoes</li>
                            <li>Sun hats and sunglasses as accessories</li>
                        </ul>
                    </div>
                    
                    <div class="season-card">
                        <div class="season-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 9V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v2"></path>
                                <path d="M9 22V12"></path>
                                <path d="M15 22V12"></path>
                                <path d="M20 15H4"></path>
                                <path d="M4 9h16"></path>
                            </svg>
                        </div>
                        <h3>Fall/Winter</h3>
                        <ul>
                            <li>Rich textures like wool, cashmere, and velvet</li>
                            <li>Deeper colors and earth tones</li>
                            <li>Strategic layering for warmth and style</li>
                            <li>Quality boots and closed-toe shoes</li>
                            <li>Scarves, gloves, and warm accessories</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Accessorizing Tips -->
            <div class="accessories-section">
                <h2 class="section-title">
                    <span class="title-line">The Art of Accessorizing</span>
                </h2>
                <div class="accessories-grid">
                    <div class="accessory-card">
                        <h3>Jewelry</h3>
                        <div class="accessory-tips">
                            <h4>Dos:</h4>
                            <ul>
                                <li>Mix metals for a modern look</li>
                                <li>Layer necklaces of different lengths</li>
                                <li>Choose one statement piece per outfit</li>
                                <li>Consider your neckline when choosing necklaces</li>
                            </ul>
                            <h4>Don'ts:</h4>
                            <ul>
                                <li>Over-accessorize - less is often more</li>
                                <li>Wear competing statement pieces</li>
                                <li>Ignore the scale of your features</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="accessory-card">
                        <h3>Bags</h3>
                        <div class="accessory-tips">
                            <h4>Dos:</h4>
                            <ul>
                                <li>Choose size proportional to your frame</li>
                                <li>Consider functionality for your lifestyle</li>
                                <li>Invest in quality leather that ages well</li>
                                <li>Match formality level to your outfit</li>
                            </ul>
                            <h4>Don'ts:</h4>
                            <ul>
                                <li>Carry oversized bags with delicate outfits</li>
                                <li>Ignore the color coordination</li>
                                <li>Choose style over comfort for daily use</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="accessory-card">
                        <h3>Scarves</h3>
                        <div class="accessory-tips">
                            <h4>Dos:</h4>
                            <ul>
                                <li>Use scarves to add color and pattern</li>
                                <li>Learn different tying techniques</li>
                                <li>Choose appropriate weight for season</li>
                                <li>Use as a belt or bag accessory</li>
                            </ul>
                            <h4>Don'ts:</h4>
                            <ul>
                                <li>Overwhelm small frames with large scarves</li>
                                <li>Ignore the fabric care requirements</li>
                                <li>Stick to only one wearing style</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="accessory-card">
                        <h3>Shoes</h3>
                        <div class="accessory-tips">
                            <h4>Dos:</h4>
                            <ul>
                                <li>Ensure proper fit and comfort</li>
                                <li>Match formality to your outfit</li>
                                <li>Consider the occasion and venue</li>
                                <li>Maintain and care for your shoes</li>
                            </ul>
                            <h4>Don'ts:</h4>
                            <ul>
                                <li>Sacrifice comfort for style</li>
                                <li>Ignore weather conditions</li>
                                <li>Wear damaged or dirty shoes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body Type Styling -->
            <div class="body-type-section">
                <h2 class="section-title">
                    <span class="title-line">Styling for Your Body Type</span>
                </h2>
                <div class="body-type-disclaimer">
                    <p><strong>Remember:</strong> These are guidelines, not rules. The most important thing is to wear what makes you feel confident and comfortable. Fashion should be fun and expressive!</p>
                </div>
                <div class="body-types-grid">
                    <div class="body-type-card">
                        <h3>Pear Shape</h3>
                        <p><strong>Characteristics:</strong> Hips wider than shoulders</p>
                        <div class="styling-suggestions">
                            <h4>Flattering Styles:</h4>
                            <ul>
                                <li>A-line and fit-and-flare dresses</li>
                                <li>Tops with interesting necklines or details</li>
                                <li>Bootcut or straight-leg pants</li>
                                <li>Jackets that hit at the hip</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="body-type-card">
                        <h3>Apple Shape</h3>
                        <p><strong>Characteristics:</strong> Fuller midsection, narrower hips</p>
                        <div class="styling-suggestions">
                            <h4>Flattering Styles:</h4>
                            <ul>
                                <li>Empire waist dresses and tops</li>
                                <li>V-necks and scoop necklines</li>
                                <li>Straight or bootcut pants</li>
                                <li>Jackets that don't cinch at the waist</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="body-type-card">
                        <h3>Hourglass Shape</h3>
                        <p><strong>Characteristics:</strong> Balanced shoulders and hips, defined waist</p>
                        <div class="styling-suggestions">
                            <h4>Flattering Styles:</h4>
                            <ul>
                                <li>Fitted dresses that show your waist</li>
                                <li>Wrap dresses and tops</li>
                                <li>High-waisted bottoms</li>
                                <li>Belted jackets and coats</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="body-type-card">
                        <h3>Rectangle Shape</h3>
                        <p><strong>Characteristics:</strong> Similar measurements throughout</p>
                        <div class="styling-suggestions">
                            <h4>Flattering Styles:</h4>
                            <ul>
                                <li>Dresses with defined waistlines</li>
                                <li>Layered looks to add dimension</li>
                                <li>Peplum tops and jackets</li>
                                <li>Wide-leg or flare pants</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Styling Tips Page Styles */
.styling-header {
    padding: var(--spacing-3xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
}

.styling-header-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.styling-content {
    padding: var(--spacing-3xl) 0;
}

.principles-section {
    margin-bottom: var(--spacing-3xl);
}

.principles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--spacing-xl);
}

.principle-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    text-align: center;
    transition: all var(--transition-medium);
}

.principle-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--glass-shadow);
}

.principle-icon {
    width: 80px;
    height: 80px;
    background: var(--color-black);
    color: var(--color-white);
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md);
}

.principle-card h3 {
    font-size: 1.3rem;
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.principle-card p {
    color: var(--color-gray-600);
    line-height: 1.6;
}

.occasion-styling-section {
    margin-bottom: var(--spacing-3xl);
}

.occasion-tabs {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    overflow: hidden;
}

.tab-buttons {
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

.tab-content {
    padding: var(--spacing-xl);
}

.occasion-tab {
    display: none;
}

.occasion-tab.active {
    display: block;
}

.styling-guide h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
    font-size: 1.5rem;
}

.styling-tips {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.tip-section h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
    font-size: 1.1rem;
}

.tip-section ul {
    list-style: none;
    padding-left: 0;
}

.tip-section li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
    line-height: 1.5;
}

.tip-section li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--color-black);
    font-weight: bold;
}

.outfit-examples {
    grid-column: 1 / -1;
}

.outfit-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-md);
}

.outfit-card {
    background: var(--color-gray-100);
    padding: var(--spacing-md);
    border-radius: var(--border-radius-md);
}

.outfit-card h5 {
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
}

.outfit-card p {
    color: var(--color-gray-700);
    font-size: 0.9rem;
}

.seasonal-styling-section {
    margin-bottom: var(--spacing-3xl);
}

.seasons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: var(--spacing-xl);
}

.season-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    text-align: center;
    transition: all var(--transition-medium);
}

.season-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--glass-shadow);
}

.season-icon {
    width: 80px;
    height: 80px;
    background: var(--color-black);
    color: var(--color-white);
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md);
}

.season-card h3 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.season-card ul {
    list-style: none;
    text-align: left;
}

.season-card li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
    line-height: 1.5;
}

.season-card li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #10b981;
    font-weight: bold;
}

.accessories-section {
    margin-bottom: var(--spacing-3xl);
}

.accessories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-xl);
}

.accessory-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    transition: all var(--transition-medium);
}

.accessory-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--glass-shadow);
}

.accessory-card h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
    text-align: center;
}

.accessory-tips h4 {
    margin: var(--spacing-md) 0 var(--spacing-sm);
    color: var(--color-black);
    font-size: 1rem;
}

.accessory-tips ul {
    list-style: none;
    margin-bottom: var(--spacing-md);
}

.accessory-tips li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
    line-height: 1.5;
    font-size: 0.9rem;
}

.accessory-tips li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--color-black);
    font-weight: bold;
}

.body-type-section {
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
    padding: var(--spacing-3xl);
    border-radius: var(--border-radius-xl);
}

.body-type-disclaimer {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
    text-align: center;
}

.body-type-disclaimer p {
    color: var(--color-gray-700);
    font-style: italic;
}

.body-types-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--spacing-xl);
}

.body-type-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    transition: all var(--transition-medium);
}

.body-type-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--glass-shadow);
}

.body-type-card h3 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
    text-align: center;
}

.body-type-card > p {
    text-align: center;
    color: var(--color-gray-600);
    margin-bottom: var(--spacing-lg);
    font-size: 0.9rem;
}

.styling-suggestions h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.styling-suggestions ul {
    list-style: none;
}

.styling-suggestions li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
    line-height: 1.5;
    font-size: 0.9rem;
}

.styling-suggestions li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #10b981;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 992px) {
    .styling-tips {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .tab-buttons {
        flex-wrap: wrap;
    }
    
    .tab-btn {
        flex: none;
        min-width: 120px;
    }
}

@media (max-width: 768px) {
    .occasion-tabs,
    .body-type-section {
        padding: var(--spacing-lg);
    }
    
    .principles-grid,
    .seasons-grid,
    .accessories-grid,
    .body-types-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    .outfit-cards {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function showOccasionTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.occasion-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}
</script>

<?php include_once '../includes/footer.php'; ?>