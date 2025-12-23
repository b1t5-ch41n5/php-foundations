<!DOCTYPE html>
<html lang="en">
<head>
    <!-- 🌐 Meta tags for responsive design -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form - b1t5 ch41n5</title>

    <!-- 🎨 Bootstrap 5 CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- 📞 International Telephone Input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    
    <!-- 🖌️ Hacker Theme Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Fira+Code:wght@300;400;500;700&display=swap');
        
        body {
            /* 🖼️ Background image for hacker theme */
            background: url('../css/img/anon.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Fira Code', monospace;
            color: #00ff41; /* 💚 Hacker green */
        }
        body::before {
            /* 🎥 Overlay with a GIF for a hacker vibe */
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://media.giphy.com/media/oEI9uBYSzLpBK/giphy.gif') center center / cover no-repeat;
            opacity: 0.1;
            z-index: -1;
        }
        .hacker-overlay {
            /* 🎨 Gradient overlay for aesthetic */
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: linear-gradient(135deg, rgba(10,10,26,0.85) 0%, rgba(26,26,58,0.85) 50%, rgba(42,42,90,0.85) 100%);
            z-index: -2;
        }
        .contact-container {
            /* 📏 Center the contact form */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
        }
        .hacker-card {
            background: rgba(0, 0, 0, 0.95);
            border: 3px solid #00bfff;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 191, 255, 0.5), inset 0 0 30px rgba(0, 191, 255, 0.2), 0 0 60px rgba(0, 255, 65, 0.3);
            backdrop-filter: blur(15px);
            max-width: 800px;
            width: 100%;
        }
        .hacker-header {
            background: linear-gradient(45deg, #00bfff, #0099cc, #00ffff);
            color: #000;
            text-align: center;
            padding: 25px;
            border-radius: 12px 12px 0 0;
            font-weight: 900;
            font-family: 'Orbitron', monospace;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .contact-form {
            padding: 30px;
        }
        .form-label {
            color: #00ffff !important;
            font-weight: 700 !important;
            font-family: 'Orbitron', monospace !important;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .form-control {
            background: rgba(0, 0, 0, 0.9);
            border: 2px solid #00bfff;
            color: #00ffff !important;
            border-radius: 10px;
            font-family: 'Fira Code', monospace !important;
        }
        .form-control:focus {
            background: rgba(0, 0, 0, 0.95) !important;
            border-color: #00ffff !important;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.8), inset 0 0 20px rgba(0, 255, 255, 0.2) !important;
        }
        .form-control::placeholder {
            color: rgba(0, 191, 255, 0.7) !important;
        }
        .btn-hacker {
            background: linear-gradient(45deg, #00bfff, #0099cc, #00ffff);
            border: none;
            color: #000 !important;
            font-weight: 900 !important;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-hacker:hover {
            box-shadow: 0 0 25px rgba(0, 191, 255, 0.8);
            transform: scale(1.05);
        }
        .iti {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="hacker-overlay"></div>
    <div class="contact-container">
        <div class="hacker-card">
            <div class="hacker-header">
                <h3><i class="bi bi-envelope-at-fill"></i> Secure Transmission</h3>
            </div>
            <div class="contact-form">
                <form id="contact-form" action="process-contact.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Handle / Alias</label>
                            <input type="text" id="name" name="name" class="form-control" required placeholder="Enter your callsign">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Secure Email</label>
                            <input type="email" id="email" name="email" class="form-control" required placeholder="your.address@domain.sec">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Encoded Phone Line</label>
                            <input id="phone" type="tel" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" required placeholder="Transmission Subject">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea id="message" name="message" rows="5" class="form-control" required placeholder="Compose your encrypted message..."></textarea>
                    </div>

                    <!-- Hidden fields for geolocation -->
                    <input type="hidden" id="user_ip" name="user_ip">
                    <input type="hidden" id="user_country" name="user_country">
                    <input type="hidden" id="user_city" name="user_city">
                    <input type="hidden" id="user_region" name="user_region">
                    <input type="hidden" id="user_timezone" name="user_timezone">

                    <div class="text-center mt-4">
                        <button class="btn btn-hacker" type="submit">Transmit Message</button>
                        <button class="btn btn-warning" type="reset" style="margin-left: 10px;">Clear Fields</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- International Telephone Input JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Configure international telephone input
            var input = document.querySelector("#phone");
            var iti = window.intlTelInput(input, {
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                separateDialCode: true,
                initialCountry: "auto",
                geoIpLookup: function (callback) {
                    fetch("https://ipapi.co/json")
                        .then(function (res) { return res.json(); })
                        .then(function (data) { callback(data.country_code); })
                        .catch(function () { callback("us"); });
                }
            });

            // Validate phone number on form submission
            var form = document.querySelector("#contact-form");
            form.addEventListener('submit', function (e) {
                if (input.value.trim() !== "" && !iti.isValidNumber()) {
                    e.preventDefault();
                    alert("Please enter a valid phone number.");
                    return false;
                }
            });

            // Get geolocation information
            fetch('https://ipapi.co/json/')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('user_ip').value = data.ip || '';
                    document.getElementById('user_country').value = data.country_name || '';
                    document.getElementById('user_city').value = data.city || '';
                    document.getElementById('user_region').value = data.region || '';
                    document.getElementById('user_timezone').value = data.timezone || '';
                })
                .catch(error => {
                    console.log('Error getting geolocation:', error);
                });
        });
    </script>
</body>
</html>
