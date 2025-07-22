/**
 * ABF User Management Frontend JavaScript
 * Handles modal, forms, and user interactions
 */

jQuery(document).ready(function($) {
    
    // Global variables
    const modal = $('#abf-auth-modal');
    const userBar = $('#abf-user-bar');
    const messageDiv = $('#abf-modal-message');
    
    // Initialize
    init();
    
    function init() {
        setupEventListeners();
        checkUserStatus();
        
        // Show modal if URL parameter is present
        if (abf_ajax.show_modal) {
            showModal();
        }
    }
    
    function setupEventListeners() {
        // Modal controls
        $('.abf-modal-close').on('click', hideModal);
        modal.on('click', function(e) {
            if (e.target === this) hideModal();
        });
        
        // Tab switching
        $('.abf-tab-btn').on('click', function() {
            const tab = $(this).data('tab');
            switchTab(tab);
        });
        
        // Email field for dynamic form switching
        $('#register_email').on('input', function() {
            checkEmailType($(this).val());
        });
        
        // Form submissions
        $('#abf-login-form-element').on('submit', handleLogin);
        $('#abf-register-form-element').on('submit', handleRegistration);
        $('#abf-reset-form').on('submit', handlePasswordReset);
        
        // Password reset toggle
        $('#show-password-reset').on('click', function(e) {
            e.preventDefault();
            showPasswordResetForm();
        });
        
        $('#show-login-form').on('click', function(e) {
            e.preventDefault();
            showLoginForm();
        });
        
        // Logout
        $('#abf-logout-btn').on('click', handleLogout);
        
        // Login button trigger (you can add this to your homepage)
        $(document).on('click', '.abf-login-trigger', function(e) {
            e.preventDefault();
            showModal();
        });
    }
    
    function showModal() {
        modal.fadeIn(300);
        hideMessage();
    }
    
    function hideModal() {
        modal.fadeOut(300);
        resetForms();
        hideMessage();
    }
    
    function switchTab(tab) {
        $('.abf-tab-btn').removeClass('active');
        $('.abf-tab-btn[data-tab="' + tab + '"]').addClass('active');
        
        $('.abf-tab-content').removeClass('active');
        $('#abf-' + tab + '-form').addClass('active');
        
        // Update modal title
        if (tab === 'login') {
            $('#abf-modal-title').text('Anmelden');
        } else {
            $('#abf-modal-title').text('Registrieren');
        }
        
        hideMessage();
    }
    
    function checkEmailType(email) {
        const isInternal = email.includes('@a-b-f.de');
        const externalFields = $('.abf-external-only');
        
        if (isInternal) {
            externalFields.hide();
            externalFields.find('input, textarea').removeAttr('required');
        } else {
            externalFields.show();
            externalFields.find('input, textarea').attr('required', 'required');
        }
    }
    
    function handleLogin(e) {
        e.preventDefault();
        
        const formData = {
            action: 'abf_login_user',
            nonce: abf_ajax.login_nonce,
            email: $('#login_email').val(),
            password: $('#login_password').val(),
            remember: $('#abf-login-form-element input[name="remember"]').is(':checked')
        };
        
        showLoading('Anmeldung läuft...');
        
        $.post(abf_ajax.ajax_url, formData)
            .done(function(response) {
                const data = JSON.parse(response);
                
                if (data.success) {
                    showMessage(data.message, 'success');
                    setTimeout(function() {
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            location.reload();
                        }
                    }, 1500);
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .fail(function() {
                showMessage('Verbindungsfehler. Bitte versuchen Sie es erneut.', 'error');
            });
    }
    
    function handleRegistration(e) {
        e.preventDefault();
        
        const formData = {
            action: 'abf_register_user',
            nonce: abf_ajax.registration_nonce,
            name: $('#register_name').val(),
            company: $('#register_company').val(),
            email: $('#register_email').val(),
            phone: $('#register_phone').val(),
            reason: $('#register_reason').val(),
            password: $('#register_password').val()
        };
        
        showLoading('Registrierung läuft...');
        
        $.post(abf_ajax.ajax_url, formData)
            .done(function(response) {
                const data = JSON.parse(response);
                
                if (data.success) {
                    showMessage(data.message, 'success');
                    
                    if (data.auto_login) {
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        // Switch to login tab after successful registration
                        setTimeout(function() {
                            switchTab('login');
                            resetForms();
                        }, 3000);
                    }
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .fail(function() {
                showMessage('Verbindungsfehler. Bitte versuchen Sie es erneut.', 'error');
            });
    }
    
    function handlePasswordReset(e) {
        e.preventDefault();
        
        const formData = {
            action: 'abf_reset_password',
            nonce: abf_ajax.reset_nonce,
            email: $('#reset_email').val()
        };
        
        showLoading('Link wird gesendet...');
        
        $.post(abf_ajax.ajax_url, formData)
            .done(function(response) {
                const data = JSON.parse(response);
                
                if (data.success) {
                    showMessage(data.message, 'success');
                    setTimeout(function() {
                        showLoginForm();
                    }, 3000);
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .fail(function() {
                showMessage('Verbindungsfehler. Bitte versuchen Sie es erneut.', 'error');
            });
    }
    
    function handleLogout(e) {
        e.preventDefault();
        
        if (!confirm('Möchten Sie sich wirklich abmelden?')) {
            return;
        }
        
        const formData = {
            action: 'abf_logout_user'
        };
        
        $.post(abf_ajax.ajax_url, formData)
            .done(function(response) {
                const data = JSON.parse(response);
                
                if (data.success) {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        location.reload();
                    }
                } else {
                    // Silent error handling - redirect to homepage
                    window.location.href = window.location.origin;
                }
            })
            .fail(function() {
                                  // Silent error handling - redirect to homepage
                  window.location.href = window.location.origin;
            });
    }
    
    function showPasswordResetForm() {
        $('#abf-login-form-element').hide();
        $('#abf-reset-form').show();
        $('#abf-modal-title').text('Passwort zurücksetzen');
    }
    
    function showLoginForm() {
        $('#abf-reset-form').hide();
        $('#abf-login-form-element').show();
        $('#abf-modal-title').text('Anmelden');
    }
    
    function showMessage(message, type) {
        messageDiv.removeClass('success error').addClass(type);
        messageDiv.html(message).fadeIn(300);
    }
    
    function hideMessage() {
        messageDiv.fadeOut(300);
    }
    
    function showLoading(message) {
        showMessage(message + ' <span style="animation: spin 1s linear infinite; display: inline-block;">⟳</span>', 'success');
    }
    
    function resetForms() {
        $('#abf-login-form-element')[0].reset();
        $('#abf-register-form-element')[0].reset();
        $('#abf-reset-form')[0].reset();
        
        // Reset external fields visibility
        $('.abf-external-only').hide();
        $('.abf-external-only input, .abf-external-only textarea').removeAttr('required');
        
        // Reset to login form
        showLoginForm();
    }
    
    function checkUserStatus() {
        if (abf_ajax.user_info) {
            const user = abf_ajax.user_info;
            
            if (user.can_access) {
                // Show user bar
                $('#abf-user-name').text('Willkommen, ' + user.name);
                userBar.show();
            } else {
                // User is logged in but not approved
                let statusMessage = '';
                switch (user.approval_status) {
                    case 'pending':
                        statusMessage = 'Ihr Konto wartet auf Freigabe.';
                        break;
                    case 'rejected':
                        statusMessage = 'Ihr Konto wurde nicht genehmigt.';
                        break;
                    default:
                        statusMessage = 'Ihr Konto ist nicht aktiviert.';
                }
                
                $('#abf-user-name').text(user.name + ' - ' + statusMessage);
                userBar.show();
            }
        }
    }
    
    // Add spinning animation CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
    
    // Expose function to global scope for external use
    window.ABF_UserManagement = {
        showModal: showModal,
        hideModal: hideModal,
        switchTab: switchTab
    };
    
}); 