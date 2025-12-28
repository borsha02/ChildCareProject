<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/help.css'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-baby"></i>
                    <h2>Childcare</h2>
                </div>
                <div class="user-info">
                    <div class="user-details">
                        <h4>{{Auth::user()->name}}</h4>
                        <p>Parent Account</p>
                    </div>
                </div>
            </div>

            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('parent.dashboard') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('parent.child-profile') }}" class="nav-item">
                        <i class="fas fa-child"></i>
                        <span>Child Profile</span>
                    </a>
                    <a href="{{ route('parent.attendance') }}" class="nav-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('parent.reports') }}" class="nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Reports</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>
                    <a href="{{ route('parent.messages') }}" class="nav-item">
                        <i class="fas fa-comments"></i>
                        <span>Messages</span>
                        <span class="badge">3</span>
                    </a>
                    <a href="{{ route('parent.notifications') }}" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span class="badge">5</span>
                    </a>
                    <a href="{{ route('parent.events') }}" class="nav-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Services</div>
                    <a href="{{ route('parent.health') }}" class="nav-item">
                        <i class="fas fa-heartbeat"></i>
                        <span>Health Records</span>
                    </a>
                    <a href="{{ route('parent.invoice') }}" class="nav-item">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Billing & Invoices</span>
                    </a>
                    <a href="{{ route('parent.caregivers') }}" class="nav-item">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Assigned Caregivers</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Account</div>
                    <a href="{{ route('parent.settings') }}" class="nav-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="{{ route('parent.help') }}" class="nav-item active">
                        <i class="fas fa-question-circle"></i>
                        <span>Help & Support</span>
                    </a>
                    <a href="{{ route('logout') }}" class="nav-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('parent.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Help & Support</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search help topics...">
                        <i class="fas fa-search"></i>
                    </div>
                    <a href="{{ route('parent.notifications') }}" class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot"></span>
                    </a>
                    <a href="{{ route('parent.messages') }}" class="icon-btn">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>

            <div class="content-area">
                <div class="help-container">
                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <a href="#faq" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-question"></i>
                            </div>
                            <h3>FAQs</h3>
                            <p>Find answers to commonly asked questions</p>
                        </a>
                        <a href="#contact" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3>Contact Support</h3>
                            <p>Get in touch with our support team</p>
                        </a>
                        <a href="#feedback" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-comment-dots"></i>
                            </div>
                            <h3>Send Feedback</h3>
                            <p>Share your thoughts and suggestions</p>
                        </a>
                    </div>

                    <!-- FAQ Section -->
                    <div class="faq-section" id="faq">
                        <div class="section-header">
                            <i class="fas fa-question-circle"></i>
                            <h2>Frequently Asked Questions</h2>
                        </div>
                        <div class="faq-list">
                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>How do I update my child's profile information?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <div class="faq-answer-content">
                                        To update your child's profile, navigate to the Child Profile page from the main menu. Click on the "Edit Profile" button, make your changes, and save. You can update information such as emergency contacts, medical details, and dietary preferences.
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>How can I view my child's attendance records?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <div class="faq-answer-content">
                                        Go to the Attendance page from the main menu. You'll see a calendar view showing all check-in and check-out times. You can filter by date range and download attendance reports for your records.
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>How do I pay my invoices online?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <div class="faq-answer-content">
                                        Visit the Billing & Invoices page to view all your invoices. Click on any unpaid invoice and select "Pay Now". You can pay using credit card, debit card, or bank transfer. All transactions are secure and encrypted.
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>How do I communicate with my child's teacher?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <div class="faq-answer-content">
                                        Use the Messages page to send direct messages to teachers and staff. You'll receive notifications when they reply. For urgent matters, you can also use the contact information provided in the Contact Support section.
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>Can I update my notification preferences?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <div class="faq-answer-content">
                                        Yes! Go to Settings > Notifications to customize which notifications you receive via email and push notifications. You can choose to receive updates about events, messages, payments, and more.
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>How do I reset my password?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <div class="faq-answer-content">
                                        Go to Settings > Security and click on "Change Password". Enter your current password and your new password. If you've forgotten your password, use the "Forgot Password" link on the login page to receive a reset email.
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>What should I do if I notice an error in my billing?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <div class="faq-answer-content">
                                        If you notice any discrepancies in your billing, please contact our support team immediately using the contact form below or call us directly. We'll review your account and resolve any issues promptly.
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>How can I view my child's health records?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <div class="faq-answer-content">
                                        Navigate to the Health Records page to view vaccination records, medical history, allergies, and any health-related notes from staff. You can also upload new medical documents and update health information.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Support Section -->
                    <div class="contact-section" id="contact">
                        <div class="section-header">
                            <i class="fas fa-headset"></i>
                            <h2>Contact Support</h2>
                        </div>

                        <div class="contact-methods">
                            <div class="contact-method">
                                <div class="contact-method-header">
                                    <div class="contact-method-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <h3>Phone Support</h3>
                                </div>
                                <p>Speak directly with our support team</p>
                                <a href="tel:+15551234567">
                                    +1 (555) 123-4567
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                                <p style="margin-top: 8px; font-size: 12px;">Mon-Fri: 8AM - 6PM EST</p>
                            </div>

                            <div class="contact-method">
                                <div class="contact-method-header">
                                    <div class="contact-method-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <h3>Email Support</h3>
                                </div>
                                <p>Send us an email and we'll respond within 24 hours</p>
                                <a href="mailto:support@childcare.com">
                                    support@childcare.com
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>

                            <div class="contact-method">
                                <div class="contact-method-header">
                                    <div class="contact-method-icon">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <h3>Live Chat</h3>
                                </div>
                                <p>Chat with us in real-time</p>
                                <a href="#" onclick="openLiveChat(); return false;">
                                    Start Chat
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                                <p style="margin-top: 8px; font-size: 12px;">Available 24/7</p>
                            </div>
                        </div>

                        <div class="contact-form" id="feedback">
                            <h3 style="margin-bottom: 20px; color: #1f2937; font-size: 18px;">Send us a Message</h3>
                            <form id="supportForm">
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <select id="subject" class="form-select" required>
                                        <option value="">Select a topic</option>
                                        <option value="billing">Billing & Payments</option>
                                        <option value="technical">Technical Issue</option>
                                        <option value="account">Account Management</option>
                                        <option value="feedback">Feedback & Suggestions</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="email">Your Email</label>
                                    <input type="email" id="email" class="form-input" placeholder="john.doe@example.com" required>
                                </div>

                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea id="message" class="form-textarea" placeholder="Describe your issue or question in detail..." required></textarea>
                                </div>

                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-paper-plane"></i>
                                    Send Message
                                </button>
                            </form>
                        </div>
                    </div>


                </div>
            </div>
        </main>
    </div>

    <script>
        // FAQ Accordion
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', function() {
                const faqItem = this.parentElement;
                const isActive = faqItem.classList.contains('active');

                // Close all FAQ items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                });

                // Open clicked item if it wasn't active
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });

        // Support Form Submission
        document.getElementById('supportForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = this.querySelector('.submit-btn');
            const originalContent = btn.innerHTML;

            // Show loading state
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            // Simulate API call
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = originalContent;

                // Show success message
                showToast('Message sent successfully! We\'ll get back to you soon.', 'success');

                // Reset form
                this.reset();
            }, 1500);
        });

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(toast);

            // Trigger reflow
            toast.offsetHeight;

            // Show toast
            toast.classList.add('show');

            // Hide after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        // Live Chat (placeholder)
        function openLiveChat() {
            showToast('Live chat feature coming soon!', 'success');
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href !== '#feedback') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });

        // Mobile menu toggle
        const mobileToggle = document.querySelector('.mobile-toggle');
        const sidebar = document.getElementById('sidebar');

        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>
