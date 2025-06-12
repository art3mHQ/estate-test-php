jQuery(document).ready(function($) {
    'use strict';
    
    var filterForm = $('#real-estate-filter-form');
    var resultsContainer = $('#real-estate-results');
    var loadingElement = $('#filter-loading');
    var resultsCounter = $('#results-counter');
    
    // Initialize
    updateResultsCount();
    
    // Form submission handler
    filterForm.on('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
    
    // Reset button handler
    $('#reset-filter').on('click', function() {
        resetFilters();
    });
    
    // Auto-filter on input change (optional - with debounce)
    var filterTimeout;
    filterForm.find('input, select').on('change input', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(function() {
            // Uncomment the line below if you want auto-filtering
            // applyFilters();
        }, 500);
    });
    
    /**
     * Apply filters via AJAX
     */
    function applyFilters() {
        var formData = getFormData();
        
        // Show loading
        showLoading();
        
        // AJAX request
        $.ajax({
            url: real_estate_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'real_estate_filter',
                nonce: real_estate_ajax.nonce,
                filters: formData,
                per_page: getResultsPerPage()
            },
            success: function(response) {
                // console.log(typeof response);
                // console.log(response.success); // should be true
                // console.log(response.data.html);
                hideLoading();

                response = JSON.parse(response);
                
                if (response.success) {
                    resultsContainer.fadeOut(200, function() {
                        $(this).html(response.data.html).fadeIn(200);
                        updateResultsCount(response.data.count);
                        
                        // Trigger custom event for other scripts
                        $(document).trigger('realEstateFiltered', {
                            count: response.data.count,
                            filters: formData
                        });
                    });
                } else {
                    showError('Помилка при завантаженні результатів');
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                showError('Помилка мережі: ' + error);
            }
        });
    }
    
    /**
     * Reset all filters
     */
    function resetFilters() {
        filterForm[0].reset();
        
        // Reset custom elements if any
        filterForm.find('input[type="number"]').val('');
        filterForm.find('select').prop('selectedIndex', 0);
        
        // Apply filters to show all results
        applyFilters();
        
        // Trigger custom event
        $(document).trigger('realEstateFiltersReset');
    }
    
    /**
     * Get form data as object
     */
    function getFormData() {
        var data = {};
        
        // Get all form elements
        filterForm.find('input, select').each(function() {
            var $field = $(this);
            var name = $field.attr('name');
            var value = $field.val();
            
            if (name && value && value.trim() !== '') {
                data[name] = value.trim();
            }
        });
        
        return data;
    }
    
    /**
     * Get results per page setting
     */
    function getResultsPerPage() {
        // You can make this configurable
        return filterForm.data('per-page') || 5;
    }
    
    /**
     * Show loading indicator
     */
    function showLoading() {
        loadingElement.fadeIn(200);
        resultsContainer.css('opacity', '0.5');
    }
    
    /**
     * Hide loading indicator
     */
    function hideLoading() {
        loadingElement.fadeOut(200);
        resultsContainer.css('opacity', '1');
    }
    
    /**
     * Update results counter
     */
    function updateResultsCount(count) {
        if (typeof count === 'undefined') {
            // Count current visible results
            count = resultsContainer.find('.property-card').length;
        }
        
        var text;
        if (count === 0) {
            text = 'Результатів не знайдено';
        } else if (count === 1) {
            text = '1 результат';
        } else if (count < 5) {
            text = count + ' результати';
        } else {
            text = count + ' результатів';
        }
        
        resultsCounter.text(text);
    }
    
    /**
     * Show error message
     */
    function showError(message) {
        var errorHtml = '<div class="filter-error" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin: 10px 0;">' + 
                       '<strong>Помилка:</strong> ' + message + 
                       '</div>';
        
        // Remove existing errors
        $('.filter-error').remove();
        
        // Add new error
        filterForm.after(errorHtml);
        
        // Auto-remove after 5 seconds
        setTimeout(function() {
            $('.filter-error').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }
    
    /**
     * Validate form data
     */
    function validateForm() {
        var isValid = true;
        var errors = [];
        
        // Validate area range
        var areaMin = $('[name="room_area_min"]').val();
        var areaMax = $('[name="room_area_max"]').val();
        
        if (areaMin && areaMax) {
            if (parseFloat(areaMin) > parseFloat(areaMax)) {
                errors.push('Мінімальна площа не може бути більшою за максимальну');
                isValid = false;
            }
        }
        
        // Validate numeric inputs
        filterForm.find('input[type="number"]').each(function() {
            var $input = $(this);
            var value = $input.val();
            
            if (value && (isNaN(value) || parseFloat(value) < 0)) {
                errors.push('Некоректне значення в полі "' + $input.prev('label').text() + '"');
                isValid = false;
            }
        });
        
        if (!isValid) {
            showError(errors.join('<br>'));
        }
        
        return isValid;
    }
    
    /**
     * Enhanced form submission with validation
     */
    filterForm.on('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            applyFilters();
        }
    });
    
    /**
     * Keyboard shortcuts
     */
    $(document).on('keydown', function(e) {
        // Ctrl+Enter or Cmd+Enter to apply filters
        if ((e.ctrlKey || e.metaKey) && e.which === 13) {
            e.preventDefault();
            if (validateForm()) {
                applyFilters();
            }
        }
        
        // Escape to reset filters
        if (e.which === 27) {
            resetFilters();
        }
    });
    
    /**
     * Auto-save filters to sessionStorage (optional)
     */
    function saveFiltersToStorage() {
        if (typeof(Storage) !== "undefined") {
            var formData = getFormData();
            sessionStorage.setItem('realEstateFilters', JSON.stringify(formData));
        }
    }
    
    /**
     * Load filters from sessionStorage (optional)
     */
    function loadFiltersFromStorage() {
        if (typeof(Storage) !== "undefined") {
            var savedFilters = sessionStorage.getItem('realEstateFilters');
            if (savedFilters) {
                try {
                    var filters = JSON.parse(savedFilters);
                    
                    // Apply saved filters to form
                    $.each(filters, function(name, value) {
                        var $field = filterForm.find('[name="' + name + '"]');
                        if ($field.length) {
                            $field.val(value);
                        }
                    });
                    
                    // Optionally apply filters automatically
                    // applyFilters();
                } catch (e) {
                    console.log('Error loading saved filters:', e);
                }
            }
        }
    }
    
    /**
     * Add smooth animations to results
     */
    function animateResults() {
        resultsContainer.find('.property-card').each(function(index) {
            $(this).delay(index * 100).animate({
                opacity: 1,
                transform: 'translateY(0)'
            }, 300);
        });
    }
    
    /**
     * Mobile-friendly enhancements
     */
    function initMobileEnhancements() {
        // Collapse/expand filter on mobile
        if ($(window).width() < 768) {
            var $filterHeader = $('<div class="filter-toggle" style="cursor: pointer; padding: 10px; background: #0073aa; color: white; margin-bottom: 0; border-radius: 4px 4px 0 0;">').html('🔍 Показати фільтри');
            var $filterContent = filterForm.find('.filter-row, .filter-section, .filter-buttons').wrapAll('<div class="filter-content" style="display: none;">').parent();
            
            filterForm.prepend($filterHeader);
            
            $filterHeader.on('click', function() {
                $filterContent.slideToggle(300);
                var isVisible = $filterContent.is(':visible');
                $(this).html(isVisible ? '🔼 Сховати фільтри' : '🔍 Показати фільтри');
            });
        }
    }
    
    /**
     * URL hash management for shareable filters
     */
    function updateURLHash() {
        var formData = getFormData();
        if (Object.keys(formData).length > 0) {
            var hashString = btoa(JSON.stringify(formData));
            window.location.hash = 'filters=' + hashString;
        } else {
            history.replaceState("", document.title, window.location.pathname);
        }
    }
    
    function loadFromURLHash() {
        var hash = window.location.hash;
        if (hash.startsWith('#filters=')) {
            try {
                var hashData = hash.replace('#filters=', '');
                var filters = JSON.parse(atob(hashData));
                
                $.each(filters, function(name, value) {
                    var $field = filterForm.find('[name="' + name + '"]');
                    if ($field.length) {
                        $field.val(value);
                    }
                });
                
                applyFilters();
            } catch (e) {
                console.log('Error loading filters from URL:', e);
            }
        }
    }
    
    // Initialize mobile enhancements
    initMobileEnhancements();
    
    // Load filters from URL on page load
    loadFromURLHash();
    
    // Update URL when filters change (optional)
    filterForm.on('submit', function() {
        updateURLHash();
    });
    
    // Handle browser back/forward
    $(window).on('hashchange', function() {
        loadFromURLHash();
    });
    
    /**
     * Performance optimization: debounced resize handler
     */
    var resizeTimeout;
    $(window).on('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            // Reinitialize mobile enhancements if needed
            if ($(window).width() < 768) {
                initMobileEnhancements();
            }
        }, 250);
    });
    
    // Add loading class to body when filtering
    $(document).on('realEstateFiltering', function() {
        $('body').addClass('real-estate-filtering');
    });
    
    $(document).on('realEstateFiltered realEstateFiltersReset', function() {
        $('body').removeClass('real-estate-filtering');
    });
    
    // Trigger events for external integration
    filterForm.on('submit', function() {
        $(document).trigger('realEstateFiltering');
    });
});