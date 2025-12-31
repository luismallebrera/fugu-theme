(function() {
    'use strict';
    
    class ImagePanZoom {
        constructor(img) {
            this.img = img;
            this.container = img.parentElement;
            this.scale = 1;
            this.posX = 0;
            this.posY = 0;
            this.isDragging = false;
            this.startX = 0;
            this.startY = 0;
            
            // Get settings from data attributes
            this.zoomMin = parseFloat(img.dataset.zoomMin) || 1;
            this.zoomMax = parseFloat(img.dataset.zoomMax) || 3;
            this.zoomStep = parseFloat(img.dataset.zoomStep) || 0.1;
            this.mouseWheelEnabled = img.dataset.mouseWheel !== 'false';
            this.dragEnabled = img.dataset.drag !== 'false';
            
            this.init();
        }
        
        init() {
            // Wait for image to load
            if (this.img.complete) {
                this.setupInitialPosition();
            } else {
                this.img.addEventListener('load', () => this.setupInitialPosition());
            }
            
            // Mouse wheel zoom
            if (this.mouseWheelEnabled) {
                this.container.addEventListener('wheel', (e) => this.handleWheel(e));
            }
            
            // Drag to pan
            if (this.dragEnabled) {
                this.container.addEventListener('mousedown', (e) => this.startDrag(e));
                document.addEventListener('mousemove', (e) => this.drag(e));
                document.addEventListener('mouseup', () => this.endDrag());
                
                // Touch support
                this.container.addEventListener('touchstart', (e) => this.startDrag(e));
                document.addEventListener('touchmove', (e) => this.drag(e));
                document.addEventListener('touchend', () => this.endDrag());
            }
            
            // Button controls
            const controls = this.container.querySelectorAll('.ipz-btn');
            controls.forEach(btn => {
                if (btn.dataset.target === this.img.id) {
                    btn.addEventListener('click', (e) => this.handleControlClick(e));
                }
            });
            
            // Prevent image dragging
            this.img.addEventListener('dragstart', (e) => e.preventDefault());
        }
        
        setupInitialPosition() {
            const containerRect = this.container.getBoundingClientRect();
            const imgRect = this.img.getBoundingClientRect();
            
            // Center the image
            this.posX = (containerRect.width - imgRect.width) / 2;
            this.posY = (containerRect.height - imgRect.height) / 2;
            
            this.updateTransform();
        }
        
        handleWheel(e) {
            e.preventDefault();
            
            const rect = this.container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const delta = e.deltaY > 0 ? -this.zoomStep : this.zoomStep;
            this.zoom(delta, x, y);
        }
        
        zoom(delta, centerX, centerY) {
            const oldScale = this.scale;
            this.scale = Math.max(this.zoomMin, Math.min(this.zoomMax, this.scale + delta));
            
            if (oldScale !== this.scale) {
                // Zoom towards cursor/center point
                const scaleDiff = this.scale - oldScale;
                this.posX -= (centerX - this.posX) * scaleDiff / oldScale;
                this.posY -= (centerY - this.posY) * scaleDiff / oldScale;
                
                this.constrainPosition();
                this.updateTransform();
                this.updateButtons();
            }
        }
        
        startDrag(e) {
            if (!this.dragEnabled) return;
            
            this.isDragging = true;
            this.container.classList.add('dragging');
            
            const point = this.getPoint(e);
            this.startX = point.x - this.posX;
            this.startY = point.y - this.posY;
            
            e.preventDefault();
        }
        
        drag(e) {
            if (!this.isDragging) return;
            
            const point = this.getPoint(e);
            this.posX = point.x - this.startX;
            this.posY = point.y - this.startY;
            
            this.constrainPosition();
            this.updateTransform();
            e.preventDefault();
        }
        
        constrainPosition() {
            const containerRect = this.container.getBoundingClientRect();
            const imgWidth = this.img.naturalWidth * this.scale;
            const imgHeight = this.img.naturalHeight * this.scale;
            
            // Calculate boundaries
            const maxX = 0;
            const minX = containerRect.width - imgWidth;
            const maxY = 0;
            const minY = containerRect.height - imgHeight;
            
            // Constrain horizontal
            if (imgWidth > containerRect.width) {
                this.posX = Math.max(minX, Math.min(maxX, this.posX));
            } else {
                this.posX = (containerRect.width - imgWidth) / 2;
            }
            
            // Constrain vertical
            if (imgHeight > containerRect.height) {
                this.posY = Math.max(minY, Math.min(maxY, this.posY));
            } else {
                this.posY = (containerRect.height - imgHeight) / 2;
            }
        }
        
        endDrag() {
            this.isDragging = false;
            this.container.classList.remove('dragging');
        }
        
        getPoint(e) {
            const rect = this.container.getBoundingClientRect();
            
            if (e.touches && e.touches.length > 0) {
                return {
                    x: e.touches[0].clientX - rect.left,
                    y: e.touches[0].clientY - rect.top
                };
            }
            
            return {
                x: e.clientX - rect.left,
                y: e.clientY - rect.top
            };
        }
        
        handleControlClick(e) {
            const btn = e.currentTarget;
            const rect = this.container.getBoundingClientRect();
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            if (btn.classList.contains('ipz-zoom-in')) {
                this.zoom(this.zoomStep, centerX, centerY);
            } else if (btn.classList.contains('ipz-zoom-out')) {
                this.zoom(-this.zoomStep, centerX, centerY);
            } else if (btn.classList.contains('ipz-reset')) {
                this.reset();
            }
        }
        
        reset() {
            this.scale = 1;
            this.setupInitialPosition();
            this.updateButtons();
        }
        
        updateTransform() {
            this.img.style.transform = `translate(${this.posX}px, ${this.posY}px) scale(${this.scale})`;
        }
        
        updateButtons() {
            const controls = this.container.querySelectorAll('.ipz-btn');
            controls.forEach(btn => {
                if (btn.dataset.target === this.img.id) {
                    if (btn.classList.contains('ipz-zoom-in')) {
                        btn.disabled = this.scale >= this.zoomMax;
                    } else if (btn.classList.contains('ipz-zoom-out')) {
                        btn.disabled = this.scale <= this.zoomMin;
                    }
                }
            });
        }
    }
    
    // Initialize all image pan zoom instances
    function initImagePanZoom() {
        const images = document.querySelectorAll('.image-pan-zoom-container img');
        images.forEach(img => {
            if (!img.dataset.ipzInitialized) {
                new ImagePanZoom(img);
                img.dataset.ipzInitialized = 'true';
            }
        });
    }
    
    // Init on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initImagePanZoom);
    } else {
        initImagePanZoom();
    }
    
    // Re-init for dynamically loaded content (Elementor preview)
    if (window.elementorFrontend && window.elementorFrontend.hooks) {
        window.elementorFrontend.hooks.addAction('frontend/element_ready/global', initImagePanZoom);
    }
})();
