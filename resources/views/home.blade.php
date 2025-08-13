@include('layouts.header')
<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Dispatch <span class="text-primary">Smarter.</span> Close Loads <span
                        class="text-primary">Faster</span>
                </h1>
                <p class="lead text-muted mb-4">
                    Automate broker outreach, verify carriers, and calculate RPMs—right from your load board.
                </p>
                <div class="d-flex align-items-center gap-3">
                    <a href="#install" class="btn btn-dark btn-lg">
                        <i class="fab fa-chrome me-2"></i>Install for Free
                    </a>
                    <a href="#demo" class="text-decoration-underline text-muted">Demo in Action</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('build/assets/images/6.png') }}" class="img-fluid" alt="App Screenshot">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold mb-3">
                    Still copying broker info <span class="text-primary">manually?</span>
                </h2>
                <p class="lead text-muted">
                    Tired of switching tabs, pasting emails, and calculating mileage?
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="feature-card">
                    <img src="{{ asset('build/assets/images/2.jpg') }}" class="img-fluid mb-3"
                        alt="One-click email templates">
                    <h3 class="h4 fw-bold">One-click email templates</h3>
                    <p class="text-muted">Tired of switching tabs, pasting emails, and calculating mileage?</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="feature-card">
                    <img src="{{ asset('build/assets/images/3.jpg') }}" class="img-fluid mb-3"
                        alt="Route map and estimator">
                    <h3 class="h4 fw-bold">Route map + toll & diesel estimator</h3>
                    <p class="text-muted">Tired of switching tabs, pasting emails, and mileage?</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="feature-card">
                    <img src="{{ asset('build/assets/images/4.jpg') }}" class="img-fluid mb-3"
                        alt="Instant RPM calculator">
                    <h3 class="h4 fw-bold">Instant RPM calculator</h3>
                    <p class="text-muted">Tired of switching tabs, pasting emails, and calculating mileage?</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="feature-card">
                    <img src="{{ asset('build/assets/images/2.jpg') }}" class="img-fluid mb-3"
                        alt="Broker &amp; factoring status checker">
                    <h3 class="h4 fw-bold">Broker & factoring score checker</h3>
                    <p class="text-muted">Tired of switching tabs, pasting emails, and calculating mileage?</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works-section py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold mb-3">
                    Your Load Board, <span class="text-primary">Supercharged</span>
                </h2>
                <p class="lead text-muted">
                    Email brokers, calculate RPM, and verify leads — directly from the load board.
                </p>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <img src="{{ asset('build/assets/images/5.png') }}" class="img-fluid" alt="Load Board">
            </div>
            <div class="col-lg-6">
                <h3 class="h4 fw-bold mb-3">Browse Load Boards</h3>
                <p class="text-muted">
                    Open DAT, Truckstop, or Loadlink — the extension runs seamlessly in the background.
                </p>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-lg-6 order-lg-2">
                <img src="{{ asset('build/assets/images/8.png') }}" class="img-fluid" alt="Load Board">
            </div>
            <div class="col-lg-6 order-lg-1">
                <h3 class="h4 fw-bold mb-3">Click to Auto-Fill</h3>
                <p class="text-muted">
                    With one click, it pulls broker info and auto-generates a ready-to-send email using your saved
                    template.
                </p>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-6">
                <img src="{{ asset('build/assets/images/7.png') }}" class="img-fluid" alt="Load Board">
            </div>
            <div class="col-lg-6">
                <h3 class="h4 fw-bold mb-3">Verify & Decide</h3>
                <p class="text-muted">
                    Instantly see RPM, route distance, toll costs, and broker credibility — all without switching
                    tabs.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Integrations Section -->
