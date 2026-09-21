<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$user_query = "SELECT first_name, last_name FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_query);
$user_data = $user_result->fetch_assoc();

$mommy_name = ($user_data) ? $user_data['first_name'] . " " . $user_data['last_name'] : "Mommy";

$infant_query = "SELECT * FROM infants LIMIT 1";
$infant_result = $conn->query($infant_query);
$baby = $infant_result->fetch_assoc();

$baby_name = ($baby) ? $baby['baby_name'] : "your little one";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOH.CVCHD Health Information Systems - BARANGAY ALAWIHAO Vaccines</title>
    <style>
        :root {
            --primary-green: #2d5016; 
            --accent-green: #5a7c3a;
            --light-green: #8fbf5a;
            --bg-color: #f8fffb; 
            --white: #ffffff;
            --text-dark: #333333;
            --text-muted: #666666;
            --card-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: var(--bg-color); 
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Header */
        .header {
            background: var(--white);
            padding: 20px 0;
            border-bottom: 3px solid var(--primary-green);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-green);
            text-decoration: none;
        }

        .logo span {
            color: var(--light-green);
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }

        nav a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: color 0.3s ease;
            font-size: 1rem;
        }

        nav a:hover {
            color: var(--primary-green);
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .page-title h1 {
            font-size: 2.5rem;
            color: var(--primary-green);
            margin-bottom: 10px;
        }

        .page-title p {
            font-size: 1.1rem;
            color: var(--text-muted);
            font-style: italic;
        }

        /* Grid Layout */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        /* Card Styling */
        .service-card {
            background: var(--white);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: var(--light-green);
        }

        .service-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            display: block;
        }

        .service-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--primary-green);
            margin-bottom: 15px;
        }

        .service-description {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Modal Overlay */
        .doc-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 4000;
            overflow-y: auto;
            padding: 20px;
            backdrop-filter: blur(5px);
        }

        .doc-content {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            position: relative;
        }

        .btn-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--light-green);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            color: white;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn-close:hover {
            background: var(--primary-green);
        }

        .info-block {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-block:last-child {
            border-bottom: none;
        }

        .info-block h4 {
            color: var(--primary-green);
            margin-bottom: 10px;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .lang-en {
            display: block;
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 5px;
            line-height: 1.6;
        }

        .lang-tl {
            display: block;
            color: var(--text-muted);
            font-style: italic;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .disclaimer {
            margin-top: 30px;
            padding: 20px;
            background: #fff9db;
            border-radius: 10px;
            border-left: 5px solid var(--light-green);
            color: #856404;
            font-size: 0.9rem;
        }

        /* Sidebar Navigation */
        .side-nav {
            height: 100%; width: 0; position: fixed; z-index: 3000;
            top: 0; left: 0; background-color: var(--primary-green);
            overflow-x: hidden; transition: 0.5s; padding-top: 60px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.2);
        }
        
        .side-nav a, .dropdown-btn { 
            padding: 15px 32px; text-decoration: none; font-size: 18px; 
            color: white; display: block; transition: 0.3s; 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
            background: none; border: none; width: 100%; 
            text-align: left; cursor: pointer; font-family: inherit;
        }
        
        .side-nav a:hover, .dropdown-btn:hover { background-color: var(--accent-green); }
        .side-nav .closebtn { position: absolute; top: 10px; right: 25px; font-size: 36px; border: none; }

        /* Dropdown Container */
        .dropdown-container {
            display: none;
            background-color: var(--accent-green);
            padding-left: 8px;
        }

        .dropdown-container a {
            font-size: 16px !important;
            padding: 12px 32px 12px 50px !important;
            border-bottom: none !important;
        }

        .caret { float: right; padding-top: 5px; font-size: 12px; }

        /* Hamburger Menu */
        .hamburger { font-size: 30px; cursor: pointer; color: var(--primary-green); }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 20px;
            }

            nav ul {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .services-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .page-title h1 {
                font-size: 2.5rem;
                color: var(--primary-green);
                margin-bottom: 10px;
            }

            .page-title p {
                font-size: 1.1rem;
                color: var(--text-muted);
                font-style: italic;
            }

            /* Grid Layout */
            .services-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                gap: 30px;
                margin-top: 40px;
            }

            /* Card Styling */
            .service-card {
                background: var(--white);
                border-radius: 15px;
                padding: 30px;
                text-align: center;
                box-shadow: var(--card-shadow);
                transition: all 0.3s ease;
                border: 2px solid transparent;
                cursor: pointer;
            }

            .service-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                border-color: var(--light-green);
            }

            .service-icon {
                font-size: 3rem;
                margin-bottom: 20px;
                display: block;
            }

            .service-title {
                font-size: 1.3rem;
                font-weight: bold;
                color: var(--primary-green);
                margin-bottom: 15px;
            }

            .service-description {
                color: var(--text-muted);
                font-size: 0.95rem;
                line-height: 1.5;
            }

            /* Modal Overlay */
            .doc-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                z-index: 4000;
                overflow-y: auto;
                padding: 20px;
                backdrop-filter: blur(5px);
            }

            .doc-content {
                max-width: 700px;
                margin: 50px auto;
                background: white;
                padding: 40px;
                border-radius: 20px;
                box-shadow: 0 25px 50px rgba(0,0,0,0.3);
                position: relative;
            }

            .btn-close {
                position: absolute;
                top: 20px;
                right: 20px;
                background: var(--light-green);
                border: none;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                cursor: pointer;
                font-size: 20px;
                color: white;
                font-weight: bold;
                transition: background 0.3s ease;
            }

            .btn-close:hover {
                background: var(--primary-green);
            }

            .info-block {
                margin-bottom: 25px;
                padding-bottom: 20px;
                border-bottom: 1px solid #f0f0f0;
            }

            .info-block:last-child {
                border-bottom: none;
            }

            .info-block h4 {
                color: var(--primary-green);
                margin-bottom: 10px;
                font-size: 1rem;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .lang-en {
                display: block;
                color: var(--text-dark);
                font-weight: 600;
                margin-bottom: 5px;
                line-height: 1.6;
            }

            .lang-tl {
                display: block;
                color: var(--text-muted);
                font-style: italic;
                font-size: 0.95rem;
                line-height: 1.5;
            }

            .disclaimer {
                margin-top: 30px;
                padding: 20px;
                background: #fff9db;
                border-radius: 10px;
                border-left: 5px solid var(--light-green);
                color: #856404;
                font-size: 0.9rem;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .header-content {
                    flex-direction: column;
                    gap: 20px;
                }

                nav ul {
                    flex-direction: column;
                    text-align: center;
                    gap: 15px;
                }

                .services-grid {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }

                .page-title h1 {
                    font-size: 2rem;
                }

                .page-title p {
                    font-size: 1.1rem;
                    color: var(--text-muted);
                    font-style: italic;
                }

                /* Grid Layout */
                .services-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                    gap: 30px;
                    margin-top: 40px;
                }

        <div class="services-grid">
            <div class="service-card" onclick="window.location.href='health_article.php?topic=warning-signs'">
                <span class="service-icon">⚠️</span>
                <h3 class="service-title">Mga Babala Habang Buntis</h3>
                <p class="service-description">Alamin ang mga palatandaan na dapat pansinin habang buntis at kailan dapat pumunta sa health center.</p>
            </div>

            <div class="service-card" onclick="window.location.href='health_article.php?topic=prenatal-checkup'">
                <span class="service-icon">🏥</span>
                <h3 class="service-title">Pre-natal Check-up</h3>
                <p class="service-description">Schedule ng check-up at ang mga mangyayari sa bawat bisita sa health center habang buntis.</p>
            </div>

            <div class="service-card" onclick="window.location.href='health_article.php?topic=pregnancy-dos'">
                <span class="service-icon">✅</span>
                <h3 class="service-title">Pregnancy Do's</h3>
                <p class="service-description">Mga malusog na gawi at dapat gawin para sa kalusugan ng ina at sanggol habang buntis.</p>
            </div>

            <div class="service-card" onclick="window.location.href='health_article.php?topic=pregnancy-donts'">
                <span class="service-icon">🚫</span>
                <h3 class="service-title">Pregnancy Don'ts</h3>
                <p class="service-description">Mga dapat iwasan habang buntis para sa kaligtasan ng ina at ng dinadalang sanggol.</p>
            </div>

            <div class="service-card" onclick="window.location.href='health_article.php?topic=pamahiin'">
                <span class="service-icon">💡</span>
                <h3 class="service-title">Mga Pamahiin: Sabi-sabi vs. Totoo</h3>
                <p class="service-description">Alamin kung alin sa mga kilalang pamahiin tungkol sa pagbubuntis ang totoo at alin ang hindi.</p>
            </div>

            <div class="service-card" onclick="window.location.href='health_article.php?topic=baby-growth'">
                <span class="service-icon">🤰</span>
                <h3 class="service-title">Paglaki ni Baby sa Sinapupunan</h3>
                <p class="service-description">Buwanang patnubay sa paglaki ni baby mula 0 hanggang 12 linggo sa loob ng sinapupunan.</p>
            </div>

            <div class="service-card" onclick="window.location.href='health_article.php?topic=newborn-care'">
                <span class="service-icon">👶</span>
                <h3 class="service-title">Pangangalaga sa Bagong Silang</h3>
                <p class="service-description">Mga payo sa tamang pag-aalaga sa bagong silang na sanggol at mga palatandaan ng karamdaman.</p>
            </div>

            <div class="service-card" onclick="window.location.href='health_article.php?topic=unang-linggo'">
                <span class="service-icon">🍼</span>
                <h3 class="service-title">Unang Linggo ng Pagsilang</h3>
                <p class="service-description">Mga pangangailangan ni baby sa unang linggo — init, pagpapasuso, at proteksyon sa impeksyon.</p>
            </div>

            <div class="service-card" onclick="window.location.href='health_article.php?topic=breastfeeding'">
                <span class="service-icon">🤱</span>
                <h3 class="service-title">Eksklusibong Pagpapasuso</h3>
                <p class="service-description">Mga benepisyo ng 6 na buwang eksklusibong pagpapasuso para sa ina at sanggol.</p>
            </div>

            <div class="service-card" onclick="window.location.href='health_article.php?topic=baby-milestones'">
                <span class="service-icon">🌱</span>
                <h3 class="service-title">Mga Kaganapan sa Paglaki ng Sanggol</h3>
                <p class="service-description">Alamin ang mga bagong kakayahan ni baby sa bawat buwan ng kanyang paglaki.</p>
            </div>

            <div class="service-card" onclick="window.location.href='health_article.php?topic=baby-safety'">
                <span class="service-icon">🛡️</span>
                <h3 class="service-title">Kaligtasan ni Baby</h3>
                <p class="service-description">Mga simpleng gabay para matiyak ang kaligtasan ni baby sa tahanan at sa labas.</p>
            </div>

            <div class="service-card" onclick="window.location.href='health_article.php?topic=philhealth'">
                <span class="service-icon">🏥</span>
                <h3 class="service-title">PhilHealth Benefits</h3>
                <p class="service-description">Mga benepisyo ng PhilHealth para sa ina at sanggol — maternity, newborn care, at iba pa.</p>
            </div>

            <div class="service-card" onclick="window.location.href='health_article.php?topic=family-planning'">
                <span class="service-icon">👨‍👩‍👧</span>
                <h3 class="service-title">Family Planning</h3>
                <p class="service-description">Iba't ibang pamamaraan ng family planning para sa tamang pag-aagwat ng panganganak.</p>
            </div>
        </div>
    </div>

    </body>
    </html>