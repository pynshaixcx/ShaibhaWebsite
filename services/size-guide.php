<?php
$page_title = "Size Guide";
$page_description = "Find your perfect fit with our comprehensive size guide for pre-loved fashion.";
include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Size Guide Header -->
    <section class="size-guide-header">
        <div class="container">
            <div class="size-guide-header-content">
                <h1 class="page-title">
                    <span class="word" data-delay="0">Size</span>
                    <span class="word" data-delay="200">Guide</span>
                </h1>
                <p class="page-subtitle">
                    <span class="word" data-delay="400">Find</span>
                    <span class="word" data-delay="600">your</span>
                    <span class="word" data-delay="800">perfect</span>
                    <span class="word" data-delay="1000">fit</span>
                </p>
                <div class="hero-description">
                    <p>Our comprehensive size guide helps you find the perfect fit for pre-loved fashion pieces. Each item is carefully measured to ensure accuracy.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Size Guide Content -->
    <section class="size-guide-content">
        <div class="container">
            <!-- How to Measure -->
            <div class="measurement-section">
                <h2 class="section-title">
                    <span class="title-line">How to Measure</span>
                </h2>
                <div class="measurement-grid">
                    <div class="measurement-card">
                        <div class="measurement-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                <path d="M2 17l10 5 10-5"></path>
                                <path d="M2 12l10 5 10-5"></path>
                            </svg>
                        </div>
                        <h3>Bust/Chest</h3>
                        <p>Measure around the fullest part of your bust/chest, keeping the tape measure level and snug but not tight.</p>
                    </div>
                    <div class="measurement-card">
                        <div class="measurement-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                        </div>
                        <h3>Waist</h3>
                        <p>Measure around your natural waistline, which is the narrowest part of your torso, usually just above your belly button.</p>
                    </div>
                    <div class="measurement-card">
                        <div class="measurement-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            </svg>
                        </div>
                        <h3>Hips</h3>
                        <p>Measure around the fullest part of your hips, typically about 7-9 inches below your natural waistline.</p>
                    </div>
                    <div class="measurement-card">
                        <div class="measurement-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="2" x2="12" y2="22"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <h3>Inseam</h3>
                        <p>For pants, measure from the crotch seam down to the desired hem length along the inside of your leg.</p>
                    </div>
                </div>
            </div>

            <!-- Women's Sizes -->
            <div class="size-chart-section">
                <h2 class="section-title">
                    <span class="title-line">Women's Size Chart</span>
                </h2>
                
                <!-- Tops & Dresses -->
                <div class="chart-container">
                    <h3>Tops & Dresses</h3>
                    <div class="size-table-wrapper">
                        <table class="size-table">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Bust (inches)</th>
                                    <th>Waist (inches)</th>
                                    <th>Hips (inches)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>XS</strong></td>
                                    <td>30-32</td>
                                    <td>24-26</td>
                                    <td>34-36</td>
                                </tr>
                                <tr>
                                    <td><strong>S</strong></td>
                                    <td>32-34</td>
                                    <td>26-28</td>
                                    <td>36-38</td>
                                </tr>
                                <tr>
                                    <td><strong>M</strong></td>
                                    <td>34-36</td>
                                    <td>28-30</td>
                                    <td>38-40</td>
                                </tr>
                                <tr>
                                    <td><strong>L</strong></td>
                                    <td>36-38</td>
                                    <td>30-32</td>
                                    <td>40-42</td>
                                </tr>
                                <tr>
                                    <td><strong>XL</strong></td>
                                    <td>38-40</td>
                                    <td>32-34</td>
                                    <td>42-44</td>
                                </tr>
                                <tr>
                                    <td><strong>XXL</strong></td>
                                    <td>40-42</td>
                                    <td>34-36</td>
                                    <td>44-46</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bottoms -->
                <div class="chart-container">
                    <h3>Bottoms</h3>
                    <div class="size-table-wrapper">
                        <table class="size-table">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Waist (inches)</th>
                                    <th>Hips (inches)</th>
                                    <th>Inseam (inches)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>24</strong></td>
                                    <td>24-25</td>
                                    <td>34-35</td>
                                    <td>30-32</td>
                                </tr>
                                <tr>
                                    <td><strong>26</strong></td>
                                    <td>26-27</td>
                                    <td>36-37</td>
                                    <td>30-32</td>
                                </tr>
                                <tr>
                                    <td><strong>28</strong></td>
                                    <td>28-29</td>
                                    <td>38-39</td>
                                    <td>30-32</td>
                                </tr>
                                <tr>
                                    <td><strong>30</strong></td>
                                    <td>30-31</td>
                                    <td>40-41</td>
                                    <td>30-32</td>
                                </tr>
                                <tr>
                                    <td><strong>32</strong></td>
                                    <td>32-33</td>
                                    <td>42-43</td>
                                    <td>30-32</td>
                                </tr>
                                <tr>
                                    <td><strong>34</strong></td>
                                    <td>34-35</td>
                                    <td>44-45</td>
                                    <td>30-32</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Men's Sizes -->
            <div class="size-chart-section">
                <h2 class="section-title">
                    <span class="title-line">Men's Size Chart</span>
                </h2>
                
                <!-- Shirts -->
                <div class="chart-container">
                    <h3>Shirts</h3>
                    <div class="size-table-wrapper">
                        <table class="size-table">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Chest (inches)</th>
                                    <th>Waist (inches)</th>
                                    <th>Neck (inches)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>S</strong></td>
                                    <td>34-36</td>
                                    <td>28-30</td>
                                    <td>14-14.5</td>
                                </tr>
                                <tr>
                                    <td><strong>M</strong></td>
                                    <td>38-40</td>
                                    <td>32-34</td>
                                    <td>15-15.5</td>
                                </tr>
                                <tr>
                                    <td><strong>L</strong></td>
                                    <td>42-44</td>
                                    <td>36-38</td>
                                    <td>16-16.5</td>
                                </tr>
                                <tr>
                                    <td><strong>XL</strong></td>
                                    <td>46-48</td>
                                    <td>40-42</td>
                                    <td>17-17.5</td>
                                </tr>
                                <tr>
                                    <td><strong>XXL</strong></td>
                                    <td>50-52</td>
                                    <td>44-46</td>
                                    <td>18-18.5</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pants -->
                <div class="chart-container">
                    <h3>Pants</h3>
                    <div class="size-table-wrapper">
                        <table class="size-table">
                            <thead>
                                <tr>
                                    <th>Waist Size</th>
                                    <th>Waist (inches)</th>
                                    <th>Inseam (inches)</th>
                                    <th>Hip (inches)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>30</strong></td>
                                    <td>30</td>
                                    <td>30-34</td>
                                    <td>38-40</td>
                                </tr>
                                <tr>
                                    <td><strong>32</strong></td>
                                    <td>32</td>
                                    <td>30-34</td>
                                    <td>40-42</td>
                                </tr>
                                <tr>
                                    <td><strong>34</strong></td>
                                    <td>34</td>
                                    <td>30-34</td>
                                    <td>42-44</td>
                                </tr>
                                <tr>
                                    <td><strong>36</strong></td>
                                    <td>36</td>
                                    <td>30-34</td>
                                    <td>44-46</td>
                                </tr>
                                <tr>
                                    <td><strong>38</strong></td>
                                    <td>38</td>
                                    <td>30-34</td>
                                    <td>46-48</td>
                                </tr>
                                <tr>
                                    <td><strong>40</strong></td>
                                    <td>40</td>
                                    <td>30-34</td>
                                    <td>48-50</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Shoe Sizes -->
            <div class="size-chart-section">
                <h2 class="section-title">
                    <span class="title-line">Shoe Size Chart</span>
                </h2>
                
                <div class="shoe-charts">
                    <div class="chart-container">
                        <h3>Women's Shoes</h3>
                        <div class="size-table-wrapper">
                            <table class="size-table">
                                <thead>
                                    <tr>
                                        <th>US Size</th>
                                        <th>UK Size</th>
                                        <th>EU Size</th>
                                        <th>Foot Length (inches)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>5</strong></td>
                                        <td>3</td>
                                        <td>35.5</td>
                                        <td>8.5</td>
                                    </tr>
                                    <tr>
                                        <td><strong>6</strong></td>
                                        <td>4</td>
                                        <td>36.5</td>
                                        <td>9</td>
                                    </tr>
                                    <tr>
                                        <td><strong>7</strong></td>
                                        <td>5</td>
                                        <td>37.5</td>
                                        <td>9.5</td>
                                    </tr>
                                    <tr>
                                        <td><strong>8</strong></td>
                                        <td>6</td>
                                        <td>38.5</td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td><strong>9</strong></td>
                                        <td>7</td>
                                        <td>39.5</td>
                                        <td>10.5</td>
                                    </tr>
                                    <tr>
                                        <td><strong>10</strong></td>
                                        <td>8</td>
                                        <td>40.5</td>
                                        <td>11</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="chart-container">
                        <h3>Men's Shoes</h3>
                        <div class="size-table-wrapper">
                            <table class="size-table">
                                <thead>
                                    <tr>
                                        <th>US Size</th>
                                        <th>UK Size</th>
                                        <th>EU Size</th>
                                        <th>Foot Length (inches)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>7</strong></td>
                                        <td>6</td>
                                        <td>40</td>
                                        <td>9.5</td>
                                    </tr>
                                    <tr>
                                        <td><strong>8</strong></td>
                                        <td>7</td>
                                        <td>41</td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td><strong>9</strong></td>
                                        <td>8</td>
                                        <td>42</td>
                                        <td>10.5</td>
                                    </tr>
                                    <tr>
                                        <td><strong>10</strong></td>
                                        <td>9</td>
                                        <td>43</td>
                                        <td>11</td>
                                    </tr>
                                    <tr>
                                        <td><strong>11</strong></td>
                                        <td>10</td>
                                        <td>44</td>
                                        <td>11.5</td>
                                    </tr>
                                    <tr>
                                        <td><strong>12</strong></td>
                                        <td>11</td>
                                        <td>45</td>
                                        <td>12</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fit Tips -->
            <div class="fit-tips-section">
                <h2 class="section-title">
                    <span class="title-line">Fit Tips</span>
                </h2>
                <div class="tips-grid">
                    <div class="tip-card">
                        <div class="tip-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <h3>Pre-loved Considerations</h3>
                        <p>Pre-loved items may have slight variations in fit due to previous wear. Check individual item measurements for accuracy.</p>
                    </div>
                    <div class="tip-card">
                        <div class="tip-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                <path d="M2 17l10 5 10-5"></path>
                                <path d="M2 12l10 5 10-5"></path>
                            </svg>
                        </div>
                        <h3>Brand Variations</h3>
                        <p>Different brands may have varying fits. Always check the specific measurements provided for each item.</p>
                    </div>
                    <div class="tip-card">
                        <div class="tip-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <h3>Need Help?</h3>
                        <p>Contact our team if you need assistance with sizing. We're here to help you find the perfect fit.</p>
                    </div>
                    <div class="tip-card">
                        <div class="tip-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9,22 9,12 15,12 15,22"></polyline>
                            </svg>
                        </div>
                        <h3>Return Policy</h3>
                        <p>We offer a 7-day return policy if the fit isn't right. Items must be in original condition.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Size Guide Page Styles */
