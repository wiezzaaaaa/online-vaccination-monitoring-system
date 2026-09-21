<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infant Enrollment - Alawihao Health Center</title>
    <style>
        :root {
            --primary-green: #718355; 
            --bg-color: #f9fbf2; 
            --accent-green: #a3b18a;
        }

        * { box-sizing: border-box; }
        body, html { margin: 0; padding: 0; background-color: var(--bg-color); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        .header { background: white; padding: 15px 50px; display: flex; align-items: center; gap: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .logo-placeholder { width: 50px; height: 50px; background: #ddd; border-radius: 50%; }
        .green-bar { height: 20px; width: 100%; background: var(--primary-green); }

        .form-wrapper { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        
        .enrollment-card {
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            border-top: 6px solid var(--primary-green);
        }

        .card-header {
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .card-header h2 { 
            color: var(--primary-green); 
            font-size: 1.25rem; 
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-row { display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; }
        .field { flex: 1; min-width: 200px; display: flex; flex-direction: column; }
        
        label { font-weight: bold; font-size: 13px; color: #555; margin-bottom: 8px; }
        input, select { 
            padding: 12px; 
            border: 1px solid #ccc; 
            border-radius: 6px; 
            font-size: 14px; 
            background-color: #fcfcfc;
        }

        .vaccine-badge-box {
            background: #f1f8e9;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--accent-green);
        }

        .checkbox-group { display: flex; gap: 30px; margin-top: 10px; }
        .check-item { display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 14px; color: var(--primary-green); }
        .check-item input { width: 18px; height: 18px; cursor: pointer; }

        .submit-btn {
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 20px;
            width: 100%;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 50px;
        }

        .submit-btn:hover { background: #5a6b44; transform: translateY(-2px); box-shadow: 0 5px 10px rgba(0,0,0,0.1); }

        .helper-text { font-size: 12px; color: #888; margin-top: 4px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo-placeholder"></div>
        <h1 style="font-size: 1.4rem; color: #333;">ALAWIHAO HEALTH CENTER</h1>
    </div>
    <div class="green-bar"></div>

    <div class="form-wrapper">
        <form action="process_infants.php" method="POST">
            <div class="enrollment-card">
                <div class="card-header"><h2>Step 1: Child Identity</h2></div>
                <div class="input-row">
                    <div class="field"><label>Last Name</label><input type="text" name="lname" required></div>
                    <div class="field"><label>First Name</label><input type="text" name="fname" required></div>
                    <div class="field"><label>Middle Name</label><input type="text" name="mname"></div>
                    <div class="field" style="max-width: 100px;"><label>Extension</label><input type="text" name="ext" placeholder="Jr."></div>
                </div>
                <div class="input-row">
                    <div class="field"><label>Date of Birth</label><input type="date" name="dob" required></div>
                    <div class="field"><label>Sex</label>
                        <select name="sex"><option value="male">Male</option><option value="female">Female</option></select>
                    </div>
                    <div class="field"><label>Blood Type</label>
                        <select name="blood_type">
                            <option value="unknown">Unknown</option>
                            <option value="A+">A+</option><option value="A-">A-</option>
                            <option value="B+">B+</option><option value="B-">B-</option>
                            <option value="O+">O+</option><option value="O-">O-</option>
                            <option value="AB+">AB+</option><option value="AB-">AB-</option>
                        </select>
                    </div>
                </div>
                <div class="input-row">
                    <div class="field"><label>Place of Birth</label><input type="text" name="birth_place" placeholder="Hospital or Health Center Name"></div>
                </div>
            </div>

            <div class="enrollment-card">
                <div class="card-header"><h2>Step 2: Birth Details & Initial Vaccines</h2></div>
                <div class="input-row">
                    <div class="field"><label>Birth Weight (kg)</label><input type="number" step="0.01" name="b_weight" placeholder="e.g. 3.2"></div>
                    <div class="field"><label>Birth Length (cm)</label><input type="number" step="0.1" name="b_length" placeholder="e.g. 50"></div>
                    <div class="field"><label>Head Circumference (cm)</label><input type="number" step="0.1" name="b_head"></div>
                </div>
                <div class="input-row">
                    <div class="field"><label>Type of Delivery</label>
                        <select name="delivery_type">
                            <option value="nsd">Normal Spontaneous (NSD)</option>
                            <option value="cs">Cesarean Section (CS)</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div class="field"><label>Birth Attendant</label><input type="text" name="attendant" placeholder="Doctor/Midwife"></div>
                </div>

                <div class="vaccine-badge-box">
                    <label>Initial Vaccines Received (Given at birth/hospital):</label>
                    <div class="checkbox-group">
                        <label class="check-item"><input type="checkbox" name="init_bcg"> BCG</label>
                        <label class="check-item"><input type="checkbox" name="init_hepab"> Hepatitis B</label>
                    </div>
                    <p class="helper-text">*Usually administered within 24 hours of birth.</p>
                </div>
            </div>

            <div class="enrollment-card">
                <div class="card-header"><h2>Step 3: Family & Contact Information</h2></div>
                <div class="input-row">
                    <div class="field"><label>Mother's Full Name (Maiden)</label><input type="text" name="mother_name"></div>
                    <div class="field"><label>Father's Full Name</label><input type="text" name="father_name"></div>
                </div>
                <div class="input-row">
                    <div class="field" style="flex: 2;"><label>Full Home Address</label><input type="text" name="address" placeholder="Purok, Barangay, Municipality"></div>
                    <div class="field"><label>Contact Number (Mobile)</label><input type="text" name="contact" placeholder="0912xxxxxxx">
                        <span class="helper-text">Used for vaccine SMS reminders.</span>
                    </div>
                </div>
            </div>

            <button type="submit" name="register_child" class="submit-btn">REGISTER MY CHILD</button>
        </form>
    </div>
</body>
</html>