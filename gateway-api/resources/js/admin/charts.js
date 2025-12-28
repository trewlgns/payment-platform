/**
 * Admin Dashboard Charts
 * Chart.js implementation for payment platform statistics
 */

import Chart from 'chart.js/auto';

/**
 * Initialize Daily Sales Chart
 */
export function initDailySalesChart() {
    const canvas = document.getElementById('dailySalesChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    // Sample data - 최근 7일 매출
    const labels = ['월', '화', '수', '목', '금', '토', '일'];
    const data = [420000, 580000, 650000, 720000, 890000, 1200000, 950000];

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: '일별 매출 (₩)',
                data: data,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#007bff',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 12,
                            family: "'Segoe UI', Roboto, sans-serif"
                        },
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₩' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₩' + (value / 1000) + 'K';
                        },
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
}

/**
 * Initialize Monthly Sales Chart
 */
export function initMonthlySalesChart() {
    const canvas = document.getElementById('monthlySalesChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    // Sample data - 최근 12개월 매출
    const labels = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];
    const data = [3200000, 3500000, 4100000, 3800000, 4500000, 5200000, 4800000, 5500000, 6200000, 5800000, 6500000, 7200000];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: '월별 매출 (₩)',
                data: data,
                backgroundColor: 'rgba(0, 123, 255, 0.8)',
                borderColor: '#007bff',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 12,
                            family: "'Segoe UI', Roboto, sans-serif"
                        },
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₩' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₩' + (value / 1000000) + 'M';
                        },
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
}

/**
 * Initialize PG Statistics Chart (Doughnut)
 */
export function initPgStatsChart() {
    const canvas = document.getElementById('pgStatsChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    // Sample data - PG별 거래 비율
    const labels = ['토스페이먼츠', 'Mock PG', '기타'];
    const data = [65, 30, 5];
    const colors = ['#007bff', '#28a745', '#6c757d'];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 12,
                            family: "'Segoe UI', Roboto, sans-serif"
                        },
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                }
            }
        }
    });
}

/**
 * Initialize Payment Status Chart (Pie)
 */
export function initPaymentStatusChart() {
    const canvas = document.getElementById('paymentStatusChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    // Sample data - 결제 상태별 분포
    const labels = ['승인', '대기', '실패', '취소'];
    const data = [820, 100, 42, 38];
    const colors = ['#28a745', '#ffc107', '#dc3545', '#6c757d'];

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 12,
                            family: "'Segoe UI', Roboto, sans-serif"
                        },
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + '건 (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}

/**
 * Initialize all charts on dashboard
 */
export function initDashboardCharts() {
    initDailySalesChart();
    initMonthlySalesChart();
    initPgStatsChart();
    initPaymentStatusChart();
}