.size-guide-header {
    padding: var(--spacing-3xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
}

.size-guide-header-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.size-guide-content {
    padding: var(--spacing-3xl) 0;
}

.measurement-section {
    margin-bottom: var(--spacing-3xl);
}

.measurement-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-xl);
}

.measurement-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    text-align: center;
    transition: all var(--transition-medium);
}

.measurement-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--glass-shadow);
}

.measurement-icon {
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

.measurement-card h3 {
    font-size: 1.3rem;
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.measurement-card p {
    color: var(--color-gray-600);
    line-height: 1.6;
}

.size-chart-section {
    margin-bottom: var(--spacing-3xl);
}

.chart-container {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    margin-bottom: var(--spacing-xl);
}

.chart-container h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
    font-size: 1.3rem;
}

.size-table-wrapper {
    overflow-x: auto;
}

.size-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--color-white);
    border-radius: var(--border-radius-md);
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.size-table th {
    background: var(--color-black);
    color: var(--color-white);
    padding: var(--spacing-md);
    text-align: left;
    font-weight: 600;
}

.size-table td {
    padding: var(--spacing-md);
    border-bottom: 1px solid var(--color-gray-200);
    color: var(--color-gray-700);
}

.size-table tr:last-child td {
    border-bottom: none;
}

.size-table tr:nth-child(even) {
    background: var(--color-gray-100);
}

.shoe-charts {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-xl);
}

.fit-tips-section {
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
    padding: var(--spacing-3xl);
    border-radius: var(--border-radius-xl);
}

.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--spacing-xl);
}

.tip-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    text-align: center;
    transition: all var(--transition-medium);
}

.tip-card:hover {
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

.tip-card h3 {
    font-size: 1.2rem;
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.tip-card p {
    color: var(--color-gray-600);
    line-height: 1.6;
}

/* Responsive */
@media (max-width: 992px) {
    .shoe-charts {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    .measurement-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
}

@media (max-width: 768px) {
    .chart-container,
    .fit-tips-section {
        padding: var(--spacing-lg);
    }
    
    .size-table th,
    .size-table td {
        padding: var(--spacing-sm);
        font-size: 0.9rem;
    }
    
    .tips-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
}
</style>

<?php include_once '../includes/footer.php'; ?>