<section class="integrations-section py-5">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Plug & Play with the Tools You Trust</h2>
        <p class="lead text-muted mb-5">
            Seamless integrations with your favorite load boards and tools — no setup, no friction.
        </p>

        <div class="row justify-content-center">
            <div class="col-md-3 col-6 mb-4">
                <img src="{{ asset('build/assets/images/9.png') }}" alt="Outlook">
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5 bg-light">
    <div class="container text-center">
        <h3 class="text-muted mb-5">What Users Are Saying</h3>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div id="testimonialCarousel" class="carousel slide testimonial-card" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="testimonial mb-3" style="font-size: 32px;font-weight: 500;">“Saves me at
                                least 2–3
                                hours every day. The one-click emails are a game changer.”</div>
                            <div class="fw-bold">- Emily</div>
                            <span>Dispatcher</span>
                        </div>
                        <div class="carousel-item">
                            <div class="testimonial mb-3" style="font-size: 32px;font-weight: 500;">"The RPM
                                calculator and
                                instant email templates make my job so much easier! Highly recommended."</div>
                            <div class="fw-bold">- John</div>
                            <span>Fleet Manager</span>
                        </div>
                        <div class="carousel-item">
                            <div class="testimonial mb-3" style="font-size: 32px;font-weight: 500;">"No more
                                copy-pasting!
                                The Chrome extension is a must-have for every dispatcher."</div>
                            <div class="fw-bold">- Sophia</div>
                            <span>Owner-Operator</span>
                        </div>
                    </div>
                    <button class="carousel-control-prev btn btn-primary rounded-circle" type="button"
                        data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                        <i class="fas fa-chevron-left"></i>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next btn btn-primary rounded-circle" type="button"
                        data-bs-target="#testimonialCarousel" data-bs-slide="next">
                        <i class="fas fa-chevron-right"></i>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section class="pricing-section py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold mb-3">
                    Still copying broker info <span class="text-primary">manually?</span>
                </h2>
                <p class="lead text-muted">
                    Tired of switching tabs, pasting emails, and calculating mileage?
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-4 mb-4">
                <div class="pricing-card">
                    <div class="pricing-header mb-3">
                        <span class="badge bg-light text-dark">Monthly</span>
                    </div>
                    <div class="pricing-price mb-3">
                        <span class="display-6 fw-bold">$11</span>
                        <span class="text-muted">/month</span>
                    </div>
                    <p class="text-muted small mb-4">
                        For experienced dispatchers: Make quick, smart decisions and book loads faster.
                    </p>
                    <a href="{{ url('/login') }}" class="btn btn-primary w-100 mb-3">Get This Plan</a>
                    <hr>
                    <div class="features-list">
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>Unlimited Emails</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>Email Template: Custom Template</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>Click to Call * Coming soon</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>RPM Calculator</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>Google Maps Integration * Coming soon</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>DAT, LoadLink</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>Sylectus, Truckstop * Coming Soon</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="pricing-card">
                    <div class="pricing-header mb-3">
                        <span class="badge bg-light text-dark">Yearly</span>
                    </div>
                    <div class="pricing-price mb-3">
                        <span class="display-6 fw-bold">$132</span>
                        <span class="text-muted">/year</span>
                    </div>
                    <p class="text-muted small mb-4">
                        Access Pro features for less Price in our Yearly Plan.
                    </p>
                    <a href="{{ url('/login') }}" class="btn btn-primary w-100 mb-3">Get This Plan</a>
                    <hr>
                    <div class="features-list">
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>Unlimited Emails</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>Email Template: Custom Template</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>Click to Call * Coming soon</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>RPM Calculator</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>Google Maps Integration * Coming soon</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>DAT, LoadLink</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <span>Sylectus, Truckstop * Coming Soon</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold mb-3">Frequently Asked Questions</h2>
                <p class="lead text-muted">
                    Tired of switching tabs, pasting emails, and calculating mileage?
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq1">
                                Does this work on all load boards?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes! It currently supports DAT, Truckstop, 123Loadboard, and Loadlink. We're adding
                                more based on user requests.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq2">
                                Can I use my own email templates?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, you can customize your email templates to match your brand and communication
                                style.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq3">
                                Do I need to copy and paste broker details?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                No! The extension automatically extracts broker information from the load board and
                                fills it into your templates.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq4">
                                How accurate is the RPM calculation?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Our RPM calculator uses real-time data and industry-standard formulas to provide
                                accurate calculations.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5">
    <div class="container text-center">
        <div class="cta-card">
            <h2 class="display-5 fw-bold mb-4">Ready to Dispatch Smarter?</h2>
            <p class="lead text-muted mb-5">
                Save time, close more loads, and never copy-paste again.<br>
                Install the Chrome extension and supercharge your workflow today.
            </p>
            <div class="d-flex flex-column align-items-center gap-3">
                <a href="#install" class="btn btn-dark btn-lg">
                    <i class="fab fa-chrome me-2"></i>Install for Free
                </a>
                <a href="#demo" class="text-decoration-underline text-muted">Demo in Action</a>
            </div>
        </div>
    </div>
</section>
@include('layouts.footer')
