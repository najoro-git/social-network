<?php
// Redirect to feed if already logged in
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialNet — The Cyberpunk Network</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* ================================
           RESET & VARIABLES
        ================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        :root {
            --green:         #1a6fff;
            --green-dark:    #1255cc;
            --green-glow:    rgba(26, 111, 255, 0.3);
            --blue:          #00cfff;
            --blue-dark:     #00a8cc;
            --blue-glow:     rgba(0, 207, 255, 0.3);


            /* 
            --green:       #00ff88;
            --green-dark:  #00cc6a;
            --green-glow:  rgba(0,255,136,0.3); 
            --blue:        #1a6fff;
            --blue-dark:   #1255cc;
            --blue-glow:   rgba(26,111,255,0.3);*/
            --gold:        #ffd700;
            --gold-dark:   #ccaa00;
            --silver:      #c0c0c0;
            --bg:          #070b14;
            --bg2:         #0a0e1a;
            --card:        rgba(13,21,38,0.85);
            --border:      rgba(0,255,136,0.2);
            --text:        #e8f4f8;
            --muted:       #556677;
            --font-title:  'Orbitron', monospace;
            --font-body:   'Rajdhani', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }

        /* ================================
           SCANLINES
        ================================ */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(
                0deg,
                rgba(0,0,0,0.04) 0px,
                rgba(0,0,0,0.04) 1px,
                transparent 1px,
                transparent 3px
            );
            pointer-events: none;
            z-index: 9999;
        }

        /* ================================
           NAVBAR
        ================================ */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 4%;
            background: rgba(7,11,20,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 2px 30px rgba(0,255,136,0.1);
        }

        .nav-brand {
            font-family: var(--font-title);
            font-size: 1.3rem;
            font-weight: 900;
            background: linear-gradient(90deg, var(--green), var(--blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 3px;
        }

        .nav-brand .material-icons {
            background: linear-gradient(90deg, var(--green), var(--blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.6rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .nav-links a {
            font-family: var(--font-body);
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 1px;
            padding: 0.45rem 1.1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            color: var(--silver);
            border: 1px solid transparent;
        }

        .nav-links a:hover {
            color: var(--green);
            border-color: var(--border);
            background: rgba(0,255,136,0.05);
        }

        .nav-links .btn-cta {
            background: linear-gradient(135deg, var(--green-dark), var(--green));
            color: var(--bg) !important;
            font-weight: 700;
            border: none !important;
            box-shadow: 0 0 15px var(--green-glow);
        }

        .nav-links .btn-cta:hover {
            box-shadow: 0 0 25px var(--green-glow);
            transform: translateY(-1px);
            color: var(--bg) !important;
        }

        /* ================================
           HERO
        ================================ */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 6rem 4% 4rem;
            background:
                linear-gradient(135deg, rgba(7,11,20,0.97) 0%, rgba(10,14,26,0.93) 100%),
                url('/assets/images/bg-main.jpg') center/cover fixed;
        }

        /* Grid overlay */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,255,136,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,255,136,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0%   { transform: translateY(0); }
            100% { transform: translateY(60px); }
        }

        /* Glowing orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: orbFloat 8s ease-in-out infinite;
        }

        .orb-1 {
            width: 500px; height: 500px;
            background: var(--green);
            top: -100px; left: -100px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 400px; height: 400px;
            background: var(--blue);
            bottom: -80px; right: -80px;
            animation-delay: -4s;
        }

        .orb-3 {
            width: 300px; height: 300px;
            background: var(--gold);
            top: 50%; right: 20%;
            opacity: 0.08;
            animation-delay: -2s;
        }

        @keyframes orbFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-30px) scale(1.05); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1.2rem;
            border: 1px solid var(--border);
            border-radius: 50px;
            background: rgba(0,255,136,0.07);
            font-family: var(--font-title);
            font-size: 0.7rem;
            color: var(--green);
            letter-spacing: 3px;
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease both;
        }

        .hero-badge .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--green);
            animation: pulse 2s ease infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.8); }
        }

        .hero-title {
            font-family: var(--font-title);
            font-size: clamp(2.5rem, 8vw, 5.5rem);
            font-weight: 900;
            line-height: 1.05;
            letter-spacing: 4px;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .hero-title .line-green {
            background: linear-gradient(90deg, var(--green), #00ccff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
        }

        .hero-title .line-gold {
            background: linear-gradient(90deg, var(--gold), var(--silver));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
            font-size: 0.5em;
            letter-spacing: 8px;
            margin-bottom: 0.3rem;
        }

        .hero-sub {
            font-size: 1.2rem;
            color: var(--silver);
            max-width: 520px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease 0.6s both;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.9rem 2.2rem;
            background: linear-gradient(135deg, var(--green-dark), var(--green));
            color: var(--bg);
            font-family: var(--font-title);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 2px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            box-shadow: 0 0 25px var(--green-glow);
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 40px var(--green-glow), 0 10px 30px rgba(0,0,0,0.3);
            color: var(--bg);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.9rem 2.2rem;
            background: transparent;
            color: var(--silver);
            font-family: var(--font-title);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 2px;
            border-radius: 8px;
            border: 1px solid rgba(192,192,192,0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .btn-hero-secondary:hover {
            color: var(--gold);
            border-color: rgba(255,215,0,0.4);
            background: rgba(255,215,0,0.06);
            box-shadow: 0 0 20px rgba(255,215,0,0.15);
            transform: translateY(-2px);
        }

        /* Scroll indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.4rem;
            color: var(--muted);
            font-family: var(--font-title);
            font-size: 0.65rem;
            letter-spacing: 2px;
            animation: fadeIn 1s ease 1.5s both;
        }

        .scroll-line {
            width: 1px;
            height: 40px;
            background: linear-gradient(var(--green), transparent);
            animation: scrollLine 2s ease infinite;
        }

        @keyframes scrollLine {
            0%   { transform: scaleY(0); transform-origin: top; }
            50%  { transform: scaleY(1); transform-origin: top; }
            51%  { transform: scaleY(1); transform-origin: bottom; }
            100% { transform: scaleY(0); transform-origin: bottom; }
        }

        /* ================================
           STATS BAR
        ================================ */
        .stats-bar {
            background: rgba(13,21,38,0.95);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 1.5rem 4%;
            display: flex;
            justify-content: center;
            gap: 4rem;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }

        .stat-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .stat-number {
            font-family: var(--font-title);
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(90deg, var(--green), var(--blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--muted);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 0.3rem;
        }

        /* ================================
           FEATURES
        ================================ */
        .section {
            padding: 6rem 4%;
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-family: var(--font-title);
            font-size: 0.7rem;
            letter-spacing: 3px;
            color: var(--green);
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .section-badge::before {
            content: '';
            width: 30px; height: 1px;
            background: var(--green);
        }

        .section-title {
            font-family: var(--font-title);
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 900;
            margin-bottom: 1rem;
            letter-spacing: 2px;
        }

        .section-title span {
            background: linear-gradient(90deg, var(--gold), var(--silver));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-sub {
            color: var(--silver);
            font-size: 1.05rem;
            max-width: 520px;
            line-height: 1.7;
            margin-bottom: 3.5rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .feature-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            transition: all 0.4s ease;
            opacity: 0;
            transform: translateY(40px);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--green), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .feature-card:hover::before {
            transform: translateX(0);
        }

        .feature-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .feature-card:hover {
            border-color: rgba(0,255,136,0.4);
            box-shadow: 0 0 30px rgba(0,255,136,0.1), 0 20px 40px rgba(0,0,0,0.3);
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.2rem;
        }

        .feature-icon .material-icons { font-size: 1.6rem; }

        .icon-green { background: rgba(0,255,136,0.12); color: var(--green); border: 1px solid rgba(0,255,136,0.2); }
        .icon-blue  { background: rgba(26,111,255,0.12); color: var(--blue);  border: 1px solid rgba(26,111,255,0.2); }
        .icon-gold  { background: rgba(255,215,0,0.12);  color: var(--gold);  border: 1px solid rgba(255,215,0,0.2); }

        .feature-title {
            font-family: var(--font-title);
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 0.7rem;
            color: var(--text);
        }

        .feature-text {
            font-size: 0.95rem;
            color: var(--silver);
            line-height: 1.7;
        }

        /* ================================
           HOW IT WORKS
        ================================ */
        .how-section {
            padding: 6rem 4%;
            background: rgba(10,14,26,0.6);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .how-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(7,11,20,0.95) 0%, rgba(10,14,26,0.9) 100%),
                url('/assets/images/bg-auth.jpg') center/cover;
        }

        .how-inner {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 0 auto;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .step-card {
            text-align: center;
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.5s ease;
        }

        .step-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .step-number {
            font-family: var(--font-title);
            font-size: 3.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, rgba(0,255,136,0.15), rgba(26,111,255,0.15));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
            position: relative;
        }

        .step-number::after {
            content: attr(data-num);
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--green), var(--blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            opacity: 0.3;
        }

        .step-icon {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: var(--card);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem;
            box-shadow: 0 0 20px var(--green-glow);
        }

        .step-icon .material-icons {
            font-size: 1.8rem;
            color: var(--green);
        }

        .step-title {
            font-family: var(--font-title);
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 0.6rem;
            color: var(--text);
        }

        .step-text {
            font-size: 0.95rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* Step connector */
        .steps-grid .step-card:not(:last-child) {
            position: relative;
        }

        /* ================================
           SHOWCASE / MOCKUP
        ================================ */
        .showcase-section {
            padding: 6rem 4%;
            max-width: 1100px;
            margin: 0 auto;
        }

        .showcase-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        @media (max-width: 768px) {
            .showcase-grid { grid-template-columns: 1fr; }
        }

        .showcase-mockup {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 0 40px rgba(0,255,136,0.1), 0 30px 60px rgba(0,0,0,0.4);
            opacity: 0;
            transform: translateX(-40px);
            transition: all 0.8s ease;
        }

        .showcase-mockup.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .mockup-bar {
            background: rgba(255,255,255,0.04);
            border-bottom: 1px solid var(--border);
            padding: 0.7rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .mockup-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
        }

        .mockup-dot:nth-child(1) { background: #ff5f57; }
        .mockup-dot:nth-child(2) { background: #febc2e; }
        .mockup-dot:nth-child(3) { background: #28c840; }

        .mockup-url {
            margin-left: 0.5rem;
            background: rgba(255,255,255,0.06);
            border-radius: 4px;
            padding: 0.2rem 0.8rem;
            font-size: 0.75rem;
            color: var(--muted);
            font-family: var(--font-title);
            letter-spacing: 1px;
        }

        .mockup-content { padding: 1.2rem; }

        .mockup-post {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(0,255,136,0.12);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 0.8rem;
        }

        .mockup-post-header {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.7rem;
        }

        .mockup-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-dark), var(--green-dark));
            border: 1px solid var(--green);
            flex-shrink: 0;
        }

        .mockup-username {
            font-family: var(--font-title);
            font-size: 0.7rem;
            color: var(--text);
            letter-spacing: 1px;
        }

        .mockup-time {
            font-size: 0.65rem;
            color: var(--muted);
        }

        .mockup-text {
            font-size: 0.82rem;
            color: var(--silver);
            line-height: 1.5;
            margin-bottom: 0.7rem;
        }

        .mockup-footer {
            display: flex;
            gap: 0.8rem;
        }

        .mockup-action {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.72rem;
            color: var(--muted);
        }

        .mockup-action .material-icons { font-size: 0.9rem; }
        .mockup-action.liked { color: #ff4466; }

        .showcase-text {
            opacity: 0;
            transform: translateX(40px);
            transition: all 0.8s ease 0.2s;
        }

        .showcase-text.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .showcase-features {
            list-style: none;
            margin-top: 2rem;
        }

        .showcase-features li {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.6rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 0.95rem;
            color: var(--silver);
        }

        .showcase-features li .material-icons {
            font-size: 1.1rem;
            color: var(--green);
            flex-shrink: 0;
        }

        /* ================================
           CTA SECTION
        ================================ */
        .cta-section {
            padding: 7rem 4%;
            text-align: center;
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(7,11,20,0.97) 0%, rgba(10,14,26,0.93) 100%),
                url('/assets/images/bg-profile.jpg') center/cover fixed;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(26,111,255,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(26,111,255,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .cta-inner {
            position: relative;
            z-index: 1;
            max-width: 620px;
            margin: 0 auto;
        }

        .cta-title {
            font-family: var(--font-title);
            font-size: clamp(1.8rem, 5vw, 3rem);
            font-weight: 900;
            letter-spacing: 3px;
            margin-bottom: 1.2rem;
            line-height: 1.2;
        }

        .cta-title .highlight {
            background: linear-gradient(90deg, var(--green), var(--blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cta-sub {
            font-size: 1.05rem;
            color: var(--silver);
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }

        .cta-btns {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* ================================
           FOOTER
        ================================ */
        .footer {
            background: rgba(7,11,20,0.98);
            border-top: 1px solid var(--border);
            padding: 2rem 4%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-brand {
            font-family: var(--font-title);
            font-size: 1rem;
            font-weight: 900;
            background: linear-gradient(90deg, var(--green), var(--blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 3px;
        }

        .footer-text {
            font-size: 0.82rem;
            color: var(--muted);
            letter-spacing: 1px;
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-links a {
            font-size: 0.82rem;
            color: var(--muted);
            letter-spacing: 1px;
            transition: color 0.3s ease;
        }

        .footer-links a:hover { color: var(--green); }

        /* ================================
           ANIMATIONS
        ================================ */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* ================================
           SCROLLBAR
        ================================ */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(var(--green-dark), var(--blue-dark));
            border-radius: 3px;
        }

        /* ================================
           RESPONSIVE
        ================================ */
        @media (max-width: 600px) {
            .stats-bar { gap: 2rem; }
            .nav-links a:not(.btn-cta) { display: none; }
            .hero-title { letter-spacing: 2px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <a href="/landing.php" class="nav-brand">
        <span class="material-icons">hub</span>
        SOCIALNET
    </a>
    <div class="nav-links">
        <a href="#features">Features</a>
        <a href="#how">Comment</a>
        <a href="/login">Connexion</a>
        <a href="/register" class="btn-cta">Rejoindre</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="hero-content">
        <div class="hero-badge">
            <div class="dot"></div>
            RÉSEAU SOCIAL NOUVELLE GÉNÉRATION
        </div>

        <h1 class="hero-title">
            <span class="line-gold">BIENVENUE DANS</span>
            SOCIALNET
        </h1>

        <p class="hero-sub">
            Connectez-vous, partagez vos idées et interagissez
            dans un espace cyberpunk unique. Votre réseau,
            vos règles.
        </p>

        <div class="hero-cta">
            <a href="/register" class="btn-hero-primary">
                <span class="material-icons">rocket_launch</span>
                Créer un compte
            </a>
            <a href="/login" class="btn-hero-secondary">
                <span class="material-icons">login</span>
                Se connecter
            </a>
        </div>
    </div>

    <div class="scroll-indicator">
        <div class="scroll-line"></div>
        SCROLL
    </div>
</section>

<!-- STATS BAR -->
<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-number" data-target="10">0</div>
        <div class="stat-label">Publications</div>
    </div>
    <div class="stat-item">
        <div class="stat-number" data-target="5">0</div>
        <div class="stat-label">Utilisateurs</div>
    </div>
    <div class="stat-item">
        <div class="stat-number" data-target="3">0</div>
        <div class="stat-label">Interactions</div>
    </div>
    <div class="stat-item">
        <div class="stat-number" data-target="100">0</div>
        <div class="stat-label">% Open Source</div>
    </div>
</div>

<!-- FEATURES -->
<section class="section" id="features">
    <div class="section-badge">
        <span class="material-icons" style="font-size:0.9rem">auto_awesome</span>
        Fonctionnalités
    </div>
    <h2 class="section-title">Tout ce dont vous <span>avez besoin</span></h2>
    <p class="section-sub">
        Une plateforme complète pour partager, interagir
        et vous connecter avec les autres utilisateurs du réseau.
    </p>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon icon-green">
                <span class="material-icons">article</span>
            </div>
            <div class="feature-title">Publications</div>
            <div class="feature-text">
                Partagez vos idées en texte ou avec des images.
                Modifiez et supprimez vos publications à tout moment.
            </div>
        </div>

        <div class="feature-card">
            <div class="feature-icon icon-blue">
                <span class="material-icons">favorite</span>
            </div>
            <div class="feature-title">Likes & Réactions</div>
            <div class="feature-text">
                Likez les publications en temps réel avec des animations
                fluides. Montrez votre appréciation instantanément.
            </div>
        </div>

        <div class="feature-card">
            <div class="feature-icon icon-gold">
                <span class="material-icons">forum</span>
            </div>
            <div class="feature-title">Commentaires</div>
            <div class="feature-text">
                Engagez des discussions sous chaque publication.
                Système de pagination pour une navigation fluide.
            </div>
        </div>

        <div class="feature-card">
            <div class="feature-icon icon-green">
                <span class="material-icons">manage_accounts</span>
            </div>
            <div class="feature-title">Profil personnalisé</div>
            <div class="feature-text">
                Personnalisez votre avatar, bio et username.
                Votre identité unique dans le réseau.
            </div>
        </div>

        <div class="feature-card">
            <div class="feature-icon icon-blue">
                <span class="material-icons">add_photo_alternate</span>
            </div>
            <div class="feature-title">Upload médias</div>
            <div class="feature-text">
                Ajoutez des images à vos publications avec
                compression automatique et affichage responsive.
            </div>
        </div>

        <div class="feature-card">
            <div class="feature-icon icon-gold">
                <span class="material-icons">security</span>
            </div>
            <div class="feature-title">Sécurité</div>
            <div class="feature-text">
                Authentification sécurisée avec hashage BCrypt,
                sessions PHP et protection CSRF.
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-section" id="how">
    <div class="how-inner">
        <div class="section-badge">
            <span class="material-icons" style="font-size:0.9rem">route</span>
            Comment ça marche
        </div>
        <h2 class="section-title">Démarrez en <span>3 étapes</span></h2>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-icon">
                    <span class="material-icons">person_add</span>
                </div>
                <div class="step-title">01 — Créer un compte</div>
                <div class="step-text">
                    Inscrivez-vous en quelques secondes avec
                    votre email et un username unique.
                </div>
            </div>

            <div class="step-card">
                <div class="step-icon">
                    <span class="material-icons">edit_note</span>
                </div>
                <div class="step-title">02 — Publier</div>
                <div class="step-text">
                    Partagez vos pensées, photos et idées
                    avec la communauté du réseau.
                </div>
            </div>

            <div class="step-card">
                <div class="step-icon">
                    <span class="material-icons">groups</span>
                </div>
                <div class="step-title">03 — Interagir</div>
                <div class="step-text">
                    Likez, commentez et connectez-vous
                    avec les autres membres du réseau.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SHOWCASE -->
<section class="showcase-section">
    <div class="showcase-grid">
        <!-- MOCKUP -->
        <div class="showcase-mockup">
            <div class="mockup-bar">
                <div class="mockup-dot"></div>
                <div class="mockup-dot"></div>
                <div class="mockup-dot"></div>
                <div class="mockup-url">localhost:8080</div>
            </div>
            <div class="mockup-content">
                <!-- Post 1 -->
                <div class="mockup-post">
                    <div class="mockup-post-header">
                        <div class="mockup-avatar"></div>
                        <div>
                            <div class="mockup-username">CYBER_USER_01</div>
                            <div class="mockup-time">il y a 2 min</div>
                        </div>
                    </div>
                    <div class="mockup-text">
                        Bienvenue dans le réseau SocialNet. Le futur est maintenant connecté.
                    </div>
                    <div class="mockup-footer">
                        <div class="mockup-action liked">
                            <span class="material-icons">favorite</span> 24
                        </div>
                        <div class="mockup-action">
                            <span class="material-icons">chat_bubble_outline</span> 8
                        </div>
                    </div>
                </div>

                <!-- Post 2 -->
                <div class="mockup-post">
                    <div class="mockup-post-header">
                        <div class="mockup-avatar" style="background:linear-gradient(135deg,#cc6a00,#ff8800)"></div>
                        <div>
                            <div class="mockup-username">NET_RUNNER_X</div>
                            <div class="mockup-time">il y a 15 min</div>
                        </div>
                    </div>
                    <div class="mockup-text">
                        Le cyberpunk n'est plus une fiction. C'est notre réalité quotidienne.
                    </div>
                    <div class="mockup-footer">
                        <div class="mockup-action">
                            <span class="material-icons">favorite_border</span> 12
                        </div>
                        <div class="mockup-action">
                            <span class="material-icons">chat_bubble_outline</span> 3
                        </div>
                    </div>
                </div>

                <!-- Post 3 -->
                <div class="mockup-post" style="opacity:0.5">
                    <div class="mockup-post-header">
                        <div class="mockup-avatar" style="background:linear-gradient(135deg,#6a00cc,#8800ff)"></div>
                        <div>
                            <div class="mockup-username">GHOST_RIDER_7</div>
                            <div class="mockup-time">il y a 1h</div>
                        </div>
                    </div>
                    <div class="mockup-text">
                        Dans ce réseau, chaque connexion compte...
                    </div>
                </div>
            </div>
        </div>

        <!-- TEXT -->
        <div class="showcase-text">
            <div class="section-badge">
                <span class="material-icons" style="font-size:0.9rem">hub</span>
                Interface
            </div>
            <h2 class="section-title">Une interface <span>immersive</span></h2>
            <p style="color:var(--silver);line-height:1.7;margin-bottom:1rem">
                Conçue avec un thème cyberpunk sombre et des effets visuels
                soignés pour une expérience utilisateur unique.
            </p>

            <ul class="showcase-features">
                <li>
                    <span class="material-icons">check_circle</span>
                    Design Cyberpunk thème sombre vert & bleu
                </li>
                <li>
                    <span class="material-icons">check_circle</span>
                    Animations fluides et réactives
                </li>
                <li>
                    <span class="material-icons">check_circle</span>
                    Feed en temps réel avec pagination
                </li>
                <li>
                    <span class="material-icons">check_circle</span>
                    Likes AJAX sans rechargement de page
                </li>
                <li>
                    <span class="material-icons">check_circle</span>
                    Responsive mobile & desktop
                </li>
                <li>
                    <span class="material-icons">check_circle</span>
                    Upload et compression d'images
                </li>
            </ul>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-inner">
        <h2 class="cta-title">
            Prêt à rejoindre<br>
            <span class="highlight">le réseau ?</span>
        </h2>
        <p class="cta-sub">
            Créez votre compte gratuitement et rejoignez
            la communauté SocialNet dès maintenant.
        </p>
        <div class="cta-btns">
            <a href="/register" class="btn-hero-primary">
                <span class="material-icons">rocket_launch</span>
                Créer un compte
            </a>
            <a href="/login" class="btn-hero-secondary">
                <span class="material-icons">login</span>
                Se connecter
            </a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-brand">SOCIALNET</div>
    <div class="footer-text"> &copy; 2026 by 3714-3673-3545-3626 .L2_ASR MAHAY MI-DEV </div>
    <div class="footer-links">
        <a href="/login">Connexion</a>
        <a href="/register">Inscription</a>
    </div>
</footer>

<script>
/* ================================
   NAVBAR SCROLL
================================ */
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 50);
});

/* ================================
   SCROLL REVEAL
================================ */
const revealEls = document.querySelectorAll(
    '.feature-card, .step-card, .stat-item, .showcase-mockup, .showcase-text'
);

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
            setTimeout(() => {
                entry.target.classList.add('visible');
            }, entry.target.dataset.delay || 0);
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.15 });

// Stagger delays
document.querySelectorAll('.feature-card').forEach((el, i) => {
    el.dataset.delay = i * 100;
    observer.observe(el);
});

document.querySelectorAll('.step-card').forEach((el, i) => {
    el.dataset.delay = i * 150;
    observer.observe(el);
});

document.querySelectorAll('.stat-item').forEach((el, i) => {
    el.dataset.delay = i * 100;
    observer.observe(el);
});

document.querySelectorAll('.showcase-mockup, .showcase-text').forEach(el => {
    observer.observe(el);
});

/* ================================
   COUNTER ANIMATION
================================ */
function animateCounter(el, target) {
    let current = 0;
    const duration = 1500;
    const step = target / (duration / 16);

    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        el.textContent = Math.floor(current) + (target === 100 ? '%' : '+');
    }, 16);
}

const statObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const numEl  = entry.target.querySelector('.stat-number');
            const target = parseInt(numEl.dataset.target);
            animateCounter(numEl, target);
            statObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

document.querySelectorAll('.stat-item').forEach(el => statObserver.observe(el));

/* ================================
   PARALLAX ORBS
================================ */
document.addEventListener('mousemove', (e) => {
    const x = (e.clientX / window.innerWidth - 0.5) * 20;
    const y = (e.clientY / window.innerHeight - 0.5) * 20;

    document.querySelectorAll('.orb').forEach((orb, i) => {
        const factor = (i + 1) * 0.5;
        orb.style.transform = `translate(${x * factor}px, ${y * factor}px)`;
    });
});

/* ================================
   SMOOTH SCROLL NAV LINKS
================================ */
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        e.preventDefault();
        const target = document.querySelector(a.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>

</body>
</html>