<style>
    @keyframes badge-shine {
        0% { transform: translateX(-100%) skewX(-15deg); }
        15%, 100% { transform: translateX(150%) skewX(-15deg); }
    }

    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        white-space: nowrap;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        background: var(--badge-bg);
        color: var(--badge-text, white);
        letter-spacing: 0.025em;
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        z-index: 1;
        overflow: hidden; /* Fix shine leak outside border-radius */
        /* Use filter for shape-aware shadows */
        filter: drop-shadow(0 4px 6px var(--badge-shadow));
        /* Fallback default vars */
        --badge-bg: linear-gradient(135deg, #fbbf24, #d97706);
        --badge-shadow: rgba(217, 119, 6, 0.4);
    }

    /* Shine Effect Container */
    .badge::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg, 
            transparent, 
            rgba(255, 255, 255, 0.4), 
            transparent
        );
        transform: translateX(-100%) skewX(-15deg);
        animation: badge-shine 3s infinite ease-in-out;
        pointer-events: none;
        /* Ensure shine respects the shape */
        clip-path: inherit; 
        border-radius: inherit;
    }

    .badge:hover {
        transform: scale(1.05) translateY(-2px);
        filter: drop-shadow(0 8px 12px var(--badge-shadow));
        z-index: 10;
    }

    /* Basic Shapes */
    .badge-pill {
        padding: 8px 20px;
        border-radius: 9999px;
    }

    .badge-soft_rectangle {
        padding: 8px 16px;
        border-radius: 8px;
    }

    .badge-tag {
        padding: 8px 16px;
        padding-left: 20px;
        border-radius: 0 8px 8px 0;
        clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%, 10px 50%); /* Using clip-path for tag ensures shine works */
    }

    .badge-tag::before {
        display: none; /* Removed pseudo-element border as it complicates clip-path shining */
    }

    .badge-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        font-size: 10px;
        padding: 4px;
        line-height: 1.1;
    }

    .badge-square {
        width: 64px;
        height: 64px;
        padding: 4px;
        border-radius: 4px;
        font-size: 12px;
        line-height: 1.1;
        white-space: normal;
        text-align: center;
    }

    /* Angled & Ribbon Styles */
    .badge-banner {
        width: 48px;
        padding-top: 10px;
        padding-bottom: 14px;
        padding-left: 4px;
        padding-right: 4px;
        min-height: 56px;
        flex-direction: column;
        text-align: center;
        line-height: 1.1;
        font-size: 10px;
        clip-path: polygon(100% 0, 100% 100%, 50% 85%, 0 100%, 0 0);
    }

    .badge-flag {
        padding: 8px 24px 8px 16px;
        clip-path: polygon(0 0, 90% 0, 100% 50%, 90% 100%, 0 100%);
    }

    .badge-arrow {
        padding: 8px 28px 8px 16px;
        clip-path: polygon(0 0, 85% 0, 100% 50%, 85% 100%, 0 100%);
    }

    .badge-ribbon_left {
        padding: 8px 16px 8px 24px;
        clip-path: polygon(0 0, 100% 15%, 100% 85%, 0 100%, 20% 50%);
    }

    .badge-ribbon_right {
        padding: 8px 24px 8px 16px;
        clip-path: polygon(100% 0, 0 15%, 0 85%, 100% 100%, 80% 50%);
    }

    /* Special Shapes */
    .badge-sticker {
        padding: 12px 20px;
        border-radius: 50%;
        transform: rotate(-12deg);
        /* Filter allows shadow to rotate with the element naturally */
    }

    .badge-sticker_circle {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        transform: rotate(12deg);
        border: 2px dashed rgba(255, 255, 255, 0.8);
        padding: 4px;
        font-size: 0.7rem;
    }

    .badge-label {
        padding: 8px 24px 8px 20px;
        clip-path: polygon(0 0, 90% 0, 100% 50%, 90% 100%, 0 100%, 15% 50%);
    }

    .badge-stripe {
        padding: 8px 24px;
        transform: skewX(-15deg);
        border-radius: 4px;
    }

    .badge-diamond {
        padding: 16px 20px;
        clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
    }

    .badge-star {
        padding: 24px 28px;
        clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
    }

    .badge-splat {
        padding: 12px 20px;
        border-radius: 45% 50% 48% 50% / 50% 48% 52% 50%;
    }

    /* 
       PREMIUM COLORS with CSS Variables for Filters
    */
    
    .badge-golden {
        --badge-bg: linear-gradient(135deg, #fcd34d 0%, #fbbf24 30%, #d97706 70%, #b45309 100%);
        --badge-shadow: rgba(217, 119, 6, 0.6);
        --badge-text: black;
        border: 1px solid rgba(255,255,255,0.4);
    }

    .badge-red {
        --badge-bg: linear-gradient(135deg, #fca5a5 0%, #ef4444 30%, #b91c1c 70%, #991b1b 100%);
        --badge-shadow: rgba(220, 38, 38, 0.6);
        border: 1px solid rgba(255,255,255,0.3);
    }

    .badge-blue {
        --badge-bg: linear-gradient(135deg, #60a5fa 0%, #3b82f6 30%, #1d4ed8 70%, #172554 100%);
        --badge-shadow: rgba(37, 99, 235, 0.6);
        border: 1px solid rgba(255,255,255,0.3);
    }

    .badge-green {
        --badge-bg: linear-gradient(135deg, #6ee7b7 0%, #10b981 30%, #047857 70%, #064e3b 100%);
        --badge-shadow: rgba(5, 150, 105, 0.6);
        border: 1px solid rgba(255,255,255,0.3);
    }

    .badge-black {
        --badge-bg: linear-gradient(135deg, #4b5563 0%, #1f2937 30%, #000000 100%);
        --badge-shadow: rgba(0, 0, 0, 0.6);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .badge-pink {
        --badge-bg: linear-gradient(135deg, #f9a8d4 0%, #ec4899 30%, #be185d 70%, #9d174d 100%);
        --badge-shadow: rgba(219, 39, 119, 0.6);
        border: 1px solid rgba(255,255,255,0.3);
    }

    .badge-orange {
        --badge-bg: linear-gradient(135deg, #fdba74 0%, #f97316 30%, #c2410c 70%, #9a3412 100%);
        --badge-shadow: rgba(234, 88, 12, 0.6);
        border: 1px solid rgba(255,255,255,0.3);
    }
</style>