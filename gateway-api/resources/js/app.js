import './bootstrap';

// Admin Dashboard Charts
import { initDashboardCharts } from './admin/charts';

// Initialize charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the admin dashboard page
    if (document.getElementById('dailySalesChart') ||
        document.getElementById('monthlySalesChart') ||
        document.getElementById('pgStatsChart') ||
        document.getElementById('paymentStatusChart')) {
        initDashboardCharts();
    }
});
