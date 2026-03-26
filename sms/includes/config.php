<?php
// ============================================================
//  config.php  –  Database connection + mock data
//  To use a REAL MySQL database:
//    1. Set DB_HOST, DB_NAME, DB_USER, DB_PASS below
//    2. Set USE_MOCK_DATA = false
//    3. Import schema.sql into your MySQL server
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'sms_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('USE_MOCK_DATA', true);   // ← flip to false when you have MySQL

// ── Session start ────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── Real DB connection (only when USE_MOCK_DATA = false) ─────
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die(json_encode(['error' => 'DB connection failed: ' . $conn->connect_error]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

// ── Mock data store (lives in $_SESSION so changes persist) ──
function &mock_store() {
    if (!isset($_SESSION['mock'])) {
        $_SESSION['mock'] = [
            'students' => [
                ['id'=>'STU001','name'=>'Priya Sharma',  'dept'=>'Computer Science','year'=>'3rd Year','email'=>'priya@sms.edu',  'pass'=>password_hash('pass123',PASSWORD_DEFAULT)],
                ['id'=>'STU002','name'=>'Arjun Patel',   'dept'=>'Electronics',     'year'=>'2nd Year','email'=>'arjun@sms.edu',  'pass'=>password_hash('pass123',PASSWORD_DEFAULT)],
                ['id'=>'STU003','name'=>'Sneha Reddy',   'dept'=>'Mechanical',      'year'=>'1st Year','email'=>'sneha@sms.edu',  'pass'=>password_hash('pass123',PASSWORD_DEFAULT)],
                ['id'=>'STU004','name'=>'Rahul Singh',   'dept'=>'Civil',           'year'=>'4th Year','email'=>'rahul@sms.edu',  'pass'=>password_hash('pass123',PASSWORD_DEFAULT)],
                ['id'=>'STU005','name'=>'Ananya Joshi',  'dept'=>'Computer Science','year'=>'2nd Year','email'=>'ananya@sms.edu', 'pass'=>password_hash('pass123',PASSWORD_DEFAULT)],
            ],
            'notices' => [
                ['id'=>1,'title'=>'Mid-Semester Examination Schedule','body'=>'Mid-semester exams will be held from April 10–18, 2025. All students must carry their ID cards. No electronic devices allowed in exam halls.','tag'=>'exam',   'date'=>'2025-03-22','by'=>'Admin'],
                ['id'=>2,'title'=>'Holi Holiday – College Closed',    'body'=>'The college will remain closed on March 25, 2025 on account of Holi. Regular classes resume on March 26.',                                         'tag'=>'holiday','date'=>'2025-03-20','by'=>'Admin'],
                ['id'=>3,'title'=>'Annual Sports Day Registration',   'body'=>'Students interested in participating must register at the Sports Office by March 30. All streams are welcome.',                                      'tag'=>'general','date'=>'2025-03-18','by'=>'Admin'],
                ['id'=>4,'title'=>'Fee Payment Deadline – Final Warning','body'=>'Students who have not paid their semester fees must do so by March 28. A late fine of ₹500 will be charged after this date.',                    'tag'=>'urgent', 'date'=>'2025-03-15','by'=>'Admin'],
            ],
            'schedules' => [
                ['id'=>1,'time'=>'8:00–9:00',   'subject'=>'Data Structures',   'teacher'=>'Prof. Meena Iyer',  'room'=>'A-201','absent'=>false],
                ['id'=>2,'time'=>'9:00–10:00',  'subject'=>'Mathematics III',   'teacher'=>'Prof. Suresh Naik', 'room'=>'A-101','absent'=>false],
                ['id'=>3,'time'=>'10:15–11:15', 'subject'=>'Computer Networks', 'teacher'=>'Prof. Anil Kumar',  'room'=>'Lab-3','absent'=>true ],
                ['id'=>4,'time'=>'11:15–12:15', 'subject'=>'Database Systems',  'teacher'=>'Prof. Kavita Shah', 'room'=>'A-304','absent'=>false],
                ['id'=>5,'time'=>'1:15–2:15',   'subject'=>'Operating Systems', 'teacher'=>'Prof. Deepak Jain', 'room'=>'A-205','absent'=>false],
                ['id'=>6,'time'=>'2:15–3:15',   'subject'=>'Software Engg.',    'teacher'=>'Prof. Ritu Sharma', 'room'=>'A-102','absent'=>false],
            ],
            'admin' => ['id'=>'ADMIN001','name'=>'Dr. Rajesh Kumar','pass'=>password_hash('admin123',PASSWORD_DEFAULT)],
            'next_notice_id' => 5,
            'next_schedule_id' => 7,
        ];
    }
    return $_SESSION['mock'];
}

// ── Convenience getters ───────────────────────────────────────
function get_notices()   { $m=&mock_store(); return $m['notices'];   }
function get_schedules() { $m=&mock_store(); return $m['schedules']; }
function get_students()  { $m=&mock_store(); return $m['students'];  }
