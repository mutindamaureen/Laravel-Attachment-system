    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-graduation-cap"></i> SAMS</h5>
                    <p>Student Attachment Management System - Empowering education through efficient industrial attachment management.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <p><a href="#home">Home</a></p>
                    <p><a href="#services">Services</a></p>
                    <p><a href="#about">About</a></p>
                    <p><a href="{{ route('login') }}">Login</a></p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact Us</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> 123 University Road, Nairobi, Kenya</p>
                    <p><i class="fas fa-phone me-2"></i> +254 700 000 000</p>
                    <p><i class="fas fa-envelope me-2"></i> info@sams.edu</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Student Attachment Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
