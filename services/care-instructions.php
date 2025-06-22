<?php
$page_title = "Care Instructions";
$page_description = "Learn how to properly care for your pre-loved fashion pieces to extend their lifespan.";
include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Care Instructions Header -->
    <section class="care-header">
        <div class="container">
            <div class="care-header-content">
                <h1 class="page-title">
                    <span class="word" data-delay="0">Care</span>
                    <span class="word" data-delay="200">Instructions</span>
                </h1>
                <p class="page-subtitle">
                    <span class="word" data-delay="400">Preserve</span>
                    <span class="word" data-delay="600">the</span>
                    <span class="word" data-delay="800">beauty</span>
                    <span class="word" data-delay="1000">of</span>
                    <span class="word" data-delay="1200">pre-loved</span>
                    <span class="word" data-delay="1400">fashion</span>
                </p>
                <div class="hero-description">
                    <p>Proper care ensures your pre-loved pieces maintain their quality and beauty for years to come. Follow our expert guidelines for different fabric types and garment styles.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Care Instructions Content -->
    <section class="care-content">
        <div class="container">
            <!-- General Care Tips -->
            <div class="general-care-section">
                <h2 class="section-title">
                    <span class="title-line">General Care Guidelines</span>
                </h2>
                <div class="care-tips-grid">
                    <div class="care-tip-card">
                        <div class="tip-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                <path d="M2 17l10 5 10-5"></path>
                                <path d="M2 12l10 5 10-5"></path>
                            </svg>
                        </div>
                        <h3>Read Care Labels</h3>
                        <p>Always check the care label before washing. Pre-loved items may have specific requirements based on their history and fabric condition.</p>
                    </div>
                    <div class="care-tip-card">
                        <div class="tip-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18l-2 13H5L3 6z"></path>
                                <path d="M3 6L2 3H1"></path>
                                <path d="M16 10a4 4 0 01-8 0"></path>
                            </svg>
                        </div>
                        <h3>Sort by Color</h3>
                        <p>Separate lights, darks, and colors to prevent bleeding. Pre-loved items may be more prone to color transfer.</p>
                    </div>
                    <div class="care-tip-card">
                        <div class="tip-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14,2 14,8 20,8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10,9 9,9 8,9"></polyline>
                            </svg>
                        </div>
                        <h3>Test First</h3>
                        <p>Test cleaning methods on an inconspicuous area first, especially for delicate or vintage pieces.</p>
                    </div>
                    <div class="care-tip-card">
                        <div class="tip-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            </svg>
                        </div>
                        <h3>Store Properly</h3>
                        <p>Use proper hangers and storage methods to maintain shape and prevent wrinkles, especially important for pre-loved items.</p>
                    </div>
                </div>
            </div>

            <!-- Fabric-Specific Care -->
            <div class="fabric-care-section">
                <h2 class="section-title">
                    <span class="title-line">Fabric-Specific Care</span>
                </h2>
                
                <div class="fabric-tabs">
                    <div class="tab-buttons">
                        <button class="tab-btn active" onclick="showFabricTab('cotton')">Cotton</button>
                        <button class="tab-btn" onclick="showFabricTab('silk')">Silk</button>
                        <button class="tab-btn" onclick="showFabricTab('wool')">Wool</button>
                        <button class="tab-btn" onclick="showFabricTab('denim')">Denim</button>
                        <button class="tab-btn" onclick="showFabricTab('leather')">Leather</button>
                        <button class="tab-btn" onclick="showFabricTab('synthetic')">Synthetic</button>
                    </div>
                    
                    <div class="tab-content">
                        <div id="cotton" class="fabric-tab active">
                            <div class="fabric-care-card">
                                <h3>Cotton Care</h3>
                                <div class="care-instructions">
                                    <div class="instruction-item">
                                        <h4>Washing</h4>
                                        <ul>
                                            <li>Machine wash in cold water (30°C or below)</li>
                                            <li>Use mild detergent to preserve color</li>
                                            <li>Turn inside out to protect surface</li>
                                            <li>Avoid bleach unless specifically needed</li>
                                        </ul>
                                    </div>
                                    <div class="instruction-item">
                                        <h4>Drying</h4>
                                        <ul>
                                            <li>Air dry when possible to prevent shrinkage</li>
                                            <li>If using dryer, use low heat setting</li>
                                            <li>Remove while slightly damp to prevent over-drying</li>
                                        </ul>
                                    </div>
                                    <div class="instruction-item">
                                        <h4>Ironing</h4>
                                        <ul>
                                            <li>Iron while slightly damp for best results</li>
                                            <li>Use medium to high heat setting</li>
                                            <li>Iron inside out to prevent shine</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="silk" class="fabric-tab">
                            <div class="fabric-care-card">
                                <h3>Silk Care</h3>
                                <div class="care-instructions">
                                    <div class="instruction-item">
                                        <h4>Washing</h4>
                                        <ul>
                                            <li>Hand wash in cold water with silk-specific detergent</li>
                                            <li>Never wring or twist - gently squeeze out water</li>
                                            <li>Consider professional dry cleaning for valuable pieces</li>
                                            <li>Test colorfastness before washing</li>
                                        </ul>
                                    </div>
                                    <div class="instruction-item">
                                        <h4>Drying</h4>
                                        <ul>
                                            <li>Lay flat on clean towel to dry</li>
                                            <li>Avoid direct sunlight which can fade colors</li>
                                            <li>Never use dryer - heat damages silk fibers</li>
                                        </ul>
                                    </div>
                                    <div class="instruction-item">
                                        <h4>Ironing</h4>
                                        <ul>
                                            <li>Iron on low heat while slightly damp</li>
                                            <li>Use pressing cloth to protect surface</li>
                                            <li>Iron inside out to prevent water spots</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="wool" class="fabric-tab">
                            <div class="fabric-care-card">
                                <h3>Wool Care</h3>
                                <div class="care-instructions">
                                    <div class="instruction-item">
                                        <h4>Washing</h4>
                                        <ul>
                                            <li>Hand wash in cool water with wool detergent</li>
                                            <li>Soak for 10-15 minutes, don't agitate</li>
                                            <li>Rinse thoroughly in cool water</li>
                                            <li>Professional cleaning recommended for structured pieces</li>
                                        </ul>
                                    </div>
                                    <div class="instruction-item">
                                        <h4>Drying</h4>
                                        <ul>
                                            <li>Gently squeeze out excess water in towel</li>
                                            <li>Lay flat to dry, reshaping while damp</li>
                                            <li>Avoid hanging which can stretch the garment</li>
                                        </ul>
                                    </div>
                                    <div class="instruction-item">
                                        <h4>Storage</h4>
                                        <ul>
                                            <li>Store folded to prevent stretching</li>
                                            <li>Use cedar blocks to prevent moths</li>
                                            <li>Allow to air between wears</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="denim" class="fabric-tab">
                            <div class="fabric-care-card">
                                <h3>Denim Care</h3>
                                <div class="care-instructions">
                                    <div class="instruction-item">
                                        <h4>Washing</h4>
                                        <ul>
                                            <li>Wash inside out in cold water</li>
                                            <li>Use gentle cycle to prevent fading</li>
                                            <li>Wash with similar colors only</li>
                                            <li>Spot clean when possible to preserve color</li>
                                        </ul>
                                    </div>
                                    <div class="instruction-item">
                                        <h4>Drying</h4>
                                        <ul>
                                            <li>Air dry to prevent shrinkage</li>
                                            <li>Hang by waistband or lay flat</li>
                                            <li>Avoid direct sunlight to prevent fading</li>
                                        </ul>
                                    </div>
                                    <div class="instruction-item">
                                        <h4>Maintenance</h4>
                                        <ul>
                                            <li>Wash less frequently to preserve color and fit</li>
                                            <li>Steam or air out between wears</li>
                                            <li>Store hanging to maintain shape</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="leather" class="fabric-tab">
                            <div class="fabric-care-card">
                                <h3>Leather Care</h3>
                                <div class="care-instructions">
                                    <div class="instruction-item">
                                        <h4>Cleaning</h4>
                                        <ul>
                                            <li>Use specialized leather cleaner only</li>
                                            <li>Test on hidden area first</li>
                                            <li>Clean with soft, damp cloth</li>
                                            <li>Professional cleaning for valuable pieces</li>
                                        </ul>
                                    </div>
                                    <div class="instruction-item">
                                        <h4>Conditioning</h4>
                                        <ul>
                                            <li>Apply leather conditioner every 6 months</li>
                                            <li>Use products specifically for your leather type</li>
                                            <li>Allow to absorb completely before wearing</li>
                                        </ul>
                                    </div>
                                    <div class="instruction-item">
                                        <h4>Storage</h4>
                                        <ul>
                                            <li>Store in breathable garment bags</li>
                                            <li>Use padded hangers for jackets</li>
                                            <li>Keep away from heat and direct sunlight</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="synthetic" class="fabric-tab">
                            <div class="fabric-care-card">
                                <h3>Synthetic Fabrics Care</h3>
                                <div class="care-instructions">
                                    <div class="instruction-item">
                                        <h4>Washing</h4>
                                        <ul>
                                            <li>Machine wash in warm water (40°C)</li>
                                            <li>Use regular detergent</li>
                                            <li>Separate from natural fibers</li>
                                            <li>Check care label for specific instructions</li>
                                        </ul>
                                    </div>
                                    <div class="instruction-item">
                                        <h4>Drying</h4>
                                        <ul>
                                            <li>Low heat tumble dry or air dry</li>
                                            <li>Remove promptly to prevent wrinkles</li>
                                            <li>Avoid high heat which can damage fibers</li>
                                        </ul>
                                    </div>
                                    <div class="instruction-item">
                                        <h4>Ironing</h4>
                                        <ul>
                                            <li>Use low to medium heat setting</li>
                                            <li>Use pressing cloth for delicate synthetics</li>
                                            <li>Iron inside out when possible</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stain Removal Guide -->
            <div class="stain-removal-section">
                <h2 class="section-title">
                    <span class="title-line">Stain Removal Guide</span>
                </h2>
                <div class="stain-grid">
                    <div class="stain-card">
                        <h3>Oil-Based Stains</h3>
                        <p><strong>Examples:</strong> Makeup, food grease, body oils</p>
                        <div class="removal-steps">
                            <ol>
                                <li>Blot excess oil with clean cloth</li>
                                <li>Apply cornstarch or baby powder</li>
                                <li>Let sit for 10-15 minutes</li>
                                <li>Brush off powder and treat with dish soap</li>
                                <li>Wash as usual</li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="stain-card">
                        <h3>Protein-Based Stains</h3>
                        <p><strong>Examples:</strong> Blood, sweat, food proteins</p>
                        <div class="removal-steps">
                            <ol>
                                <li>Rinse with cold water immediately</li>
                                <li>Soak in cold water for 30 minutes</li>
                                <li>Apply enzyme-based detergent</li>
                                <li>Let sit for 15 minutes</li>
                                <li>Wash in cold water</li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="stain-card">
                        <h3>Tannin Stains</h3>
                        <p><strong>Examples:</strong> Coffee, tea, wine, fruit juices</p>
                        <div class="removal-steps">
                            <ol>
                                <li>Blot immediately, don't rub</li>
                                <li>Rinse with cold water from back of fabric</li>
                                <li>Apply white vinegar solution (1:1 with water)</li>
                                <li>Let sit for 5 minutes</li>
                                <li>Wash with oxygen bleach if safe for fabric</li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="stain-card">
                        <h3>Dye Transfer</h3>
                        <p><strong>Examples:</strong> Color bleeding from other garments</p>
                        <div class="removal-steps">
                            <ol>
                                <li>Rewash immediately in cold water</li>
                                <li>Use color-safe bleach if appropriate</li>
                                <li>For white fabrics, use oxygen bleach</li>
                                <li>Repeat if necessary</li>
                                <li>Consider professional cleaning for valuable items</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Storage Tips -->
            <div class="storage-section">
                <h2 class="section-title">
                    <span class="title-line">Proper Storage</span>
                </h2>
                <div class="storage-grid">
                    <div class="storage-card">
                        <div class="storage-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9,22 9,12 15,12 15,22"></polyline>
                            </svg>
                        </div>
                        <h3>Hanging</h3>
                        <ul>
                            <li>Use padded hangers for delicate items</li>
                            <li>Wooden hangers for heavy coats and suits</li>
                            <li>Avoid wire hangers that can stretch fabric</li>
                            <li>Leave space between garments for air circulation</li>
                        </ul>
                    </div>
                    
                    <div class="storage-card">
                        <div class="storage-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            </svg>
                        </div>
                        <h3>Folding</h3>
                        <ul>
                            <li>Fold knitwear to prevent stretching</li>
                            <li>Use acid-free tissue paper for delicate items</li>
                            <li>Store in breathable cotton bags</li>
                            <li>Refold periodically to prevent permanent creases</li>
                        </ul>
                    </div>
                    
                    <div class="storage-card">
                        <div class="storage-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14,2 14,8 20,8"></polyline>
                            </svg>
                        </div>
                        <h3>Climate Control</h3>
                        <ul>
                            <li>Maintain 60-70°F temperature</li>
                            <li>Keep humidity between 45-55%</li>
                            <li>Avoid basements and attics</li>
                            <li>Use dehumidifiers in damp areas</li>
                        </ul>
                    </div>
                    
                    <div class="storage-card">
                        <div class="storage-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <h3>Protection</h3>
                        <ul>
                            <li>Use cedar blocks for moth protection</li>
                            <li>Avoid plastic bags for long-term storage</li>
                            <li>Clean items before storing</li>
                            <li>Check stored items periodically</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Care Instructions Page Styles */
