/**
 * EMOTIMATE — shared UI behaviors
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initLoadingButtons();
        initSidebarToggle();
        initFlashAutoDismiss();
    });

    function initLoadingButtons() {
        document.querySelectorAll('form[data-loading-form]').forEach(function (form) {
            form.addEventListener('submit', function () {
                var btn = form.querySelector('[type="submit"]');
                if (!btn || btn.disabled) {
                    return;
                }
                btn.disabled = true;
                btn.dataset.originalHtml = btn.innerHTML;
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
                    (btn.dataset.loadingText || 'Please wait...');
            });
        });

        document.querySelectorAll('.btn[data-loading]').forEach(function (btn) {
            btn.closest('form')?.setAttribute('data-loading-form', 'true');
            if (!btn.dataset.loadingText) {
                btn.dataset.loadingText = 'Please wait...';
            }
        });
    }

    function initSidebarToggle() {
        var sidebar = document.getElementById('adminSidebar');
        var sidebarToggle = document.getElementById('adminSidebarToggle');

        if (sidebar && sidebarToggle) {
            sidebar.addEventListener('shown.bs.collapse', function () {
                sidebarToggle.setAttribute('aria-expanded', 'true');
            });
            sidebar.addEventListener('hidden.bs.collapse', function () {
                sidebarToggle.setAttribute('aria-expanded', 'false');
            });
        }
    }

    function initFlashAutoDismiss() {
        document.querySelectorAll('.alert-flash[data-auto-dismiss]').forEach(function (alert) {
            setTimeout(function () {
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                }
            }, 6000);
        });
    }

    window.EmotimateCharts = {
        defaults: function () {
            return {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#2f3e36',
                            font: { family: "'Plus Jakarta Sans', sans-serif" }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#5f7268' },
                        grid: { color: 'rgba(107, 171, 138, 0.15)' }
                    },
                    y: {
                        ticks: { color: '#5f7268' },
                        grid: { color: 'rgba(107, 171, 138, 0.15)' }
                    }
                }
            };
        },
        greenPalette: ['#4a8f6f', '#6bab8a', '#b8d9c8', '#3d7359', '#8fc4a8']
    };
})();
