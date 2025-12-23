<!-- ========================================
     HACKER FOOTER - STANDALONE VERSION
     ========================================
     This footer is designed to be included in other pages
     without interfering with the main page content
     ======================================== -->
<footer class="hacker-footer py-5">
    <!-- 🌌 Matrix background effect overlay -->
    <div class="matrix-bg"></div>
    
    <!-- 📦 Bootstrap container for responsive layout -->
    <div class="container position-relative">
        <!-- 🧱 Bootstrap row with gap between columns -->
        <div class="row g-4">
            
            <!-- 🏴‍☠️ B1T5 CH41N5 SECTION -->
            <div class="col-lg-4 col-md-6">
                <div class="text-center text-md-start">
                    <!-- ✨ Main brand logo with glitch effect -->
                    <h2 class="hacker-brand glitch-effect" data-text="B1T5 CH41N5">
                        B1T5 CH41N5
                    </h2>
                    <!-- 🖥️ Description with terminal-style text -->
                    <p class="terminal-text mb-3">
                        Elite hacker collective specializing in cybersecurity, penetration testing, and digital forensics.
                    </p>
                    <p class="terminal-text mb-3">
                        Established: <span class="neon-text"><?php echo date('Y'); ?></span>
                    </p>
                    <p class="terminal-text mb-3">
                        Status: <span class="neon-text">ONLINE</span>
                    </p>
                    <p class="terminal-text">
                        Access Level: <span style="color: #ff0080;">CLASSIFIED</span>
                    </p>
                    
                    <!-- 🔗 Social media icons section -->
                    <div class="social-hacker mt-4">
                        <a href="#" class="social-hacker-icon" title="GitHub Repository">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="#" class="social-hacker-icon" title="Discord Server">
                            <i class="fab fa-discord"></i>
                        </a>
                        <a href="#" class="social-hacker-icon" title="Telegram Channel">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="#" class="social-hacker-icon" title="Twitter/X">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                        <a href="#" class="social-hacker-icon" title="YouTube Channel">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- MODULES SECTION -->
            <div class="col-lg-4 col-md-6">
                <h5 class="neon-text mb-4">// MODULES</h5>
                <ul class="hacker-links">
                    <li><a href="#" title="Exploit Development">Exploits</a></li>
                    <li><a href="#" title="Payload Creation">Payloads</a></li>
                    <li><a href="#" title="Target Reconnaissance">Reconnaissance</a></li>
                    <li><a href="#" title="Social Engineering Tactics">Social Engineering</a></li>
                    <li><a href="#" title="Encryption & Decryption">Cryptography</a></li>
                    <li><a href="#" title="Network Traffic Analysis">Network Analysis</a></li>
                </ul>
            </div>

            <!-- CONNECT SECTION -->
            <div class="col-lg-4 col-md-6">
                <h5 class="neon-text mb-4">// CONNECT</h5>
                <p class="terminal-text mb-3">
                    Join our underground network for exclusive hacks, tools, and zero-day exploits.
                </p>
                <p class="terminal-text mb-3">
                    Get access to private repositories, advanced tutorials, and direct communication with elite hackers.
                </p>
                
                <!-- Contact us button -->
                <div class="mb-3">
                    
                    <button type="button" class="hack-btn w-100" onclick="window.location.href='includes/contact-form.php'">
                        <i class="fas fa-terminal me-2"></i><span style="color: white;">CONTACT US</span>
                    </button>                    
                </div>
                
                <!-- Contact information -->
                <div class="mt-4">
                    <p class="terminal-text mb-2">
                        <i class="fas fa-envelope me-2" style="color: #ff0080;"></i>
                        contact@b1t5ch41n5.net
                    </p>
                    <p class="terminal-text mb-2">
                        <i class="fas fa-shield-alt me-2" style="color: #ff0080;"></i>
                        PGP Key: 0x1337DEADBEEF
                    </p>
                    <p class="terminal-text">
                        <i class="fas fa-globe me-2" style="color: #ff0080;"></i>
                        Dark Web: b1t5ch41n5.onion
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer bottom section -->
        <div class="footer-bottom-hacker">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="copyright-hacker mb-2">
                        © <?php echo date('Y'); ?> B1T5 CH41N5. All rights reserved.
                    </p>
                    <p class="copyright-hacker">
                        <i class="fas fa-code me-1"></i>
                        Developed for educational purposes only
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end gap-3 flex-wrap">
                        <a href="#" class="copyright-hacker text-decoration-none" style="color: #666;">
                            Privacy Policy
                        </a>
                        <a href="#" class="copyright-hacker text-decoration-none" style="color: #666;">
                            Terms of Service
                        </a>
                        <a href="#" class="copyright-hacker text-decoration-none" style="color: #666;">
                            Code of Ethics
                        </a>
                    </div>
                    <p class="copyright-hacker mt-2">
                        <i class="fas fa-circle me-1" style="color: #00ff41; animation: pulse 2s infinite;"></i>
                        System Status: Operational
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript for footer functionality -->
<script>
    // Email subscription function
    function subscribeToNetwork() {
        const email = document.getElementById('hackerEmail').value;
        
        if (email) {
            const button = document.querySelector('.hack-btn');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>CONNECTING...';
            button.disabled = true;
            
            setTimeout(() => {
                button.innerHTML = '<i class="fas fa-check me-2"></i>CONNECTION ESTABLISHED';
                button.style.background = 'linear-gradient(45deg, #00ff41, #00ffff)';
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    button.style.background = 'linear-gradient(45deg, #ff0080, #00ff41)';
                    document.getElementById('hackerEmail').value = '';
                }, 3000);
        }, 2000);
        
        console.log('New hacker joined the network:', email);
    } else {
        alert('ERROR: Email address required for network access');
    }
}

    // Matrix rain effect
    function createMatrixRain() {
        const matrixBg = document.querySelector('.matrix-bg');
        if (matrixBg) {
            const characters = '01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン';
            
            for (let i = 0; i < 50; i++) {
                const span = document.createElement('span');
                span.textContent = characters[Math.floor(Math.random() * characters.length)];
                span.style.position = 'absolute';
                span.style.left = Math.random() * 100 + '%';
                span.style.top = Math.random() * 100 + '%';
                span.style.animationDelay = Math.random() * 2 + 's';
                span.style.opacity = Math.random() * 0.5;
                matrixBg.appendChild(span);
            }
        }
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        createMatrixRain();
        
        // Add pulse animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { opacity: 1; }
                50% { opacity: 0.5; }
                100% { opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    });
</script>

<!-- Bootstrap JavaScript for interactive components -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
