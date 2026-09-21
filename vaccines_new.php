<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$user_query = "SELECT first_name, last_name FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_query);

if ($user_result && $user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
    $mommy_name = $user_data['first_name'] . " " . $user_data['last_name'];
} else {
    $mommy_name = "Mommy";
}

$infant_query = "SELECT * FROM infants WHERE user_id = '$user_id' LIMIT 1";
$infant_result = $conn->query($infant_query);

if ($infant_result && $infant_result->num_rows > 0) {
    $baby = $infant_result->fetch_assoc();
    $baby_name = $baby['fname'] . " " . $baby['lname'];
} else {
    $baby = null;
    $baby_name = "your little one";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOH.CVCHD Health Information Systems - BARANGAY ALAWIHAO Vaccines</title>
    <style>
        :root {
            --primary-green: #718355; 
            --accent-green: #a3c981;
            --light-green: #d4e4bc;
            --bg-color: #f9fbf2; 
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

        /* Hamburger Menu */
        .hamburger { font-size: 30px; cursor: pointer; color: var(--primary-green); }

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
        }
    </style>
</head>
<body>

<?php include 'user_sidebar.php'; ?>

<header class="header">
    <div class="header-content">
        <a href="#" class="logo">BARANGAY ALAWIHAO</a>
        <div class="hamburger" onclick="openNav()">☰</div>
    </div>
</header>

<div id="main" class="container">
    <div class="page-title">
        <h1>Welcome, <?php echo htmlspecialchars($mommy_name); ?>!</h1>
        <p>Healthy babies start with informed mommies. (Ang malusog na sanggol ay nagsisimula sa maalam na nanay.)</p>
    </div>

    <div class="services-grid">
        <div class="service-card" onclick="openDoc('Tetanus Toxoid', 'Maternal Protection', 'This vaccine prevents Tetanus infection during pregnancy and protects both mother and baby from this serious bacterial disease.', '(Ang bakunang ito ay nagbibigay proteksyon laban sa Tetanus infection habang buntis at nagpoprotekta sa ina at sanggol mula sa malubhang bacterial disease.)', 'It builds immunity that passes to the baby, providing protection during the first few months of life.', '(Nagbibigay ito ng immunity na napupasa sa sanggol, na nagbibigay proteksyon sa unang ilang buwan ng buhay.)', '2 doses during pregnancy - first as early as possible, second at least 4 weeks after first dose.', '(Dalawang beses habang buntis - una nang mas maaga, pangalawa kahit 4 linggo pagkatapos ng unang dose.)', 'Mild soreness at injection site, slight fever, and temporary redness.', '(Bahagyang pananakit sa lugar ng turok, kaunting lagnat, at pansamantalang pamumula.)')">
            <span class="service-icon">🤱</span>
            <h3 class="service-title">Tetanus Toxoid</h3>
            <p class="service-description">Maternal protection vaccine preventing tetanus infection during pregnancy and protecting both mother and baby.</p>
        </div>

        <div class="service-card" onclick="openDoc('Pregnancy Do\'s', 'Healthy Habits', 'Take prenatal vitamins daily, especially folic acid to prevent birth defects.', '(Uminom ng prenatal vitamins araw-araw, lalo na ang folic acid para maiwasan ang birth defects.)', 'Proper nutrition ensures healthy fetal development and prevents complications.', '(Ang tamang nutrisyon ay tinitiyak ang malusog na fetal development at nag-iwas sa mga komplikasyon.)', 'Every day throughout pregnancy and continue while breastfeeding if recommended.', '(Araw-araw sa buong pagbubuntis at ituloy habang nagpapasuso kung inirerekomenda.)', 'Better energy levels, reduced nausea, and improved overall health for both mother and baby.', '(Mas masigla na energy, nabawasang nausea, at improved na overall health para sa ina at sanggol.)')">
            <span class="service-icon">🥗</span>
            <h3 class="service-title">Pregnancy Do's</h3>
            <p class="service-description">Essential healthy habits including prenatal vitamins, proper nutrition, and wellness practices for expectant mothers.</p>
        </div>

        <div class="service-card" onclick="openDoc('Pregnancy Don\'ts', 'Risk Prevention', 'Avoid alcohol, smoking, and certain medications that can harm fetal development.', '(Iwasan ang alak, paninigarilyo, at ilang gamot na maaaring makasira sa fetal development.)', 'These risks can cause birth defects, developmental delays, and pregnancy complications.', '(Ang mga ito ay maaaring magdulot ng birth defects, developmental delays, at pregnancy complications.)', 'Strictly follow throughout entire pregnancy for optimal fetal health.', '(Sundin ito nang mahigpit sa buong pagbubuntis para sa optimal na fetal health.)', 'Safe delivery and reduced risk of complications for both mother and baby.', '(Ligtas na panganganak at nabawasang risk ng complications para sa ina at sanggol.)')">
            <span class="service-icon">🚫</span>
            <h3 class="service-title">Pregnancy Don'ts</h3>
            <p class="service-description">Risk prevention guidelines including substances and activities to avoid during pregnancy for optimal fetal health.</p>
        </div>

        <div class="service-card" onclick="openDoc('BCG Vaccine', 'Anti-Tuberculosis', 'Protects from severe forms of tuberculosis like TB meningitis and miliary TB.', '(Proteksyon laban sa mga severe na uri ng tularemia tulad ng TB meningitis at miliary TB.)', 'Infants are vulnerable to TB complications that can be life-threatening if not prevented.', '(Ang mga sanggol ay madaling kapitan ng TB complications na maaaring maging life-threatening kung hindi maiiwasan.)', 'Given immediately after birth, preferably within the first 24 hours of life.', '(Ibinibigay agad pagkapanganak, mas mainam sa loob ng unang 24 oras ng buhay.)', 'Small scar at injection site after 2-3 months, mild swelling or soreness.', '(Maliit na peklat sa lugar ng turok pagkatapos ng 2-3 buwan, kaunting pamamaga o pananakit.)')">
            <span class="service-icon">💉</span>
            <h3 class="service-title">BCG Vaccine <span><?php echo ($baby && $baby['init_bcg'] == 1) ? "✅" : "⏳"; ?></span></h3>
            <p class="service-description">Anti-tuberculosis vaccine protecting infants from severe TB forms like meningitis and miliary tuberculosis.</p>
        </div>

        <div class="service-card" onclick="openDoc('Hepatitis B', 'Liver Safety', 'Prevents Hepatitis B virus infection which can cause chronic liver disease and cancer.', '(Pinipigilan ang Hepatitis B virus infection na maaaring magdulot ng chronic liver disease at cancer.)', 'Stops virus from passing from mother to baby during birth and prevents future infections.', '(Iniiwasan ang pagpasa ng virus mula sa ina hanggang sa sanggol habang panganganak at nag-iwas sa mga future infections.)', 'First dose within 24 hours of birth, followed by additional doses according to schedule.', '(Unang dose sa loob ng 24 oras pagkapanganak, kasunod ng karagdagang doses ayon sa schedule.)', 'Minimal fever, slight soreness at injection site, usually resolves within 1-2 days.', '(Kaunting lagnat, bahagyang pananakit sa lugar ng turok, karaniwang nawawala sa loob ng 1-2 araw.)')">
            <span class="service-icon">🛡️</span>
            <h3 class="service-title">Hepatitis B <span><?php echo ($baby && $baby['init_hepab'] == 1) ? "✅" : "⏳"; ?></span></h3>
            <p class="service-description">Liver safety vaccine preventing Hepatitis B virus infection and reducing risk of chronic liver disease and cancer.</p>
        </div>

        <div class="service-card" onclick="openDoc('Pentavalent', '5-in-1 Protection', 'Protects against 5 diseases: Diphtheria, Pertussis, Tetanus, Hepatitis B, and Hib.', '(Proteksyon laban sa 5 sakit: Diphtheria, Pertussis, Tetanus, Hepatitis B, at Hib.)', 'One single injection provides protection against multiple serious childhood diseases.', '(Isang turok lang ang nagbibigay proteksyon laban sa maraming seryosong sakit sa pambata.)', 'Given at 6, 10, and 14 weeks of age as part of routine immunization schedule.', '(Ibinibigay sa ika-6, 10, at 14 linggo ng edad bilang bahagi ng routine immunization schedule.)', 'Fever, redness or swelling at injection site, irritability - usually mild and temporary.', '(Lagnat, pamumula o pamamaga sa lugar ng turok, irritability - karaniwang mild at pansamantala lang.)')">
            <span class="service-icon">🍼</span>
            <h3 class="service-title">5-in-1 Pentavalent</h3>
            <p class="service-description">Comprehensive protection against Diphtheria, Pertussis, Tetanus, Hepatitis B, and Hib in a single injection.</p>
        </div>

        <div class="service-card" onclick="openDoc('Oral Polio', 'Paralysis Prevention', 'Prevents Polio - a highly infectious viral disease that can cause irreversible paralysis.', '(Iniiwasan ang Polio - isang highly infectious viral disease na maaaring magdulot ng irreversible paralysis.)', 'The oral vaccine stops the virus in the digestive system, preventing it from attacking nerves.', '(Ang oral vaccine ay pumapatay sa virus sa digestive system, na nag-iwas na itakbo sa nerves.)', '3 doses given at 6, 10, and 14 weeks, plus booster doses as per national schedule.', '(Tatlong doses sa ika-6, 10, at 14 linggo, kasama ang booster doses ayon sa national schedule.)', 'No major side effects, very safe and effective in preventing polio infection.', '(Walang major side effects, napakaligtas at epektibo sa pag-iwas sa polio infection.)')">
            <span class="service-icon">💧</span>
            <h3 class="service-title">Oral Polio Vaccine</h3>
            <p class="service-description">Paralysis prevention vaccine protecting against polio virus that can cause irreversible paralysis and disability.</p>
        </div>

        <div class="service-card" onclick="openDoc('Vaccine Schedule', 'Immunization Timeline', 'Complete vaccination schedule for infants and children with recommended dates and intervals.', '(Kumpletong vaccination schedule para sa mga sanggol at bata kasama ang mga inirerekomendang petsa at interval.)', 'Following the schedule ensures timely protection and prevents disease outbreaks.', '(Ang pagsunod sa schedule ay tinitiyak ang timely na proteksyon at nag-iwas sa disease outbreaks.)', 'Regular check-ups at health center for vaccination according to national immunization program.', '(Mga regular na check-up sa health center para sa vaccination ayon sa national immunization program.)', 'Peace of mind knowing your child is protected against preventable diseases.', '(Peace of mind na alam mong protektado ang iyong anak laban sa mga preventable diseases.)')">
            <span class="service-icon">📅</span>
            <h3 class="service-title">Vaccine Schedule</h3>
            <p class="service-description">Complete immunization timeline for infants and children with recommended vaccination dates and follow-up schedules.</p>
        </div>

        <div class="service-card" onclick="openDoc('Vaccination Records', 'Digital Tracking', 'Digital system for maintaining complete vaccination history and monitoring immunization status.', '(Digital system para sa pagpapanatili ng kumpletong vaccination history at pagmomonitor ng immunization status.)', 'Easy access to vaccination records helps healthcare providers give appropriate care.', '(Madaling access sa vaccination records ay tumutulong sa healthcare providers na magbigay ng angkop na pag-aalaga.)', 'Update records after each vaccination visit and keep for future reference.', '(I-update ang records pagkatapos ng bawat vaccination visit at ikeep para sa future reference.)', 'Convenient tracking, reminders for next doses, and complete health documentation.', '(Convenient na tracking, mga paalala para sa susunod na doses, at kumpletong health documentation.)')">
            <span class="service-icon">📋</span>
            <h3 class="service-title">Vaccination Records</h3>
            <p class="service-description">Digital tracking system for maintaining complete vaccination history and monitoring immunization status.</p>
        </div>
    </div>
</div>

<div id="docViewer" class="doc-overlay">
    <div class="doc-content">
        <button class="btn-close" onclick="closeDoc()">×</button>
        <h1 id="vTitle" style="color: var(--primary-green); margin: 0;"></h1>
        <span id="vSub" style="color: var(--accent-green); font-weight: bold;"></span>
        <hr style="border: 0; border-top: 2px solid #f8f9fa; margin: 25px 0;">
        <div class="info-block"><h4>What is this? / Ano ito?</h4><span id="vWhatEN" class="lang-en"></span><span id="vWhatTL" class="lang-tl"></span></div>
        <div class="info-block"><h4>Importance / Bakit Mahalaga?</h4><span id="vWhyEN" class="lang-en"></span><span id="vWhyTL" class="lang-tl"></span></div>
        <div class="info-block"><h4>Schedule / Kailan Ibibigay?</h4><span id="vWhenEN" class="lang-en"></span><span id="vWhenTL" class="lang-tl"></span></div>
        <div class="info-block"><h4>Side Effects / Dapat Asahan</h4><span id="vSideEN" class="lang-en"></span><span id="vSideTL" class="lang-tl"></span></div>
        <div class="disclaimer"><strong>Note to Mommy:</strong> Consult Alawihao Health Center workers for questions.</div>
    </div>
</div>

<script>
    function openDoc(title, sub, whatEN, whatTL, whyEN, whyTL, whenEN, whenTL, sideEN, sideTL) {
        document.getElementById('vTitle').innerText = title;
        document.getElementById('vSub').innerText = sub;
        document.getElementById('vWhatEN').innerText = whatEN;
        document.getElementById('vWhatTL').innerText = whatTL;
        document.getElementById('vWhyEN').innerText = whyEN;
        document.getElementById('vWhyTL').innerText = whyTL;
        document.getElementById('vWhenEN').innerText = whenEN;
        document.getElementById('vWhenTL').innerText = whenTL;
        document.getElementById('vSideEN').innerText = sideEN;
        document.getElementById('vSideTL').innerText = sideTL;
        document.getElementById('docViewer').style.display = "block";
        document.body.style.overflow = "hidden";
    }

    function closeDoc() {
        document.getElementById('docViewer').style.display = "none";
        document.body.style.overflow = "auto";
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('docViewer')) { closeDoc(); }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') { closeDoc(); }
    });
</script>

</body>
</html>
