/**
 * Main JavaScript File for ABF Styleguide Theme
 */

(function() {
    'use strict';

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        
        // Initialize theme functionality
        initTheme();
        
    });

    /**
     * Initialize theme functionality
     */
    function initTheme() {
        
        // Initialize navigation
        initNavigation();
        
        // Initialize user management
        initUserManagement();
        
        // Initialize custom blocks
        initCustomBlocks();
        
    }

    /**
     * Initialize navigation functionality
     */
    function initNavigation() {
        // Navigation functionality can be added here
    }

    /**
     * Initialize user management functionality
     */
    function initUserManagement() {
        // User management functionality can be added here
    }

    /**
     * Initialize custom blocks functionality
     */
    function initCustomBlocks() {
        // Initialize accordion functionality
        initAccordions();
        
    }
    
    /**
     * Initialize Accordion Functionality
     */
    function initAccordions() {
        // Find all accordion containers
        const accordionContainers = document.querySelectorAll('.block-styleguide-akkordeon');
        
        
        accordionContainers.forEach(container => {
            initAccordionContainer(container);
        });
    }
    
    function initAccordionContainer(container) {
        // Find all accordion triggers within this container
        const triggers = container.querySelectorAll('[data-accordion-trigger]');
        
        
        triggers.forEach(trigger => {
            // Ensure initial state is set correctly
            if (!trigger.hasAttribute('aria-expanded')) {
                trigger.setAttribute('aria-expanded', 'false');
            }
            
            // Find corresponding content
            const accordionItem = trigger.closest('[data-accordion-item]');
            const content = accordionItem.querySelector('[data-accordion-content]');
            
            if (content) {
                // Ensure content is initially hidden
                content.setAttribute('aria-hidden', 'true');
                content.style.maxHeight = '0';
                content.style.padding = '0';
                
                // Add event listeners
                trigger.addEventListener('click', handleAccordionClick);
                trigger.addEventListener('keydown', handleAccordionKeydown);
                
            }
        });
    }
    
    function handleAccordionClick(event) {
        event.preventDefault();
        
        
        const trigger = event.currentTarget;
        const accordionItem = trigger.closest('[data-accordion-item]');
        const content = accordionItem.querySelector('[data-accordion-content]');
        
        if (!content) {
            return;
        }
        
        const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
        
        
        // Toggle the accordion state
        toggleAccordion(trigger, content, !isExpanded);
    }
    
    function handleAccordionKeydown(event) {
        // Handle Enter and Space keys for accessibility
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            handleAccordionClick(event);
        }
    }
    
    function toggleAccordion(trigger, content, shouldExpand) {
        
        // Update ARIA attributes
        trigger.setAttribute('aria-expanded', shouldExpand);
        content.setAttribute('aria-hidden', !shouldExpand);
        
        if (shouldExpand) {
            openAccordion(content);
        } else {
            closeAccordion(content);
        }
    }
    
    function openAccordion(content) {
        
        // Set max-height to auto temporarily to measure actual height
        const contentInner = content.querySelector('.styleguide-akkordeon-item-content-inner');
        if (!contentInner) {
            return;
        }
        
        // Reset any previous inline styles
        content.style.maxHeight = 'none';
        content.style.padding = '0';
        
        // Get the actual height needed
        const actualHeight = content.scrollHeight;
        
        // Set back to 0 for animation
        content.style.maxHeight = '0';
        
        // Force reflow
        content.offsetHeight;
        
        // Animate to the actual height
        content.style.maxHeight = actualHeight + 'px';
        content.style.paddingBottom = '16px';
        
        // Set aria-hidden to false
        content.setAttribute('aria-hidden', 'false');
        
        
        // Clean up max-height after animation completes
        setTimeout(() => {
            if (content.getAttribute('aria-hidden') === 'false') {
                content.style.maxHeight = '1000px'; // Fallback to CSS value
            }
        }, 300);
    }
    
    function closeAccordion(content) {
        
        // Get current height
        const currentHeight = content.scrollHeight;
        content.style.maxHeight = currentHeight + 'px';
        content.style.paddingBottom = '16px';
        
        // Force reflow
        content.offsetHeight;
        
        // Animate to 0
        content.style.maxHeight = '0';
        content.style.paddingBottom = '0';
        
        // Set aria-hidden after animation starts
        content.setAttribute('aria-hidden', 'true');
        
    }

    /**
     * Global function for opening modals
     */
    window.abfOpenModal = function(modalType) {
        // Modal functionality can be implemented here
    };
    
    /**
     * Expose accordion reinit function globally for dynamic content
     */
    window.abfReinitAccordions = function() {
        initAccordions();
    };

})(); 