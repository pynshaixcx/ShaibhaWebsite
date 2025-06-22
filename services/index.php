<?php
$page_title = "Services";
$page_description = "Discover our services including size guide, care instructions, and styling tips.";
require_once '../includes/functions.php';
include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Services Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1 class="page-title">Our Services</h1>
                <p class="page-subtitle">Making your fashion experience better</p>
            </div>
        </div>
    </section>

    <!-- Services Content -->
    <section class="services-content">
        <div class="container">
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 8v8"></path>
                            <path d="M8 12h8"></path>
                        </svg>
                    </div>
                    <h3 class="service-title">Size Guide</h3>
                    <p class="service-description">Find the perfect fit with our comprehensive size guide for all clothing types.</p>
                    <a href="/services/size-guide.php" class="btn btn-outline">View Size Guide</a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                    </div>
                    <h3 class="service-title">Care Instructions</h3>
                    <p class="service-description">Learn how to properly care for your pre-loved fashion items to extend their life.</p>
                    <a href="/services/care-instructions.php" class="btn btn-outline">View Care Instructions</a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                    <h3 class="service-title">Styling Tips</h3>
                    <p class="service-description">Get expert advice on how to style your pieces for different occasions and seasons.</p>
                    <a href="/services/styling-tips.php" class="btn btn-outline">View Styling Tips</a>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .page-header {
        padding: var(--spacing-3xl) 0;
        background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
        text-align: center;
    }
    
    .services-content {
        padding: var(--spacing-3xl) 0;
    }
    
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--spacing-2xl);
        margin-top: var(--spacing-2xl);
    }
    
    .service-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-backdrop);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius-xl);
        padding: var(--spacing-xl);
        text-align: center;
        transition: all var(--transition-medium);
    }
    
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--glass-shadow);
    }
    
    .service-icon {
        margin: 0 auto var(--spacing-lg);
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-black);
    }
    
    .service-title {
        font-size: 1.5rem;
        margin-bottom: var(--spacing-md);
    }
    
    .service-description {
        color: var(--color-gray-600);
        margin-bottom: var(--spacing-lg);
        min-height: 4rem;
    }
    
    @media (max-width: 768px) {
        .services-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php include_once '../includes/footer.php'; ?> 