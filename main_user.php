<?php
session_start();
include 'db_connect.php';
// if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$name = htmlspecialchars($_SESSION['name'] ?? 'Nanay');
?>
<!DOCTYPE html>
<html lang="fil">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Hub | Alawihao</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --green: #2d5016;
            --accent: #5a7c3a;
            --light: #8fbf5a;
            --bg: #f4f7f4;
            --white: #ffffff;
            --text: #1a2e0f;
            --muted: #6b7280;
            --blue: #1e5f8e;
            --blue-light: #e8f4fd;
            --orange: #b45309;
            --orange-light: #fef3c7;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); }

        /* TOPBAR */
        .topbar {
            background: var(--white);
            border-bottom: 3px solid var(--green);
            padding: 14px 20px 14px 65px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 200;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .topbar .logo-text { display: flex; flex-direction: column; }
        .topbar .logo-text span { font-size: 0.72rem; color: var(--muted); font-weight: 500; }
        .topbar .logo-text strong { font-size: 1rem; color: var(--green); font-weight: 700; }

        #main { transition: margin-left 0.3s; }
        .container { max-width: 960px; margin: 0 auto; padding: 28px 20px 0; }

        /* WELCOME BANNER */
        .welcome-banner {
            background: linear-gradient(135deg, var(--green) 0%, var(--accent) 100%);
            border-radius: 20px;
            padding: 28px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            box-shadow: 0 6px 20px rgba(45,80,22,0.25);
        }
        .welcome-banner .text h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 6px; }
        .welcome-banner .text p { font-size: 0.88rem; opacity: 0.88; line-height: 1.5; }
        .welcome-banner .emoji { font-size: 3.5rem; opacity: 0.9; }

        /* STATS BAR */
        .stats-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .stat-item {
            background: var(--white);
            border-radius: 12px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            flex: 1;
            min-width: 140px;
        }
        .stat-item .stat-icon { font-size: 1.4rem; }
        .stat-item .stat-text { display: flex; flex-direction: column; }
        .stat-item .stat-num { font-size: 1.1rem; font-weight: 700; color: var(--green); }
        .stat-item .stat-label { font-size: 0.72rem; color: var(--muted); }

        /* SEARCH BAR */
        .search-wrapper {
            position: relative;
            margin-bottom: 28px;
        }
        .search-wrapper input {
            width: 100%;
            padding: 14px 20px 14px 48px;
            border-radius: 50px;
            border: 2px solid #e5e7eb;
            font-size: 0.95rem;
            background: var(--white);
            color: var(--text);
            outline: none;
            transition: border-color 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .search-wrapper input:focus { border-color: var(--light); }
        .search-wrapper .search-icon { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 16px; }
        .no-results { text-align: center; color: var(--muted); padding: 20px; display: none; font-size: 0.9rem; }

        /* SECTION HEADER */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 28px 0 14px;
        }
        .section-header .left { display: flex; align-items: center; gap: 10px; }
        .section-header .icon-circle {
            width: 34px; height: 34px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; color: white;
        }
        .icon-circle.green { background: var(--green); }
        .icon-circle.blue { background: var(--blue); }
        .icon-circle.orange { background: var(--orange); }
        .section-header h2 { font-size: 1.1rem; font-weight: 700; }
        .section-header h2.green { color: var(--green); }
        .section-header h2.blue { color: var(--blue); }
        .section-header h2.orange { color: var(--orange); }
        .see-all {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--muted);
            text-decoration: none;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }
        .see-all:hover { background: var(--green); color: white; border-color: var(--green); }

        /* SCROLL WRAPPER */
        .scroll-wrapper {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            padding-bottom: 10px;
            scrollbar-width: thin;
            scrollbar-color: var(--light) transparent;
        }
        .scroll-wrapper::-webkit-scrollbar { height: 4px; }
        .scroll-wrapper::-webkit-scrollbar-thumb { background: var(--light); border-radius: 10px; }

        /* CARD */
        .info-card {
            min-width: 155px;
            max-width: 155px;
            background: var(--white);
            border-radius: 16px;
            overflow: visible;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            border: 2px solid transparent;
            text-decoration: none;
            display: block;
            position: relative;
            animation: fadeUp 0.4s ease both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .info-card:hover { transform: translateY(-5px); box-shadow: 0 10px 24px rgba(0,0,0,0.12); }
        .info-card.green:hover { border-color: var(--light); }
        .info-card.blue:hover { border-color: #7ab8e8; }
        .info-card.orange:hover { border-color: #f59e0b; }
        .info-card.hidden { display: none; }

        .card-icon {
            height: 105px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            border-radius: 14px 14px 0 0;
        }
        .card-icon.green { background: linear-gradient(135deg, #e8f5e9, #f1f8e9); }
        .card-icon.blue { background: linear-gradient(135deg, #e8f4fd, #f0f8ff); }
        .card-icon.orange { background: linear-gradient(135deg, #fef3c7, #fffbeb); }

        .card-label {
            color: white;
            padding: 9px 8px;
            text-align: center;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.4px;
            line-height: 1.3;
            border-radius: 0 0 14px 14px;
        }
        .card-label.green { background: var(--green); }
        .card-label.blue { background: var(--blue); }
        .card-label.orange { background: var(--orange); }

        /* TOOLTIP */
        .tooltip-text {
            visibility: hidden;
            opacity: 0;
            background: rgba(0,0,0,0.82);
            color: white;
            font-size: 0.72rem;
            text-align: center;
            border-radius: 8px;
            padding: 7px 10px;
            position: absolute;
            bottom: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            width: 160px;
            z-index: 999;
            transition: opacity 0.2s;
            pointer-events: none;
            line-height: 1.4;
        }
        .tooltip-text::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: rgba(0,0,0,0.82);
        }
        .info-card:hover .tooltip-text { visibility: visible; opacity: 1; }

        /* FOOTER */
        .site-footer {
            background: var(--green);
            color: white;
            padding: 28px 20px;
            margin-top: 40px;
            text-align: center;
        }
        .site-footer h3 { font-size: 1rem; font-weight: 700; margin-bottom: 8px; }
        .site-footer p { font-size: 0.83rem; opacity: 0.85; line-height: 1.9; }
        .site-footer hr { border: none; border-top: 1px solid rgba(255,255,255,0.2); margin: 12px auto; width: 50%; }
    </style>
</head>
<body>
<?php include 'user_sidebar.php'; ?>

<div class="topbar">
    <img src="image/brgy.jpg" alt="Brgy Logo" style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid var(--green);">
    <div class="logo-text">
        <span>Barangay Alawihao Health Center</span>
        <strong>ALAWIHAO HEALTH HUB</strong>
    </div>
</div>

<div id="main">
<div class="container">

    <!-- WELCOME BANNER -->
    <div class="welcome-banner">
        <div class="text">
            <h1>Kamusta, <?= $name ?>!</h1>
            <p>Maligayang pagdating sa Alawihao Health Center Portal.<br>Piliin ang topic na gusto mong basahin.</p>
        </div>
    </div>

    <!-- SEARCH BAR -->
    <div class="search-wrapper">
        <i class="fa fa-search search-icon"></i>
        <input type="text" id="searchInput" placeholder="Maghanap ng health topic..." oninput="filterCards()">
    </div>
    <p class="no-results" id="noResults">Walang nahanap na topic. Subukan ang ibang keyword.</p>

    <!-- MATERNAL HEALTH -->
    <div class="section-header">
        <div class="left">
            <h2 class="green">Maternal Health</h2>
        </div>
    </div>
    <div class="scroll-wrapper" id="maternal">
        <a class="info-card green" href="health_article.php?topic=warning-signs">
            <span class="tooltip-text">Mga palatandaan na dapat pansinin habang buntis</span>
            <div class="card-icon green"><img src="image/pic9.jpg" alt="Mga Babala" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label green">MGA BABALA HABANG BUNTIS</div>
        </a>
        <a class="info-card green" href="health_article.php?topic=prenatal-checkup">
            <span class="tooltip-text">Schedule at proseso ng pre-natal check-up</span>
            <div class="card-icon green"><img src="image/pic10.jpg" alt="Pre-natal" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label green">PRE-NATAL CHECK-UP</div>
        </a>
        <a class="info-card green" href="health_article.php?topic=pregnancy-dos">
            <span class="tooltip-text">Mga malusog na gawi para sa buntis</span>
            <div class="card-icon green"><img src="image/pic11.jpg" alt="Pregnancy Dos" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label green">PREGNANCY DO'S</div>
        </a>
        <a class="info-card green" href="health_article.php?topic=pregnancy-donts">
            <span class="tooltip-text">Mga dapat iwasan habang buntis</span>
            <div class="card-icon green"><img src="image/pic8.jpg" alt="Pregnancy Donts" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label green">PREGNANCY DON'TS</div>
        </a>
        <a class="info-card green" href="health_article.php?topic=pamahiin">
            <span class="tooltip-text">Totoo o hindi ang mga kilalang pamahiin?</span>
            <div class="card-icon green"><img src="image/pic12.webp" alt="Pamahiin" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label green">SABI-SABI VS. TOTOO</div>
        </a>
        <a class="info-card green" href="health_article.php?topic=baby-growth">
            <span class="tooltip-text">Buwanang paglaki ni baby sa sinapupunan</span>
            <div class="card-icon green"><img src="image/pic13.jpg" alt="Baby Growth" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label green">PAGLAKI NI BABY SA SINAPUPUNAN</div>
        </a>
    </div>

    <!-- NEWBORN CARE -->
    <div class="section-header">
        <div class="left">
            <h2 class="blue">Newborn Care</h2>
        </div>
    </div>
    <div class="scroll-wrapper" id="newborn">
        <a class="info-card blue" href="health_article.php?topic=newborn-care">
            <span class="tooltip-text">Tamang pag-aalaga sa bagong silang na sanggol</span>
            <div class="card-icon blue"><img src="image/pic6.jpg" alt="Newborn Care" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label blue">PANGANGALAGA SA BAGONG SILANG</div>
        </a>
        <a class="info-card blue" href="health_article.php?topic=unang-linggo">
            <span class="tooltip-text">Mga pangangailangan ni baby sa unang linggo</span>
            <div class="card-icon blue"><img src="image/pic3.jpg" alt="Unang Linggo" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label blue">UNANG LINGGO NG PAGSILANG</div>
        </a>
        <a class="info-card blue" href="health_article.php?topic=breastfeeding">
            <span class="tooltip-text">Benepisyo ng 6 na buwang eksklusibong pagpapasuso</span>
            <div class="card-icon blue"><img src="image/pic1.jpg" alt="Breastfeeding" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label blue">EKSKLUSIBONG PAGPAPASUSO</div>
        </a>
        <a class="info-card blue" href="health_article.php?topic=baby-milestones">
            <span class="tooltip-text">Mga bagong kakayahan ni baby sa bawat buwan</span>
            <div class="card-icon blue"><img src="image/pic7.webp" alt="Milestones" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label blue">PAGLAKI NG SANGGOL</div>
        </a>
        <a class="info-card blue" href="health_article.php?topic=baby-safety">
            <span class="tooltip-text">Mga gabay para sa kaligtasan ni baby sa tahanan</span>
            <div class="card-icon blue"><img src="image/pic2.jpg" alt="Baby Safety" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label blue">KALIGTASAN NI BABY</div>
        </a>
    </div>

    <!-- HEALTH PROGRAMS -->
    <div class="section-header">
        <div class="left">
            <h2 class="orange">Health Programs</h2>
        </div>
    </div>
    <div class="scroll-wrapper" id="programs">
        <a class="info-card orange" href="health_article.php?topic=philhealth">
            <span class="tooltip-text">Mga benepisyo ng PhilHealth para sa ina at sanggol</span>
            <div class="card-icon orange"><img src="image/pic13.webp" alt="PhilHealth" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label orange">PHILHEALTH BENEFITS</div>
        </a>
        <a class="info-card orange" href="health_article.php?topic=family-planning">
            <span class="tooltip-text">Iba't ibang pamamaraan ng family planning</span>
            <div class="card-icon orange"><img src="image/pic14.webp" alt="Family Planning" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;"></div>
            <div class="card-label orange">FAMILY PLANNING</div>
        </a>
    </div>

</div>

</div>

<script>
    function filterCards() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.info-card');
        let visibleCount = 0;
        cards.forEach(card => {
            const label = card.querySelector('.card-label').textContent.toLowerCase();
            if (label.includes(query)) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });
        document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
    }

    function scrollToSection(id) {
        document.getElementById(id).scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
</script>
<?php include 'footer.php'; ?>
</body>
</html>
