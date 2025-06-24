import matplotlib.pyplot as plt
import networkx as nx
from matplotlib.patches import FancyArrowPatch
import numpy as np
from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, Image, PageBreak
from reportlab.lib.units import inch
import io
import os

class ProjectOutlineGenerator:
    def __init__(self):
        self.project_name = "ShaiBha - Pre-loved Fashion E-commerce Platform"
        self.text_content = []
        self.pdf_elements = []
        self.styles = getSampleStyleSheet()
        
        # Create custom styles if they don't exist
        if 'Heading1' not in self.styles:
            self.styles.add(ParagraphStyle(
                name='Heading1',
                parent=self.styles['Heading1'],
                fontSize=18,
                spaceAfter=12,
                spaceBefore=24,
            ))
        
        if 'Heading2' not in self.styles:
            self.styles.add(ParagraphStyle(
                name='Heading2',
                parent=self.styles['Heading2'],
                fontSize=14,
                spaceAfter=8,
                spaceBefore=16,
            ))
        
        if 'Heading3' not in self.styles:
            self.styles.add(ParagraphStyle(
                name='Heading3',
                parent=self.styles['Heading3'],
                fontSize=12,
                spaceAfter=6,
                spaceBefore=10,
            ))
        
        if 'Normal' not in self.styles:
            self.styles.add(ParagraphStyle(
                name='Normal',
                parent=self.styles['Normal'],
                fontSize=10,
                spaceAfter=6,
            ))
        
        if 'Bullet' not in self.styles:
            self.styles.add(ParagraphStyle(
                name='Bullet',
                parent=self.styles['Normal'],
                fontSize=10,
                leftIndent=20,
                firstLineIndent=-15,
                spaceAfter=3,
            ))
        
    def add_text(self, text):
        """Add text to both text content and PDF elements"""
        self.text_content.append(text)
        
    def add_heading(self, text, level=1):
        """Add heading to both text content and PDF elements"""
        if level == 1:
            self.add_text("\n" + "="*80)
            self.add_text(text.center(80))
            self.add_text("="*80 + "\n")
            self.pdf_elements.append(Paragraph(text, self.styles['Heading1']))
        elif level == 2:
            self.add_text("\n" + text)
            self.add_text("-"*80)
            self.pdf_elements.append(Paragraph(text, self.styles['Heading2']))
        elif level == 3:
            self.add_text("\n" + text)
            self.pdf_elements.append(Paragraph(text, self.styles['Heading3']))
        else:
            self.add_text("\n" + text)
            self.pdf_elements.append(Paragraph(text, self.styles['Normal']))
        
        self.pdf_elements.append(Spacer(1, 0.1*inch))
        
    def add_paragraph(self, text):
        """Add paragraph to both text content and PDF elements"""
        self.add_text(text)
        self.pdf_elements.append(Paragraph(text, self.styles['Normal']))
        self.pdf_elements.append(Spacer(1, 0.1*inch))
        
    def add_bullet(self, text):
        """Add bullet point to both text content and PDF elements"""
        self.add_text("  • " + text)
        self.pdf_elements.append(Paragraph("• " + text, self.styles['Bullet']))
        
    def add_spacer(self):
        """Add spacer to PDF elements"""
        self.pdf_elements.append(Spacer(1, 0.2*inch))
        
    def add_page_break(self):
        """Add page break to PDF elements"""
        self.pdf_elements.append(PageBreak())
        
    def generate_outline(self):
        """Generate a complete project outline with diagrams"""
        # Title page
        self.add_heading(self.project_name, 1)
        self.add_paragraph("Project Outline & Technical Documentation")
        self.add_paragraph("Date: May 2025")
        self.add_spacer()
        self.add_page_break()
        
        # Table of contents
        self.add_heading("TABLE OF CONTENTS", 2)
        toc_data = [
            ["1. PROJECT OVERVIEW", "3"],
            ["2. SYSTEM ARCHITECTURE", "5"],
            ["3. DATABASE SCHEMA", "8"],
            ["4. ENTITY-RELATIONSHIP DIAGRAMS", "12"],
            ["5. FLOW CHARTS", "15"],
            ["6. UI/UX OUTLINE", "18"],
            ["7. IMPLEMENTATION PLAN", "22"],
            ["8. TECHNICAL SPECIFICATIONS", "25"],
            ["9. CONCLUSION", "28"]
        ]
        
        toc_table = Table(toc_data, colWidths=[4*inch, 0.5*inch])
        toc_table.setStyle(TableStyle([
            ('FONT', (0, 0), (-1, -1), 'Helvetica', 10),
            ('ALIGN', (1, 0), (1, -1), 'RIGHT'),
            ('LINEBELOW', (0, 0), (-1, -2), 0.5, colors.lightgrey),
            ('VALIGN', (0, 0), (-1, -1), 'MIDDLE'),
            ('BOTTOMPADDING', (0, 0), (-1, -1), 8),
        ]))
        
        self.pdf_elements.append(toc_table)
        self.add_page_break()
        
        # Generate project overview
        self.generate_project_overview()
        self.add_page_break()
        
        # Generate system architecture
        self.generate_system_architecture()
        self.add_page_break()
        
        # Generate database schema
        self.generate_database_schema()
        self.add_page_break()
        
        # Generate ER diagrams
        self.generate_er_diagrams()
        self.add_page_break()
        
        # Generate flow charts
        self.generate_flow_charts()
        self.add_page_break()
        
        # Generate UI/UX outline
        self.generate_ui_ux_outline()
        self.add_page_break()
        
        # Generate implementation plan
        self.generate_implementation_plan()
        self.add_page_break()
        
        # Generate technical specifications
        self.generate_technical_specifications()
        self.add_page_break()
        
        # Generate conclusion
        self.generate_conclusion()
        
        # Save the text file
        self.save_text_file()
        
        # Save the PDF file
        self.save_pdf_file()
        
    def generate_project_overview(self):
        """Generate project overview section"""
        self.add_heading("1. PROJECT OVERVIEW", 2)
        self.add_paragraph("ShaiBha is a curated marketplace for pre-loved fashion items, specializing in high-quality, authentic designer and branded clothing. The platform aims to make sustainable fashion accessible while offering unique pieces at affordable prices.")
        self.add_paragraph("The platform focuses on creating a premium shopping experience for customers looking for sustainable fashion options. Each item in the collection is carefully curated, authenticated, and described in detail to ensure customers have confidence in their purchases.")
        
        self.add_heading("1.1 Project Objectives", 3)
        self.add_bullet("Create a user-friendly e-commerce platform for pre-loved fashion")
        self.add_bullet("Implement secure user authentication and account management")
        self.add_bullet("Develop a robust product catalog with detailed condition descriptions")
        self.add_bullet("Build a secure shopping cart and checkout system with COD payment option")
        self.add_bullet("Create an admin panel for inventory, order, and customer management")
        self.add_bullet("Implement responsive design for all device types")
        self.add_bullet("Promote sustainable fashion through educational content")
        self.add_bullet("Build a community of conscious consumers")
        
        self.add_heading("1.2 Target Audience", 3)
        self.add_bullet("Fashion-conscious consumers interested in sustainable shopping")
        self.add_bullet("Budget-conscious shoppers looking for quality designer items")
        self.add_bullet("Environmentally conscious consumers")
        self.add_bullet("Fashion enthusiasts seeking unique pieces")
        self.add_bullet("Millennials and Gen Z consumers with sustainability values")
        self.add_bullet("Luxury fashion enthusiasts looking for affordable options")
        
        self.add_heading("1.3 Key Features", 3)
        self.add_bullet("User registration and profile management")
        self.add_bullet("Product browsing with advanced filtering and search")
        self.add_bullet("Detailed product pages with condition ratings and descriptions")
        self.add_bullet("Shopping cart and checkout functionality")
        self.add_bullet("Order tracking and history")
        self.add_bullet("Admin dashboard for inventory and order management")
        self.add_bullet("Responsive design for mobile and desktop users")
        self.add_bullet("Detailed product condition assessment system")
        self.add_bullet("Secure Cash on Delivery payment option")
        self.add_bullet("Comprehensive admin reporting tools")
        
        self.add_heading("1.4 Business Model", 3)
        self.add_bullet("Curated marketplace for pre-loved fashion")
        self.add_bullet("Revenue from sales of pre-owned items")
        self.add_bullet("Potential for consignment model in future phases")
        self.add_bullet("Focus on quality over quantity")
        self.add_bullet("Building brand reputation through authentication and quality assurance")
        self.add_bullet("Sustainable fashion education and community building")
        
    def generate_system_architecture(self):
        """Generate system architecture section"""
        self.add_heading("2. SYSTEM ARCHITECTURE", 2)
        self.add_paragraph("The ShaiBha platform follows a traditional web application architecture with the following components:")
        
        self.add_heading("2.1 Frontend", 3)
        self.add_bullet("HTML5, CSS3, JavaScript")
        self.add_bullet("Responsive design using custom CSS")
        self.add_bullet("Client-side validation and interactivity")
        self.add_bullet("Modern UI with glass morphism effects")
        self.add_bullet("Optimized for mobile and desktop devices")
        
        self.add_heading("2.2 Backend", 3)
        self.add_bullet("PHP for server-side processing")
        self.add_bullet("MySQL database for data storage")
        self.add_bullet("RESTful API endpoints for AJAX interactions")
        self.add_bullet("MVC-inspired architecture for code organization")
        self.add_bullet("Secure authentication and authorization")
        
        self.add_heading("2.3 Server Environment", 3)
        self.add_bullet("Apache web server")
        self.add_bullet("PHP 7.4+")
        self.add_bullet("MySQL 5.7+")
        self.add_bullet("SSL encryption for secure data transmission")
        self.add_bullet("Regular backups and security updates")
        
        self.add_heading("2.4 External Integrations", 3)
        self.add_bullet("Payment gateway (future implementation)")
        self.add_bullet("Email notification service")
        self.add_bullet("Image storage and optimization")
        self.add_bullet("Analytics tracking")
        
        self.add_heading("2.5 System Architecture Diagram", 3)
        self.add_text("```")
        self.add_text("┌───────────────┐      ┌───────────────┐      ┌───────────────┐")
        self.add_text("│               │      │               │      │               │")
        self.add_text("│   Frontend    │◄────►│    Backend    │◄────►│   Database    │")
        self.add_text("│  (HTML/CSS/JS)│      │     (PHP)     │      │    (MySQL)    │")
        self.add_text("│               │      │               │      │               │")
        self.add_text("└───────────────┘      └───────────────┘      └───────────────┘")
        self.add_text("        ▲                      ▲                      ▲")
        self.add_text("        │                      │                      │")
        self.add_text("        ▼                      ▼                      ▼")
        self.add_text("┌───────────────┐      ┌───────────────┐      ┌───────────────┐")
        self.add_text("│               │      │               │      │               │")
        self.add_text("│  User Browser │      │  Admin Panel  │      │ External APIs │")
        self.add_text("│               │      │               │      │               │")
        self.add_text("└───────────────┘      └───────────────┘      └───────────────┘")
        self.add_text("```")
        
        # Create architecture diagram for PDF
        fig, ax = plt.figure(figsize=(8, 6)), plt.gca()
        ax.axis('off')
        
        # Create nodes
        nodes = {
            'frontend': (0.25, 0.75),
            'backend': (0.5, 0.75),
            'database': (0.75, 0.75),
            'browser': (0.25, 0.25),
            'admin': (0.5, 0.25),
            'apis': (0.75, 0.25)
        }
        
        # Draw boxes
        for name, (x, y) in nodes.items():
            rect = plt.Rectangle((x-0.1, y-0.1), 0.2, 0.2, fill=True, 
                                facecolor='lightgray', edgecolor='black', alpha=0.7)
            ax.add_patch(rect)
            
            # Add labels
            if name == 'frontend':
                ax.text(x, y, 'Frontend\n(HTML/CSS/JS)', ha='center', va='center', fontsize=9)
            elif name == 'backend':
                ax.text(x, y, 'Backend\n(PHP)', ha='center', va='center', fontsize=9)
            elif name == 'database':
                ax.text(x, y, 'Database\n(MySQL)', ha='center', va='center', fontsize=9)
            elif name == 'browser':
                ax.text(x, y, 'User Browser', ha='center', va='center', fontsize=9)
            elif name == 'admin':
                ax.text(x, y, 'Admin Panel', ha='center', va='center', fontsize=9)
            elif name == 'apis':
                ax.text(x, y, 'External APIs', ha='center', va='center', fontsize=9)
        
        # Draw arrows
        arrow_style = dict(arrowstyle='<->', color='black', lw=1.5)
        
        # Horizontal arrows
        ax.add_patch(FancyArrowPatch(nodes['frontend'], nodes['backend'], 
                                    connectionstyle='arc3,rad=0', **arrow_style))
        ax.add_patch(FancyArrowPatch(nodes['backend'], nodes['database'], 
                                    connectionstyle='arc3,rad=0', **arrow_style))
        
        # Vertical arrows
        ax.add_patch(FancyArrowPatch(nodes['frontend'], nodes['browser'], 
                                    connectionstyle='arc3,rad=0', **arrow_style))
        ax.add_patch(FancyArrowPatch(nodes['backend'], nodes['admin'], 
                                    connectionstyle='arc3,rad=0', **arrow_style))
        ax.add_patch(FancyArrowPatch(nodes['database'], nodes['apis'], 
                                    connectionstyle='arc3,rad=0', **arrow_style))
        
        plt.title('System Architecture Diagram')
        
        # Save the figure to a buffer
        buf = io.BytesIO()
        plt.savefig(buf, format='png', dpi=300, bbox_inches='tight')
        buf.seek(0)
        
        # Add the image to the PDF
        img = Image(buf, width=6*inch, height=4*inch)
        self.pdf_elements.append(img)
        
        plt.close()
        
    def generate_database_schema(self):
        """Generate database schema section"""
        self.add_heading("3. DATABASE SCHEMA", 2)
        self.add_paragraph("The database schema consists of the following main tables:")
        
        tables = [
            {
                "name": "customers",
                "description": "Stores customer account information",
                "fields": [
                    {"name": "id", "type": "INT", "constraints": "PRIMARY KEY, AUTO_INCREMENT"},
                    {"name": "first_name", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "last_name", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "email", "type": "VARCHAR(255)", "constraints": "UNIQUE, NOT NULL"},
                    {"name": "phone", "type": "VARCHAR(20)", "constraints": ""},
                    {"name": "password", "type": "VARCHAR(255)", "constraints": "NOT NULL"},
                    {"name": "date_of_birth", "type": "DATE", "constraints": ""},
                    {"name": "gender", "type": "ENUM('male', 'female', 'other')", "constraints": ""},
                    {"name": "status", "type": "ENUM('active', 'inactive')", "constraints": "DEFAULT 'active'"},
                    {"name": "email_verified", "type": "BOOLEAN", "constraints": "DEFAULT FALSE"},
                    {"name": "last_login", "type": "TIMESTAMP", "constraints": "NULL"},
                    {"name": "created_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP"},
                    {"name": "updated_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"}
                ]
            },
            {
                "name": "customer_addresses",
                "description": "Stores customer shipping and billing addresses",
                "fields": [
                    {"name": "id", "type": "INT", "constraints": "PRIMARY KEY, AUTO_INCREMENT"},
                    {"name": "customer_id", "type": "INT", "constraints": "NOT NULL, FOREIGN KEY"},
                    {"name": "type", "type": "ENUM('billing', 'shipping')", "constraints": "DEFAULT 'shipping'"},
                    {"name": "first_name", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "last_name", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "address_line_1", "type": "VARCHAR(255)", "constraints": "NOT NULL"},
                    {"name": "address_line_2", "type": "VARCHAR(255)", "constraints": ""},
                    {"name": "city", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "state", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "postal_code", "type": "VARCHAR(20)", "constraints": "NOT NULL"},
                    {"name": "country", "type": "VARCHAR(100)", "constraints": "DEFAULT 'India'"},
                    {"name": "is_default", "type": "BOOLEAN", "constraints": "DEFAULT FALSE"}
                ]
            },
            {
                "name": "categories",
                "description": "Stores product categories",
                "fields": [
                    {"name": "id", "type": "INT", "constraints": "PRIMARY KEY, AUTO_INCREMENT"},
                    {"name": "name", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "slug", "type": "VARCHAR(100)", "constraints": "UNIQUE, NOT NULL"},
                    {"name": "description", "type": "TEXT", "constraints": ""},
                    {"name": "image", "type": "VARCHAR(255)", "constraints": ""},
                    {"name": "parent_id", "type": "INT", "constraints": "NULL, FOREIGN KEY"},
                    {"name": "status", "type": "ENUM('active', 'inactive')", "constraints": "DEFAULT 'active'"},
                    {"name": "created_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP"},
                    {"name": "updated_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"}
                ]
            },
            {
                "name": "products",
                "description": "Stores product information",
                "fields": [
                    {"name": "id", "type": "INT", "constraints": "PRIMARY KEY, AUTO_INCREMENT"},
                    {"name": "name", "type": "VARCHAR(255)", "constraints": "NOT NULL"},
                    {"name": "slug", "type": "VARCHAR(255)", "constraints": "UNIQUE, NOT NULL"},
                    {"name": "description", "type": "TEXT", "constraints": ""},
                    {"name": "short_description", "type": "VARCHAR(500)", "constraints": ""},
                    {"name": "category_id", "type": "INT", "constraints": "NOT NULL, FOREIGN KEY"},
                    {"name": "price", "type": "DECIMAL(10,2)", "constraints": "NOT NULL"},
                    {"name": "sale_price", "type": "DECIMAL(10,2)", "constraints": "NULL"},
                    {"name": "sku", "type": "VARCHAR(100)", "constraints": "UNIQUE"},
                    {"name": "stock_quantity", "type": "INT", "constraints": "DEFAULT 1"},
                    {"name": "condition_rating", "type": "ENUM('excellent', 'very_good', 'good', 'fair')", "constraints": "DEFAULT 'good'"},
                    {"name": "size", "type": "VARCHAR(50)", "constraints": ""},
                    {"name": "color", "type": "VARCHAR(50)", "constraints": ""},
                    {"name": "brand", "type": "VARCHAR(100)", "constraints": ""},
                    {"name": "material", "type": "VARCHAR(100)", "constraints": ""},
                    {"name": "care_instructions", "type": "TEXT", "constraints": ""},
                    {"name": "featured", "type": "BOOLEAN", "constraints": "DEFAULT FALSE"},
                    {"name": "status", "type": "ENUM('active', 'inactive', 'sold')", "constraints": "DEFAULT 'active'"},
                    {"name": "created_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP"},
                    {"name": "updated_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"}
                ]
            },
            {
                "name": "product_images",
                "description": "Stores product images",
                "fields": [
                    {"name": "id", "type": "INT", "constraints": "PRIMARY KEY, AUTO_INCREMENT"},
                    {"name": "product_id", "type": "INT", "constraints": "NOT NULL, FOREIGN KEY"},
                    {"name": "image_path", "type": "VARCHAR(255)", "constraints": "NOT NULL"},
                    {"name": "alt_text", "type": "VARCHAR(255)", "constraints": ""},
                    {"name": "is_primary", "type": "BOOLEAN", "constraints": "DEFAULT FALSE"},
                    {"name": "sort_order", "type": "INT", "constraints": "DEFAULT 0"},
                    {"name": "created_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP"}
                ]
            },
            {
                "name": "cart",
                "description": "Stores shopping cart items",
                "fields": [
                    {"name": "id", "type": "INT", "constraints": "PRIMARY KEY, AUTO_INCREMENT"},
                    {"name": "session_id", "type": "VARCHAR(255)", "constraints": "NOT NULL"},
                    {"name": "customer_id", "type": "INT", "constraints": "NULL, FOREIGN KEY"},
                    {"name": "product_id", "type": "INT", "constraints": "NOT NULL, FOREIGN KEY"},
                    {"name": "quantity", "type": "INT", "constraints": "NOT NULL DEFAULT 1"},
                    {"name": "added_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP"},
                    {"name": "updated_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"}
                ]
            },
            {
                "name": "orders",
                "description": "Stores order information",
                "fields": [
                    {"name": "id", "type": "INT", "constraints": "PRIMARY KEY, AUTO_INCREMENT"},
                    {"name": "order_number", "type": "VARCHAR(50)", "constraints": "UNIQUE, NOT NULL"},
                    {"name": "customer_id", "type": "INT", "constraints": "NULL, FOREIGN KEY"},
                    {"name": "customer_email", "type": "VARCHAR(255)", "constraints": "NOT NULL"},
                    {"name": "customer_phone", "type": "VARCHAR(20)", "constraints": "NOT NULL"},
                    {"name": "billing_first_name", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "billing_last_name", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "billing_address_line_1", "type": "VARCHAR(255)", "constraints": "NOT NULL"},
                    {"name": "billing_address_line_2", "type": "VARCHAR(255)", "constraints": ""},
                    {"name": "billing_city", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "billing_state", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "billing_postal_code", "type": "VARCHAR(20)", "constraints": "NOT NULL"},
                    {"name": "billing_country", "type": "VARCHAR(100)", "constraints": "DEFAULT 'India'"},
                    {"name": "shipping_first_name", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "shipping_last_name", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "shipping_address_line_1", "type": "VARCHAR(255)", "constraints": "NOT NULL"},
                    {"name": "shipping_address_line_2", "type": "VARCHAR(255)", "constraints": ""},
                    {"name": "shipping_city", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "shipping_state", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "shipping_postal_code", "type": "VARCHAR(20)", "constraints": "NOT NULL"},
                    {"name": "shipping_country", "type": "VARCHAR(100)", "constraints": "DEFAULT 'India'"},
                    {"name": "subtotal", "type": "DECIMAL(10,2)", "constraints": "NOT NULL"},
                    {"name": "shipping_cost", "type": "DECIMAL(10,2)", "constraints": "DEFAULT 0.00"},
                    {"name": "total_amount", "type": "DECIMAL(10,2)", "constraints": "NOT NULL"},
                    {"name": "payment_method", "type": "ENUM('cod')", "constraints": "DEFAULT 'cod'"},
                    {"name": "payment_status", "type": "ENUM('pending', 'paid', 'failed', 'refunded')", "constraints": "DEFAULT 'pending'"},
                    {"name": "order_status", "type": "ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'returned')", "constraints": "DEFAULT 'pending'"},
                    {"name": "notes", "type": "TEXT", "constraints": ""},
                    {"name": "admin_notes", "type": "TEXT", "constraints": ""},
                    {"name": "tracking_number", "type": "VARCHAR(100)", "constraints": ""},
                    {"name": "shipped_at", "type": "TIMESTAMP", "constraints": "NULL"},
                    {"name": "delivered_at", "type": "TIMESTAMP", "constraints": "NULL"},
                    {"name": "created_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP"},
                    {"name": "updated_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"}
                ]
            },
            {
                "name": "order_items",
                "description": "Stores items within an order",
                "fields": [
                    {"name": "id", "type": "INT", "constraints": "PRIMARY KEY, AUTO_INCREMENT"},
                    {"name": "order_id", "type": "INT", "constraints": "NOT NULL, FOREIGN KEY"},
                    {"name": "product_id", "type": "INT", "constraints": "NOT NULL, FOREIGN KEY"},
                    {"name": "product_name", "type": "VARCHAR(255)", "constraints": "NOT NULL"},
                    {"name": "product_sku", "type": "VARCHAR(100)", "constraints": ""},
                    {"name": "product_slug", "type": "VARCHAR(255)", "constraints": ""},
                    {"name": "quantity", "type": "INT", "constraints": "NOT NULL"},
                    {"name": "price", "type": "DECIMAL(10,2)", "constraints": "NOT NULL"},
                    {"name": "total", "type": "DECIMAL(10,2)", "constraints": "NOT NULL"},
                    {"name": "product_condition", "type": "VARCHAR(50)", "constraints": ""},
                    {"name": "product_size", "type": "VARCHAR(50)", "constraints": ""},
                    {"name": "product_color", "type": "VARCHAR(50)", "constraints": ""},
                    {"name": "product_brand", "type": "VARCHAR(100)", "constraints": ""},
                    {"name": "created_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP"}
                ]
            },
            {
                "name": "admin_users",
                "description": "Stores admin user accounts",
                "fields": [
                    {"name": "id", "type": "INT", "constraints": "PRIMARY KEY, AUTO_INCREMENT"},
                    {"name": "username", "type": "VARCHAR(100)", "constraints": "UNIQUE, NOT NULL"},
                    {"name": "email", "type": "VARCHAR(255)", "constraints": "UNIQUE, NOT NULL"},
                    {"name": "password", "type": "VARCHAR(255)", "constraints": "NOT NULL"},
                    {"name": "full_name", "type": "VARCHAR(255)", "constraints": "NOT NULL"},
                    {"name": "role", "type": "ENUM('admin', 'manager', 'staff')", "constraints": "DEFAULT 'staff'"},
                    {"name": "status", "type": "ENUM('active', 'inactive')", "constraints": "DEFAULT 'active'"},
                    {"name": "permissions", "type": "JSON", "constraints": ""},
                    {"name": "avatar", "type": "VARCHAR(255)", "constraints": ""},
                    {"name": "last_login", "type": "TIMESTAMP", "constraints": "NULL"},
                    {"name": "login_attempts", "type": "INT", "constraints": "DEFAULT 0"},
                    {"name": "locked_until", "type": "TIMESTAMP", "constraints": "NULL"},
                    {"name": "created_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP"},
                    {"name": "updated_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"}
                ]
            },
            {
                "name": "activity_log",
                "description": "Logs user and system activities",
                "fields": [
                    {"name": "id", "type": "INT", "constraints": "PRIMARY KEY, AUTO_INCREMENT"},
                    {"name": "user_type", "type": "ENUM('customer', 'admin')", "constraints": "NOT NULL"},
                    {"name": "user_id", "type": "INT", "constraints": ""},
                    {"name": "action", "type": "VARCHAR(100)", "constraints": "NOT NULL"},
                    {"name": "description", "type": "TEXT", "constraints": ""},
                    {"name": "ip_address", "type": "VARCHAR(45)", "constraints": ""},
                    {"name": "user_agent", "type": "TEXT", "constraints": ""},
                    {"name": "created_at", "type": "TIMESTAMP", "constraints": "DEFAULT CURRENT_TIMESTAMP"}
                ]
            }
        ]
        
        # Print table details for text file
        for i, table in enumerate(tables):
            self.add_heading(f"3.{i+1} {table['name']} Table", 3)
            self.add_paragraph(f"Description: {table['description']}")
            self.add_paragraph("Fields:")
            
            for field in table['fields']:
                self.add_text(f"    • {field['name']} ({field['type']}) {field['constraints']}")
            
            # Create table for PDF
            if i < 4:  # Only add the first 4 tables to avoid making the PDF too long
                table_data = [["Field Name", "Type", "Constraints"]]
                for field in table['fields']:
                    table_data.append([field['name'], field['type'], field['constraints']])
                
                pdf_table = Table(table_data, colWidths=[1.5*inch, 2*inch, 2.5*inch])
                pdf_table.setStyle(TableStyle([
                    ('BACKGROUND', (0, 0), (-1, 0), colors.lightgrey),
                    ('TEXTCOLOR', (0, 0), (-1, 0), colors.black),
                    ('ALIGN', (0, 0), (-1, 0), 'CENTER'),
                    ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
                    ('FONTSIZE', (0, 0), (-1, 0), 10),
                    ('BOTTOMPADDING', (0, 0), (-1, 0), 12),
                    ('BACKGROUND', (0, 1), (-1, -1), colors.white),
                    ('TEXTCOLOR', (0, 1), (-1, -1), colors.black),
                    ('ALIGN', (0, 1), (-1, -1), 'LEFT'),
                    ('FONTNAME', (0, 1), (-1, -1), 'Helvetica'),
                    ('FONTSIZE', (0, 1), (-1, -1), 8),
                    ('GRID', (0, 0), (-1, -1), 0.5, colors.grey),
                    ('VALIGN', (0, 0), (-1, -1), 'MIDDLE'),
                    ('BOTTOMPADDING', (0, 1), (-1, -1), 6),
                ]))
                
                self.pdf_elements.append(pdf_table)
                self.add_spacer()
        
        self.add_heading("Relationships between tables:", 3)
        self.add_bullet("customers 1:N customer_addresses")
        self.add_bullet("customers 1:N orders")
        self.add_bullet("categories 1:N products")
        self.add_bullet("products 1:N product_images")
        self.add_bullet("products 1:N cart")
        self.add_bullet("products 1:N order_items")
        self.add_bullet("orders 1:N order_items")
        
    def generate_er_diagrams(self):
        """Generate ER diagram section"""
        self.add_heading("4. ENTITY-RELATIONSHIP DIAGRAMS", 2)
        self.add_paragraph("The following ER diagrams illustrate the relationships between the main entities in the system.")
        
        self.add_heading("4.1 Customer-Related Entities", 3)
        self.add_text("```")
        self.add_text("┌───────────────┐       ┌───────────────┐")
        self.add_text("│   customers   │       │  customer_    │")
        self.add_text("├───────────────┤       │  addresses    │")
        self.add_text("│ id            │       ├───────────────┤")
        self.add_text("│ first_name    │       │ id            │")
        self.add_text("│ last_name     │       │ customer_id   │◄─┐")
        self.add_text("│ email         │       │ type          │  │")
        self.add_text("│ phone         │       │ address_line_1│  │")
        self.add_text("│ password      │◄──────┤ city          │  │")
        self.add_text("│ status        │       │ state         │  │")
        self.add_text("│ created_at    │       │ postal_code   │  │")
        self.add_text("└───────────────┘       │ is_default    │  │")
        self.add_text("        ▲               └───────────────┘  │")
        self.add_text("        │                                  │")
        self.add_text("        │               ┌───────────────┐  │")
        self.add_text("        │               │     cart      │  │")
        self.add_text("        │               ├───────────────┤  │")
        self.add_text("        └───────────────┤ customer_id   │  │")
        self.add_text("                        │ session_id    │  │")
        self.add_text("                        │ product_id    │  │")
        self.add_text("                        │ quantity      │  │")
        self.add_text("                        └───────────────┘  │")
        self.add_text("                                           │")
        self.add_text("        ┌───────────────┐                  │")
        self.add_text("        │    orders     │                  │")
        self.add_text("        ├───────────────┤                  │")
        self.add_text("        │ id            │                  │")
        self.add_text("        │ order_number  │                  │")
        self.add_text("        │ customer_id   │◄─────────────────┘")
        self.add_text("        │ payment_status│")
        self.add_text("        │ order_status  │")
        self.add_text("        │ total_amount  │")
        self.add_text("        └───────────────┘")
        self.add_text("                ▲")
        self.add_text("                │")
        self.add_text("        ┌───────────────┐")
        self.add_text("        │  order_items  │")
        self.add_text("        ├───────────────┤")
        self.add_text("        │ id            │")
        self.add_text("        │ order_id      │")
        self.add_text("        │ product_id    │")
        self.add_text("        │ quantity      │")
        self.add_text("        │ price         │")
        self.add_text("        └───────────────┘")
        self.add_text("```")
        
        # Create ER diagram for PDF - Customer entities
        fig, ax = plt.figure(figsize=(8, 6)), plt.gca()
        ax.axis('off')
        
        # Create a directed graph
        G = nx.DiGraph()
        
        # Add nodes
        G.add_node("customers", pos=(0, 2))
        G.add_node("customer_addresses", pos=(3, 2))
        G.add_node("cart", pos=(1.5, 1))
        G.add_node("orders", pos=(1.5, 0))
        G.add_node("order_items", pos=(1.5, -1))
        
        # Add edges
        G.add_edge("customers", "customer_addresses", label="1:N")
        G.add_edge("customers", "cart", label="1:N")
        G.add_edge("customers", "orders", label="1:N")
        G.add_edge("orders", "order_items", label="1:N")
        
        # Get positions
        pos = nx.get_node_attributes(G, 'pos')
        
        # Draw nodes
        nx.draw_networkx_nodes(G, pos, node_size=2000, node_color='lightblue', alpha=0.8)
        
        # Draw edges
        nx.draw_networkx_edges(G, pos, width=1.5, arrowsize=20, alpha=0.7)
        
        # Draw labels
        nx.draw_networkx_labels(G, pos, font_size=10, font_family='sans-serif')
        
        # Draw edge labels
        edge_labels = nx.get_edge_attributes(G, 'label')
        nx.draw_networkx_edge_labels(G, pos, edge_labels=edge_labels, font_size=8)
        
        plt.title('Customer-Related Entities ER Diagram')
        
        # Save the figure to a buffer
        buf = io.BytesIO()
        plt.savefig(buf, format='png', dpi=300, bbox_inches='tight')
        buf.seek(0)
        
        # Add the image to the PDF
        img = Image(buf, width=6*inch, height=4*inch)
        self.pdf_elements.append(img)
        
        plt.close()
        
        self.add_heading("4.2 Product-Related Entities", 3)
        self.add_text("```")
        self.add_text("┌───────────────┐       ┌───────────────┐")
        self.add_text("│  categories   │       │   products    │")
        self.add_text("├───────────────┤       ├───────────────┤")
        self.add_text("│ id            │       │ id            │")
        self.add_text("│ name          │       │ name          │")
        self.add_text("│ slug          │       │ slug          │")
        self.add_text("│ description   │◄──────┤ category_id   │")
        self.add_text("│ parent_id     │       │ price         │")
        self.add_text("│ status        │       │ sale_price    │")
        self.add_text("└───────────────┘       │ stock_quantity│")
        self.add_text("                        │ condition     │")
        self.add_text("                        │ size          │")
        self.add_text("                        │ color         │")
        self.add_text("                        │ brand         │")
        self.add_text("                        │ status        │")
        self.add_text("                        └───────────────┘")
        self.add_text("                                ▲")
        self.add_text("                                │")
        self.add_text("                        ┌───────────────┐")
        self.add_text("                        │ product_images│")
        self.add_text("                        ├───────────────┤")
        self.add_text("                        │ id            │")
        self.add_text("                        │ product_id    │")
        self.add_text("                        │ image_path    │")
        self.add_text("                        │ is_primary    │")
        self.add_text("                        │ sort_order    │")
        self.add_text("                        └───────────────┘")
        self.add_text("```")
        
        # Create ER diagram for PDF - Product entities
        fig, ax = plt.figure(figsize=(8, 6)), plt.gca()
        ax.axis('off')
        
        # Create a directed graph
        G = nx.DiGraph()
        
        # Add nodes
        G.add_node("categories", pos=(0, 1))
        G.add_node("products", pos=(2, 1))
        G.add_node("product_images", pos=(2, 0))
        
        # Add edges
        G.add_edge("categories", "products", label="1:N")
        G.add_edge("products", "product_images", label="1:N")
        
        # Get positions
        pos = nx.get_node_attributes(G, 'pos')
        
        # Draw nodes
        nx.draw_networkx_nodes(G, pos, node_size=2000, node_color='lightgreen', alpha=0.8)
        
        # Draw edges
        nx.draw_networkx_edges(G, pos, width=1.5, arrowsize=20, alpha=0.7)
        
        # Draw labels
        nx.draw_networkx_labels(G, pos, font_size=10, font_family='sans-serif')
        
        # Draw edge labels
        edge_labels = nx.get_edge_attributes(G, 'label')
        nx.draw_networkx_edge_labels(G, pos, edge_labels=edge_labels, font_size=8)
        
        plt.title('Product-Related Entities ER Diagram')
        
        # Save the figure to a buffer
        buf = io.BytesIO()
        plt.savefig(buf, format='png', dpi=300, bbox_inches='tight')
        buf.seek(0)
        
        # Add the image to the PDF
        img = Image(buf, width=6*inch, height=4*inch)
        self.pdf_elements.append(img)
        
        plt.close()
        
        self.add_heading("4.3 Admin-Related Entities", 3)
        self.add_text("```")
        self.add_text("┌───────────────┐       ┌───────────────┐")
        self.add_text("│  admin_users  │       │ activity_log  │")
        self.add_text("├───────────────┤       ├───────────────┤")
        self.add_text("│ id            │       │ id            │")
        self.add_text("│ username      │       │ user_type     │")
        self.add_text("│ email         │◄──────┤ user_id       │")
        self.add_text("│ password      │       │ action        │")
        self.add_text("│ full_name     │       │ description   │")
        self.add_text("│ role          │       │ created_at    │")
        self.add_text("│ status        │       └───────────────┘")
        self.add_text("│ last_login    │")
        self.add_text("└───────────────┘")
        self.add_text("```")
        
    def generate_flow_charts(self):
        """Generate flow charts section"""
        self.add_heading("5. FLOW CHARTS", 2)
        self.add_paragraph("The following flow charts illustrate the main processes in the system.")
        
        self.add_heading("5.1 User Registration Process", 3)
        self.add_text("```")
        self.add_text("┌─────────────┐     ┌─────────────┐     ┌─────────────┐")
        self.add_text("│  User fills │     │  Validate   │     │  Create     │")
        self.add_text("│ registration│────►│   form      │────►│  account    │")
        self.add_text("│    form     │     │   data      │     │             │")
        self.add_text("└─────────────┘     └──────┬──────┘     └──────┬──────┘")
        self.add_text("                           │                   │")
        self.add_text("                           │                   ▼")
        self.add_text("                    ┌──────▼──────┐     ┌──────────────┐")
        self.add_text("                    │   Display   │     │  Auto-login  │")
        self.add_text("                    │ validation  │     │   user &     │")
        self.add_text("                    │   errors    │     │ redirect to  │")
        self.add_text("                    └─────────────┘     │   profile    │")
        self.add_text("                                        └──────────────┘")
        self.add_text("```")
        
        # Create flowchart for PDF - User Registration
        fig, ax = plt.figure(figsize=(8, 6)), plt.gca()
        ax.axis('off')
        
        # Create nodes
        nodes = {
            'fill_form': (0.2, 0.8, 'User fills\nregistration form'),
            'validate': (0.5, 0.8, 'Validate\nform data'),
            'create_account': (0.8, 0.8, 'Create\naccount'),
            'display_errors': (0.5, 0.4, 'Display\nvalidation errors'),
            'auto_login': (0.8, 0.4, 'Auto-login user &\nredirect to profile')
        }
        
        # Draw boxes
        for name, (x, y, label) in nodes.items():
            rect = plt.Rectangle((x-0.1, y-0.1), 0.2, 0.2, fill=True, 
                                facecolor='lightblue', edgecolor='black', alpha=0.7)
            ax.add_patch(rect)
            ax.text(x, y, label, ha='center', va='center', fontsize=9)
        
        # Draw arrows
        arrow_style = dict(arrowstyle='->', color='black', lw=1.5)
        
        # Success path
        ax.add_patch(FancyArrowPatch(
            (nodes['fill_form'][0]+0.1, nodes['fill_form'][1]), 
            (nodes['validate'][0]-0.1, nodes['validate'][1]), 
            connectionstyle='arc3,rad=0', **arrow_style))
        
        ax.add_patch(FancyArrowPatch(
            (nodes['validate'][0]+0.1, nodes['validate'][1]), 
            (nodes['create_account'][0]-0.1, nodes['create_account'][1]), 
            connectionstyle='arc3,rad=0', **arrow_style))
        
        ax.add_patch(FancyArrowPatch(
            (nodes['create_account'][0], nodes['create_account'][1]-0.1), 
            (nodes['auto_login'][0], nodes['auto_login'][1]+0.1), 
            connectionstyle='arc3,rad=0', **arrow_style))
        
        # Error path
        ax.add_patch(FancyArrowPatch(
            (nodes['validate'][0], nodes['validate'][1]-0.1), 
            (nodes['display_errors'][0], nodes['display_errors'][1]+0.1), 
            connectionstyle='arc3,rad=0', **arrow_style))
        
        plt.title('User Registration Process')
        
        # Save the figure to a buffer
        buf = io.BytesIO()
        plt.savefig(buf, format='png', dpi=300, bbox_inches='tight')
        buf.seek(0)
        
        # Add the image to the PDF
        img = Image(buf, width=6*inch, height=4*inch)
        self.pdf_elements.append(img)
        
        plt.close()
        
        self.add_heading("5.2 Checkout Process", 3)
        self.add_text("```")
        self.add_text("┌─────────────┐     ┌─────────────┐     ┌─────────────┐")
        self.add_text("│  User views │     │  User       │     │  User fills │")
        self.add_text("│  cart and   │────►│ proceeds to │────►│  shipping & │")
        self.add_text("│  confirms   │     │  checkout   │     │  billing    │")
        self.add_text("└─────────────┘     └─────────────┘     └──────┬──────┘")
        self.add_text("                                               │")
        self.add_text("┌─────────────┐     ┌─────────────┐     ┌──────▼──────┐")
        self.add_text("│  Order      │     │  User       │     │  System     │")
        self.add_text("│ confirmation│◄────│ reviews     │◄────│ validates   │")
        self.add_text("│  page       │     │ order       │     │ information │")
        self.add_text("└──────┬──────┘     └─────────────┘     └─────────────┘")
        self.add_text("       │")
        self.add_text("       ▼")
        self.add_text("┌─────────────┐")
        self.add_text("│  Order      │")
        self.add_text("│ processing  │")
        self.add_text("│ begins      │")
        self.add_text("└─────────────┘")
        self.add_text("```")
        
        self.add_heading("5.3 Admin Order Management Process", 3)
        self.add_text("```")
        self.add_text("┌─────────────┐     ┌─────────────┐     ┌─────────────┐")
        self.add_text("│  Admin      │     │  Admin      │     │  Admin      │")
        self.add_text("│  views      │────►│ views order │────►│ updates     │")
        self.add_text("│  orders     │     │  details    │     │ order status│")
        self.add_text("└─────────────┘     └─────────────┘     └──────┬──────┘")
        self.add_text("                                               │")
        self.add_text("┌─────────────┐     ┌─────────────┐     ┌──────▼──────┐")
        self.add_text("│  System     │     │  System     │     │  System     │")
        self.add_text("│ notifies    │◄────│ logs        │◄────│ saves       │")
        self.add_text("│ customer    │     │ activity    │     │ changes     │")
        self.add_text("└─────────────┘     └─────────────┘     └─────────────┘")
        self.add_text("```")
        
    def generate_ui_ux_outline(self):
        """Generate UI/UX outline section"""
        self.add_heading("6. UI/UX OUTLINE", 2)
        self.add_paragraph("The ShaiBha platform features a clean, elegant design with a focus on showcasing the pre-loved fashion items. The UI/UX is designed to be intuitive and responsive.")
        
        self.add_heading("6.1 Frontend Pages", 3)
        self.add_bullet("Home Page")
        self.add_text("    - Hero section with featured collections")
        self.add_text("    - Featured categories section")
        self.add_text("    - Featured products section")
        self.add_text("    - Sustainability message section")
        
        self.add_bullet("Shop Page")
        self.add_text("    - Product grid with filtering options")
        self.add_text("    - Sorting controls")
        self.add_text("    - Pagination")
        
        self.add_bullet("Product Detail Page")
        self.add_text("    - Product images gallery")
        self.add_text("    - Product information (name, price, condition, etc.)")
        self.add_text("    - Product description tabs")
        self.add_text("    - Add to cart functionality")
        self.add_text("    - Related products")
        
        self.add_bullet("Cart Page")
        self.add_text("    - Cart items list")
        self.add_text("    - Quantity adjustment")
        self.add_text("    - Cart summary")
        self.add_text("    - Proceed to checkout button")
        
        self.add_bullet("Checkout Page")
        self.add_text("    - Shipping and billing information forms")
        self.add_text("    - Order summary")
        self.add_text("    - Payment method selection (COD)")
        
        self.add_bullet("User Account Pages")
        self.add_text("    - Profile information")
        self.add_text("    - Order history")
        self.add_text("    - Address management")
        
        self.add_heading("6.2 Admin Panel Pages", 3)
        self.add_bullet("Dashboard")
        self.add_text("    - Key metrics and statistics")
        self.add_text("    - Recent orders")
        self.add_text("    - Low stock alerts")
        
        self.add_bullet("Products Management")
        self.add_text("    - Product listing with filtering and search")
        self.add_text("    - Add/Edit product forms")
        self.add_text("    - Product image management")
        
        self.add_bullet("Orders Management")
        self.add_text("    - Order listing with filtering and search")
        self.add_text("    - Order details view")
        self.add_text("    - Order status updates")
        
        self.add_bullet("Customers Management")
        self.add_text("    - Customer listing with filtering and search")
        self.add_text("    - Customer details view")
        self.add_text("    - Customer order history")
        
        self.add_bullet("Reports")
        self.add_text("    - Sales reports")
        self.add_text("    - Inventory reports")
        
        self.add_heading("6.3 Design Elements", 3)
        self.add_bullet("Color Scheme:")
        self.add_text("    - Primary: Black (#000000)")
        self.add_text("    - Secondary: White (#FFFFFF)")
        self.add_text("    - Accents: Shades of gray")
        
        self.add_bullet("Typography:")
        self.add_text("    - Primary Font: Inter (sans-serif)")
        self.add_text("    - Display Font: Playfair Display (serif)")
        
        self.add_bullet("UI Components:")
        self.add_text("    - Glass morphism effects for cards")
        self.add_text("    - Subtle animations for interactions")
        self.add_text("    - Responsive grid layouts")
        self.add_text("    - Consistent button styles")
        
        # Create mockup for PDF
        self.add_spacer()
        self.pdf_elements.append(Paragraph("Sample UI Mockups:", self.styles['Heading3']))
        
        # Create a simple mockup of the home page
        fig, ax = plt.figure(figsize=(8, 6)), plt.gca()
        ax.axis('off')
        
        # Draw header
        header = plt.Rectangle((0.1, 0.9), 0.8, 0.08, fill=True, 
                            facecolor='black', edgecolor='black')
        ax.add_patch(header)
        ax.text(0.2, 0.94, 'ShaiBha', color='white', fontsize=14, fontweight='bold')
        
        # Draw navigation
        nav_items = ['Home', 'Shop', 'About', 'Contact']
        for i, item in enumerate(nav_items):
            ax.text(0.3 + i*0.1, 0.94, item, color='white', fontsize=10)
        
        # Draw hero section
        hero = plt.Rectangle((0.1, 0.6), 0.8, 0.28, fill=True, 
                            facecolor='lightgray', edgecolor='gray')
        ax.add_patch(hero)
        ax.text(0.2, 0.8, 'Pre-loved Fashion\nReimagined', fontsize=16, fontweight='bold')
        ax.text(0.2, 0.7, 'Discover unique, sustainable fashion pieces\nthat tell a story.', fontsize=10)
        
        # Draw button
        button = plt.Rectangle((0.2, 0.63), 0.2, 0.05, fill=True, 
                            facecolor='black', edgecolor='black')
        ax.add_patch(button)
        ax.text(0.3, 0.655, 'Shop Now', color='white', fontsize=10, ha='center')
        
        # Draw featured categories
        ax.text(0.5, 0.55, 'Featured Categories', fontsize=14, fontweight='bold', ha='center')
        
        for i in range(4):
            category = plt.Rectangle((0.1 + i*0.21, 0.35), 0.18, 0.18, fill=True, 
                                    facecolor='lightgray', edgecolor='gray')
            ax.add_patch(category)
            ax.text(0.19 + i*0.21, 0.44, f'Category {i+1}', fontsize=10, ha='center')
        
        # Draw featured products
        ax.text(0.5, 0.3, 'Featured Products', fontsize=14, fontweight='bold', ha='center')
        
        for i in range(3):
            product = plt.Rectangle((0.1 + i*0.28, 0.1), 0.25, 0.18, fill=True, 
                                    facecolor='lightgray', edgecolor='gray')
            ax.add_patch(product)
            ax.text(0.225 + i*0.28, 0.19, f'Product {i+1}', fontsize=10, ha='center')
            ax.text(0.225 + i*0.28, 0.15, f'₹{1999 + i*1000}', fontsize=10, ha='center')
        
        plt.title('Home Page UI Mockup')
        
        # Save the figure to a buffer
        buf = io.BytesIO()
        plt.savefig(buf, format='png', dpi=300, bbox_inches='tight')
        buf.seek(0)
        
        # Add the image to the PDF
        img = Image(buf, width=6*inch, height=4*inch)
        self.pdf_elements.append(img)
        
        plt.close()
        
    def generate_implementation_plan(self):
        """Generate implementation plan section"""
        self.add_heading("7. IMPLEMENTATION PLAN", 2)
        self.add_paragraph("The implementation of the ShaiBha platform will be divided into the following phases:")
        
        self.add_heading("7.1 Phase 1: Foundation", 3)
        self.add_bullet("Database schema setup")
        self.add_bullet("Basic frontend structure and styling")
        self.add_bullet("User authentication system")
        self.add_bullet("Admin authentication system")
        self.add_bullet("Core file structure and organization")
        self.add_bullet("Basic security implementation")
        
        self.add_heading("7.2 Phase 2: Core E-commerce Functionality", 3)
        self.add_bullet("Product catalog and browsing")
        self.add_bullet("Product detail pages")
        self.add_bullet("Shopping cart functionality")
        self.add_bullet("Checkout process")
        self.add_bullet("Order management")
        self.add_bullet("Basic search functionality")
        
        self.add_heading("7.3 Phase 3: Admin Functionality", 3)
        self.add_bullet("Admin dashboard")
        self.add_bullet("Product management")
        self.add_bullet("Order management")
        self.add_bullet("Customer management")
        self.add_bullet("Basic reporting")
        self.add_bullet("Activity logging")
        
        self.add_heading("7.4 Phase 4: Enhancements", 3)
        self.add_bullet("User profiles and account management")
        self.add_bullet("Advanced search and filtering")
        self.add_bullet("Responsive design optimizations")
        self.add_bullet("Performance optimizations")
        self.add_bullet("SEO improvements")
        self.add_bullet("Content pages (About, Contact, etc.)")
        
        self.add_heading("7.5 Phase 5: Future Expansions", 3)
        self.add_bullet("Payment gateway integration")
        self.add_bullet("Email notifications")
        self.add_bullet("Wishlist functionality")
        self.add_bullet("Product reviews")
        self.add_bullet("Advanced reporting")
        self.add_bullet("Social media integration")
        
        # Create implementation timeline for PDF
        self.add_spacer()
        self.pdf_elements.append(Paragraph("Implementation Timeline:", self.styles['Heading3']))
        
        # Create a Gantt chart
        fig, ax = plt.figure(figsize=(8, 4)), plt.gca()
        
        # Define phases and their durations
        phases = [
            "Phase 1: Foundation",
            "Phase 2: Core E-commerce",
            "Phase 3: Admin Functionality",
            "Phase 4: Enhancements",
            "Phase 5: Future Expansions"
        ]
        
        start_times = [0, 3, 6, 9, 12]
        durations = [4, 4, 4, 4, 4]
        
        # Create horizontal bars
        y_positions = np.arange(len(phases))
        ax.barh(y_positions, durations, left=start_times, height=0.5, 
                color=['lightblue', 'lightgreen', 'lightsalmon', 'lightpink', 'wheat'])
        
        # Customize the plot
        ax.set_yticks(y_positions)
        ax.set_yticklabels(phases)
        ax.set_xlabel('Weeks')
        ax.set_title('Implementation Timeline')
        ax.grid(axis='x', linestyle='--', alpha=0.7)
        
        # Add text labels
        for i, (start, duration) in enumerate(zip(start_times, durations)):
            ax.text(start + duration/2, i, f"{duration} weeks", 
                    ha='center', va='center', color='black')
        
        # Save the figure to a buffer
        buf = io.BytesIO()
        plt.savefig(buf, format='png', dpi=300, bbox_inches='tight')
        buf.seek(0)
        
        # Add the image to the PDF
        img = Image(buf, width=6*inch, height=3*inch)
        self.pdf_elements.append(img)
        
        plt.close()
        
    def generate_technical_specifications(self):
        """Generate technical specifications section"""
        self.add_heading("8. TECHNICAL SPECIFICATIONS", 2)
        
        self.add_heading("8.1 File Structure", 3)
        self.add_bullet("/admin - Admin panel files")
        self.add_bullet("/cart - Shopping cart functionality")
        self.add_bullet("/config - Configuration files")
        self.add_bullet("/css - Stylesheet files")
        self.add_bullet("/customer - Customer account functionality")
        self.add_bullet("/images - Image assets")
        self.add_bullet("/includes - Reusable PHP components")
        self.add_bullet("/js - JavaScript files")
        self.add_bullet("/pages - Static pages")
        self.add_bullet("/services - Service pages")
        self.add_bullet("/shop - Shop functionality")
        self.add_bullet("/uploads - User uploaded content")
        
        self.add_heading("8.2 Security Measures", 3)
        self.add_bullet("Password hashing using PHP's password_hash()")
        self.add_bullet("CSRF token protection for forms")
        self.add_bullet("Input sanitization and validation")
        self.add_bullet("Prepared statements for database queries")
        self.add_bullet("Session management and security")
        self.add_bullet("XSS prevention through output escaping")
        self.add_bullet("Secure cookie settings")
        self.add_bullet("Rate limiting for login attempts")
        
        self.add_heading("8.3 Performance Considerations", 3)
        self.add_bullet("Image optimization")
        self.add_bullet("Database indexing")
        self.add_bullet("Caching strategies")
        self.add_bullet("Lazy loading of images")
        self.add_bullet("Code minification")
        self.add_bullet("Efficient database queries")
        self.add_bullet("Pagination for large data sets")
        
        self.add_heading("8.4 Responsive Design Approach", 3)
        self.add_bullet("Mobile-first design philosophy")
        self.add_bullet("Fluid grid layouts")
        self.add_bullet("Flexible images and media")
        self.add_bullet("CSS media queries for breakpoints")
        self.add_bullet("Touch-friendly interface elements")
        self.add_bullet("Optimized navigation for mobile devices")
        
        self.add_heading("8.5 Browser Compatibility", 3)
        self.add_bullet("Chrome (latest 2 versions)")
        self.add_bullet("Firefox (latest 2 versions)")
        self.add_bullet("Safari (latest 2 versions)")
        self.add_bullet("Edge (latest 2 versions)")
        self.add_bullet("Opera (latest 2 versions)")
        self.add_bullet("Mobile browsers (iOS Safari, Android Chrome)")
        
    def generate_conclusion(self):
        """Generate conclusion section"""
        self.add_heading("9. CONCLUSION", 2)
        self.add_paragraph("The ShaiBha pre-loved fashion e-commerce platform is designed to provide a seamless shopping experience for users interested in sustainable fashion. With its comprehensive feature set, intuitive user interface, and robust admin capabilities, the platform aims to make pre-loved fashion accessible while promoting sustainability in the fashion industry.")
        
        self.add_paragraph("The platform's focus on quality curation, detailed condition descriptions, and transparent business practices will help build trust with customers and establish ShaiBha as a reputable marketplace for pre-loved fashion items.")
        
        self.add_paragraph("By implementing the platform in phases, we ensure a systematic approach to development, allowing for testing and refinement at each stage. The modular architecture also facilitates future expansions and integrations as the business grows.")
        
        self.add_paragraph("The success of ShaiBha will be measured not only by its technical performance but also by its contribution to sustainable fashion practices and customer satisfaction. By creating a platform that makes it easy and enjoyable to shop for pre-loved fashion, ShaiBha aims to change consumer behavior and promote a more sustainable approach to fashion consumption.")
        
    def save_text_file(self):
        """Save the text content to a file"""
        with open('shaibha_project_outline.txt', 'w') as f:
            for line in self.text_content:
                f.write(line + '\n')
        print(f"Text file saved: shaibha_project_outline.txt")
        
    def save_pdf_file(self):
        """Save the PDF content to a file"""
        doc = SimpleDocTemplate(
            "shaibha_project_outline.pdf",
            pagesize=A4,
            rightMargin=72,
            leftMargin=72,
            topMargin=72,
            bottomMargin=72
        )
        
        doc.build(self.pdf_elements)
        print(f"PDF file saved: shaibha_project_outline.pdf")

# Create and run the generator
if __name__ == "__main__":
    generator = ProjectOutlineGenerator()
    generator.generate_outline()