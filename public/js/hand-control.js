/**
 * MediaPipe Hands Integration for Futuristic Hand Control
 * Uses the heavy model for accurate hand tracking and gesture recognition
 */

class HandController {
    constructor() {
        this.enabled = false;
        this.hands = null;
        this.camera = null;
        this.videoElement = null;
        this.canvasElement = null;
        this.canvasCtx = null;
        this.lastGesture = null;
        this.gestureStartTime = 0;
        this.cursorElement = null;
        this.isPointing = false;
        this.lastScrollTime = 0;
        this.scrollCooldown = 300; // ms
        this.scrollAmount = 200; // px - amount to scroll per swipe gesture
    }

    async initialize() {
        // Create video and canvas elements
        this.createVideoElements();
        this.createVirtualCursor();

        // Initialize MediaPipe Hands with heavy model
        this.hands = new Hands({
            locateFile: (file) => {
                return `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`;
            }
        });

        this.hands.setOptions({
            maxNumHands: 1,
            modelComplexity: 2, // Heavy model (0=lite, 1=full, 2=heavy)
            minDetectionConfidence: 0.7,
            minTrackingConfidence: 0.7
        });

        this.hands.onResults((results) => this.onResults(results));

        // Initialize camera
        this.camera = new Camera(this.videoElement, {
            onFrame: async () => {
                if (this.enabled) {
                    await this.hands.send({ image: this.videoElement });
                }
            },
            width: 640,
            height: 480
        });

        console.log('Hand Controller initialized with heavy model');
    }

    createVideoElements() {
        // Create container
        const container = document.createElement('div');
        container.id = 'hand-control-container';
        container.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            display: none;
            background: #000;
        `;

        // Create video element
        this.videoElement = document.createElement('video');
        this.videoElement.style.cssText = `
            width: 320px;
            height: 240px;
            transform: scaleX(-1);
            display: block;
        `;

        // Create canvas overlay
        this.canvasElement = document.createElement('canvas');
        this.canvasElement.width = 640;
        this.canvasElement.height = 480;
        this.canvasElement.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            width: 320px;
            height: 240px;
            transform: scaleX(-1);
        `;
        this.canvasCtx = this.canvasElement.getContext('2d');

        container.appendChild(this.videoElement);
        container.appendChild(this.canvasElement);
        document.body.appendChild(container);
    }

    createVirtualCursor() {
        this.cursorElement = document.createElement('div');
        this.cursorElement.id = 'virtual-cursor';
        this.cursorElement.style.cssText = `
            position: fixed;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(11, 94, 215, 0.7);
            border: 3px solid #fff;
            pointer-events: none;
            z-index: 10000;
            display: none;
            box-shadow: 0 0 20px rgba(11, 94, 215, 0.8);
            transition: transform 0.1s ease, background 0.2s ease;
        `;
        document.body.appendChild(this.cursorElement);
    }

    onResults(results) {
        // Clear canvas
        this.canvasCtx.save();
        this.canvasCtx.clearRect(0, 0, this.canvasElement.width, this.canvasElement.height);

        // Draw hand landmarks
        if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
            for (const landmarks of results.multiHandLandmarks) {
                drawConnectors(this.canvasCtx, landmarks, HAND_CONNECTIONS, {
                    color: '#00FF00',
                    lineWidth: 2
                });
                drawLandmarks(this.canvasCtx, landmarks, {
                    color: '#0B5ED7',
                    lineWidth: 1,
                    radius: 3
                });

                // Process gestures
                this.processGestures(landmarks);
            }
        } else {
            // Hide cursor when no hand detected
            this.cursorElement.style.display = 'none';
            this.isPointing = false;
        }

