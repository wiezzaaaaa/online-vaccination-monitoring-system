<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main - Alawihao Health Center</title>
    <style>
        :root {
            --primary-green: #718355; 
            --bg-color: #f9fbf2; 
        }

        * {
            box-sizing: border-box;
        }

        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: var(--bg-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .app-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            width: 100%;
            padding: 20px 60px;
            display: flex;
            align-items: center;
            gap: 20px;
            background-color: #ffffff;
        }

        .profile-circle {
            width: 55px;
            height: 55px;
            background: #d9d9d9;
            border-radius: 50%;
        }

        .header h2 {
            margin: 0;
            font-weight: 600;
            color: #333;
            letter-spacing: 1px;
        }

        .green-bar {
            height: 25px;
            width: 100%;
            background-color: var(--primary-green);
        }

        /* Slider Section */
        .slider-wrapper {
            position: relative;
            width: 100%;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }

        .slider-container {
            width: 85%; 
            max-width: 1100px; 
            height: 500px; 
            overflow: hidden;
            border-radius: 15px;
            background-color: #fff;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        .slides {
            display: flex;
            height: 100%;
            transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .slides img {
            min-width: 100%;
            width: 100%;
            height: 100%; 
            object-fit: cover; 
            object-position: center;
        }

        /* Navigation Dots */
        .dots-container {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .dot {
            width: 14px;
            height: 14px;
            background-color: #ccc;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .dot.active {
            background-color: var(--primary-green);
            transform: scale(1.2);
        }

        .dot:hover {
            background-color: #999;
        }

        /* Footer */
        .footer {
            width: 100%;
            text-align: center;
            padding-bottom: 80px;
        }

        .register-btn {
            background-color: var(--primary-green);
            color: white;
            padding: 18px 110px;
            border: none;
            border-radius: 10px;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(113, 131, 85, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .register-btn:hover {
            background-color: #5a6b44;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(113, 131, 85, 0.4);
        }
    </style>
</head>
<body>

    <div class="app-container">
        <header class="header">
            <div class="profile-circle"></div>
            <h2>ALAWIHAO HEALTH CENTER</h2>
        </header>
        
        <div class="green-bar"></div>

        <main class="slider-wrapper">
            <div class="slider-container">
                <div class="slides" id="slide-track">
                    <img src="image/pic1.jpg" alt="Center Photo 1">
                    <img src="image/pic2.jpg" alt="Center Photo 2">
                    <img src="image/pic3.jpg" alt="Center Photo 3">
                    <img src="image/pic4.jpg" alt="Center Photo 4">
                    <img src="image/pic5.jpg" alt="Center Photo 5">
                </div>
            </div>

            <div class="dots-container" id="dots-container">
                <div class="dot active" onclick="jumpToSlide(0)"></div>
                <div class="dot" onclick="jumpToSlide(1)"></div>
                <div class="dot" onclick="jumpToSlide(2)"></div>
                <div class="dot" onclick="jumpToSlide(3)"></div>
                <div class="dot" onclick="jumpToSlide(4)"></div>
            </div>
        </main>

        <footer class="footer">
            <a href="register_child.php" class="register-btn">REGISTER MY CHILD</a>
        </footer>
    </div>

    <script>
        let currentIndex = 0;
        const track = document.getElementById('slide-track');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = 5;

        function updateSlider() {
            const offset = -currentIndex * 100;
            track.style.transform = `translateX(${offset}%)`;

            dots.forEach((dot, index) => {
                if (index === currentIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        function moveSlide(direction) {
            currentIndex += direction;

            if (currentIndex >= totalSlides) {
                currentIndex = 0;
            } else if (currentIndex < 0) {
                currentIndex = totalSlides - 1;
            }

            updateSlider();
        }

        function jumpToSlide(index) {
            currentIndex = index;
            updateSlider();
            resetTimer(); 
        }

        let autoSlide = setInterval(() => moveSlide(1), 4000);

        function resetTimer() {
            clearInterval(autoSlide);
            autoSlide = setInterval(() => moveSlide(1), 4000);
        }
    </script>
</body>
</html>