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
        console.log('Navigation initialized');
    }

    /**
     * Initialize user management functionality
     */
    function initUserManagement() {
        // User management functionality can be added here
        console.log('User management initialized');
    }

    /**
     * Initialize custom blocks functionality
     */
    function initCustomBlocks() {
        // Initialize accordion functionality
        initAccordions();
        
        console.log('Custom blocks initialized');
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
            // Add click event listener
            trigger.addEventListener('click', handleAccordionClick);
            
            // Add keyboard event listener for accessibility
            trigger.addEventListener('keydown', handleAccordionKeydown);
        });
    }
    
    function handleAccordionClick(event) {
        event.preventDefault();
        
        const trigger = event.currentTarget;
        const accordionItem = trigger.closest('[data-accordion-item]');
        const content = accordionItem.querySelector('[data-accordion-content]');
        
        if (!content) return;
        
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
        if (!contentInner) return;
        
        // Get the actual height needed
        content.style.maxHeight = 'none';
        const actualHeight = content.scrollHeight;
        content.style.maxHeight = '0';
        
        // Force reflow
        content.offsetHeight;
        
        // Animate to the actual height
        content.style.maxHeight = actualHeight + 'px';
        
        // Set aria-hidden to false after a small delay for screen readers
        setTimeout(() => {
            content.setAttribute('aria-hidden', 'false');
        }, 50);
        
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
        
        // Force reflow
        content.offsetHeight;
        
        // Animate to 0
        content.style.maxHeight = '0';
        
        // Set aria-hidden after animation completes
        setTimeout(() => {
            content.setAttribute('aria-hidden', 'true');
        }, 300);
    }

    /**
     * Global function for opening modals
     */
    window.abfOpenModal = function(modalType) {
        console.log('Opening modal:', modalType);
        // Modal functionality can be implemented here
    };
    
    /**
     * Expose accordion reinit function globally for dynamic content
     */
    window.abfReinitAccordions = function() {
        initAccordions();
    };

})(); 