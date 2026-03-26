<?php
require_once __DIR__ . '/config.php';

// ── Login ─────────────────────────────────────────────────────
function attempt_login(string $id, string $pass): array {
    $id   = trim($id);
    $m    = &mock_store();

    // Admin check
    if ($id === $m['admin']['id'] && password_verify($pass, $m['admin']['pass'])) {
        return ['ok'=>true,'role'=>'admin','name'=>$m['admin']['name'],'id'=>$id];
    }

    // Student check
    foreach ($m['students'] as $s) {
        if ($s['id'] === $id && password_verify($pass, $s['pass'])) {
            return ['ok'=>true,'role'=>'student','name'=>$s['name'],'id'=>$id,
                    'dept'=>$s['dept'],'year'=>$s['year'],'email'=>$s['email']];
        }
    }

    return ['ok'=>false,'error'=>'Invalid ID or password.'];
}

// ── Session helpers ───────────────────────────────────────────
function login_user(array $data): void {
    $_SESSION['user'] = $data;
}

function logout_user(): void {
    session_destroy();
    header('Location: ../index.php');
    exit;
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function require_admin(): void {
    $u = current_user();
    if (!$u || $u['role'] !== 'admin') {
        header('Location: ../index.php');
        exit;
    }
}

function require_student(): void {
    $u = current_user();
    if (!$u || $u['role'] !== 'student') {
        header('Location: ../index.php');
        exit;
    }
}

function require_login(): void {
    if (!current_user()) {
        header('Location: ../index.php');
        exit;
    }
}

// ── Initials helper ───────────────────────────────────────────
function initials(string $name): string {
    $parts = explode(' ', trim($name));
    return strtoupper(substr($parts[0],0,1) . (isset($parts[1]) ? substr($parts[1],0,1) : ''));
}

// ── Date formatter ────────────────────────────────────────────
function fmt_date(string $date): string {
    return date('d M Y', strtotime($date));
}
