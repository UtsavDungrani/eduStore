/**
 * Frontend Security Measures - Optimized for Mobile Performance
 * - Disables Right-Click
 * - Disables common DevTools shortcuts (F12, Ctrl+Shift+I, etc.)
 * - Detects if DevTools is open and blacks out the screen (desktop only)
 * - Disables text selection and dragging (via CSS in layout)
 */

(function() {
    'use strict';

    // Detect if user is on mobile device
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || 
                     window.innerWidth <= 768;

    // 1. Disable Right-Click
    document.addEventListener('contextmenu', e => e.preventDefault());

    // 2. Disable Keyboard Shortcuts
    document.addEventListener('keydown', e => {
        // F12
        if (e.keyCode === 123) {
            e.preventDefault();
            return false;
        }

        // Ctrl+Shift+I (Inspect), Ctrl+Shift+J (Console), Ctrl+Shift+C (Elements)
        if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67)) {
            e.preventDefault();
            return false;
        }

        // Ctrl+U (View Source)
        if (e.ctrlKey && e.keyCode === 85) {
            e.preventDefault();
            return false;
        }

        // Ctrl+S (Save Page)
        if (e.ctrlKey && e.keyCode === 83) {
            e.preventDefault();
            return false;
        }

        // Print Screen (Basic protection, doesn't stop Snipping Tool but alerts/blurs)
        if (e.key === 'PrintScreen') {
            blackOut(true);
            navigator.clipboard.writeText(''); // Clear clipboard
            alert('Screenshots are disabled on this website.');
            setTimeout(() => blackOut(false), 2000);
        }
    });

    // 3. DevTools Detection & Blackout
    function blackOut(state) {
        if (state) {
            document.body.classList.add('devtools-open');
        } else {
            document.body.classList.remove('devtools-open');
        }
    }

    // Skip heavy DevTools detection on mobile devices to improve performance
    if (isMobile) {
        console.log('Mobile device detected - DevTools detection disabled for performance');
        return; // Exit early on mobile
    }

    // Desktop-only: Detection using debugger (runs only on user interaction to avoid constant lag)
    let debuggerCheckEnabled = false;
    
    // Enable debugger check only after user interaction (click, scroll, etc.)
    const enableDebuggerCheck = () => {
        if (!debuggerCheckEnabled) {
            debuggerCheckEnabled = true;
            
            // Run check only once every 5 seconds instead of every second
            setInterval(() => {
                const startTime = Date.now();
                debugger;
                const endTime = Date.now();
                
                // If debugger paused execution, the difference will be large
                if (endTime - startTime > 100) {
                    blackOut(true);
                }
            }, 5000); // Increased from 1000ms to 5000ms
        }
    };
    
    // Activate on first user interaction
    ['click', 'scroll', 'touchstart'].forEach(event => {
        document.addEventListener(event, enableDebuggerCheck, { once: true });
    });

    // Desktop-only: Debounced resize check
    const threshold = 160;
    let resizeTimeout;
    
    const checkSize = () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const widthThreshold = window.outerWidth - window.innerWidth > threshold;
            const heightThreshold = window.outerHeight - window.innerHeight > threshold;
            
            if (widthThreshold || heightThreshold) {
                // This can sometimes trigger on zoom or high-res screens, 
                // so we combine it with other methods or use with caution.
                // blackOut(true); 
            }
        }, 300); // Debounce resize events
    };
    window.addEventListener('resize', checkSize);

    // Desktop-only: Visual feedback for DevTools opening
    let devtools = {
        isOpen: false,
        orientation: undefined
    };

    const emitEvent = (isOpen, orientation) => {
        if (isOpen) {
            blackOut(true);
        } else {
            // We usually don't want to un-blackout once detected until page refresh
            // blackOut(false);
        }
    };

    const main = ({emitEvents = true} = {}) => {
        const widthThreshold = window.outerWidth - window.innerWidth > threshold;
        const heightThreshold = window.outerHeight - window.innerHeight > threshold;
        const orientation = widthThreshold ? 'vertical' : 'horizontal';

        if (
            !(heightThreshold && widthThreshold) &&
            ((window.Firebug && window.Firebug.chrome && window.Firebug.chrome.isInitialized) || widthThreshold || heightThreshold)
        ) {
            if (!devtools.isOpen || devtools.orientation !== orientation) {
                if (emitEvents) {
                    emitEvent(true, orientation);
                }
            }

            devtools.isOpen = true;
            devtools.orientation = orientation;
        } else {
            if (devtools.isOpen && emitEvents) {
                // emitEvent(false, undefined);
            }

            devtools.isOpen = false;
            devtools.orientation = undefined;
        }
    };

    main({emitEvents: false});
    setInterval(main, 2000); // Increased from 500ms to 2000ms

})();