        this.canvasCtx.restore();
    }

    processGestures(landmarks) {
        // Get key points
        const indexTip = landmarks[8];
        const indexDip = landmarks[7];
        const indexPip = landmarks[6];
        const middleTip = landmarks[12];
        const ringTip = landmarks[16];
        const pinkyTip = landmarks[20];
        const thumbTip = landmarks[4];
        const wrist = landmarks[0];

        // Convert to screen coordinates
        const x = window.innerWidth * (1 - indexTip.x);
        const y = window.innerHeight * indexTip.y;

        // Update cursor position
        this.cursorElement.style.left = `${x - 15}px`;
        this.cursorElement.style.top = `${y - 15}px`;
        this.cursorElement.style.display = 'block';

        // Detect pointing gesture (index finger extended, others closed)
        const indexExtended = indexTip.y < indexDip.y && indexDip.y < indexPip.y;
        const middleClosed = middleTip.y > landmarks[10].y;
        const ringClosed = ringTip.y > landmarks[14].y;
        const pinkyClosed = pinkyTip.y > landmarks[18].y;

        if (indexExtended && middleClosed && ringClosed && pinkyClosed) {
            this.handlePointingGesture(x, y);
        } else {
            this.isPointing = false;
            this.cursorElement.style.transform = 'scale(1)';
        }

        // Detect open palm (all fingers extended) - pause gesture
        const allExtended = indexExtended &&
            middleTip.y < landmarks[10].y &&
            ringTip.y < landmarks[14].y &&
            pinkyTip.y < landmarks[18].y;

        if (allExtended) {
            this.handleOpenPalm();
        }

        // Detect swipe gestures for scrolling
        this.detectSwipeGesture(wrist, indexTip);
    }

    handlePointingGesture(x, y) {
        if (!this.isPointing) {
            this.isPointing = true;
            this.gestureStartTime = Date.now();
        }

        // Visual feedback
        this.cursorElement.style.transform = 'scale(1.3)';
        this.cursorElement.style.background = 'rgba(11, 94, 215, 0.9)';

        // Simulate click if pointing for more than 1 second
        const holdDuration = Date.now() - this.gestureStartTime;
        if (holdDuration > 1000 && holdDuration < 1100) {
            this.simulateClick(x, y);
        }
    }

    handleOpenPalm() {
        const now = Date.now();
        if (this.lastGesture !== 'palm' || now - this.gestureStartTime > 2000) {
            this.lastGesture = 'palm';
            this.gestureStartTime = now;
            this.cursorElement.style.background = 'rgba(255, 193, 7, 0.9)';
            
            // Show pause feedback
            this.showGestureFeedback('⏸️ Pausa');
        }
    }

    detectSwipeGesture(wrist, indexTip) {
        const now = Date.now();
        if (now - this.lastScrollTime < this.scrollCooldown) {
            return;
        }

        // Detect vertical movement
        const verticalMovement = indexTip.y - wrist.y;

        if (Math.abs(verticalMovement) > 0.3) {
            this.lastScrollTime = now;

            if (verticalMovement > 0.3) {
                // Swipe down - scroll down
                window.scrollBy({
                    top: this.scrollAmount,
                    behavior: 'smooth'
                });
                this.showGestureFeedback('⬇️ Scroll Down');
            } else if (verticalMovement < -0.3) {
                // Swipe up - scroll up
                window.scrollBy({
                    top: -this.scrollAmount,
                    behavior: 'smooth'
                });
                this.showGestureFeedback('⬆️ Scroll Up');
            }
        }
    }

    simulateClick(x, y) {
        const element = document.elementFromPoint(x, y);
        if (!element) {
            return;
        }

        // Validate element is safe to click
        // Avoid clicking on certain sensitive elements without explicit user action
        const tagName = element.tagName.toLowerCase();
        const isFormSubmit = tagName === 'button' && element.type === 'submit';
        const isLink = tagName === 'a';
        const isInput = tagName === 'input' || tagName === 'textarea';
        
        // For safety, only allow clicks on non-destructive elements
        // or elements explicitly marked as gesture-safe
        if (isFormSubmit || (isLink && !element.hasAttribute('data-gesture-safe'))) {
            console.log('Gesture click blocked on sensitive element:', element);
            this.showGestureFeedback('⚠️ Elemento protegido');
            return;
        }

        // Visual feedback
        this.cursorElement.style.background = 'rgba(40, 167, 69, 0.9)';
        this.showGestureFeedback('👆 Click');

        // Simulate click
        const clickEvent = new MouseEvent('click', {
            view: window,
            bubbles: true,
            cancelable: true,
            clientX: x,
            clientY: y
        });
        element.dispatchEvent(clickEvent);

        // Reset after click
        setTimeout(() => {
            this.isPointing = false;
            this.cursorElement.style.background = 'rgba(11, 94, 215, 0.7)';
        }, 300);
    }

    showGestureFeedback(text) {
        // Remove existing feedback
        const existing = document.getElementById('gesture-feedback');
        if (existing) {
            existing.remove();
        }

        const feedback = document.createElement('div');
        feedback.id = 'gesture-feedback';
        feedback.textContent = text;
        feedback.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px 40px;
            border-radius: 10px;
            font-size: 24px;
            z-index: 10001;
            pointer-events: none;
            animation: fadeInOut 1s ease;
        `;
        document.body.appendChild(feedback);

        setTimeout(() => feedback.remove(), 1000);
    }

    async start() {
        if (!this.camera) {
            await this.initialize();
        }

        this.enabled = true;
        await this.camera.start();
        const container = document.getElementById('hand-control-container');
        if (container) {
            container.style.display = 'block';
        }
        console.log('Hand control started');
    }

    stop() {
        this.enabled = false;
        if (this.camera) {
            this.camera.stop();
        }
        const container = document.getElementById('hand-control-container');
        if (container) {
            container.style.display = 'none';
        }
        if (this.cursorElement) {
            this.cursorElement.style.display = 'none';
        }
        console.log('Hand control stopped');
    }

    async toggle() {
        if (this.enabled) {
            this.stop();
        } else {
            await this.start();
        }
    }
}

// Add fadeInOut animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInOut {
        0% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
        20% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        80% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        100% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
    }
`;
document.head.appendChild(style);

// Export for global use
window.HandController = HandController;