.care-header {
    padding: var(--spacing-3xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
}

.care-header-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.care-content {
    padding: var(--spacing-3xl) 0;
}

.general-care-section {
    margin-bottom: var(--spacing-3xl);
}

.care-tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--spacing-xl);
}

.care-tip-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    text-align: center;
    transition: all var(--transition-medium);
}

.care-tip-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--glass-shadow);
}

.tip-icon {
    width: 64px;
    height: 64px;
    background: var(--color-black);
    color: var(--color-white);
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md);
}

.care-tip-card h3 {
    font-size: 1.2rem;
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.care-tip-card p {
    color: var(--color-gray-600);
    line-height: 1.6;
}

.fabric-care-section {
    margin-bottom: var(--spacing-3xl);
}

.fabric-tabs {
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

.fabric-tab {
    display: none;
}

.fabric-tab.active {
    display: block;
}

.fabric-care-card h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
    font-size: 1.5rem;
}

.care-instructions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
}

.instruction-item h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
    font-size: 1.1rem;
}

.instruction-item ul {
    list-style: none;
    padding-left: 0;
}

.instruction-item li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
    line-height: 1.5;
}

.instruction-item li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--color-black);
    font-weight: bold;
}

.stain-removal-section {
    margin-bottom: var(--spacing-3xl);
}

.stain-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-xl);
}

.stain-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    transition: all var(--transition-medium);
}

.stain-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--glass-shadow);
}

.stain-card h3 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.stain-card p {
    color: var(--color-gray-600);
    margin-bottom: var(--spacing-md);
    font-size: 0.9rem;
}

.removal-steps ol {
    padding-left: var(--spacing-md);
}

.removal-steps li {
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
    line-height: 1.5;
}

.storage-section {
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
    padding: var(--spacing-3xl);
    border-radius: var(--border-radius-xl);
}

.storage-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--spacing-xl);
}

.storage-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    text-align: center;
    transition: all var(--transition-medium);
}

.storage-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--glass-shadow);
}

.storage-icon {
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

.storage-card h3 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.storage-card ul {
    list-style: none;
    text-align: left;
}

.storage-card li {
    position: relative;
    padding-left: var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
    line-height: 1.5;
}

.storage-card li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #10b981;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 992px) {
    .care-instructions {
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
    .fabric-tabs,
    .storage-section {
        padding: var(--spacing-lg);
    }
    
    .care-tips-grid,
    .stain-grid,
    .storage-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
}
</style>

<script>
function showFabricTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.fabric-tab').forEach(tab => {
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