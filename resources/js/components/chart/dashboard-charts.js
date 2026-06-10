function isDarkMode() {
    return document.documentElement.classList.contains('dark');
}

function chartTheme() {
    const dark = isDarkMode();
    return {
        mode: dark ? 'dark' : 'light',
        labelColor: dark ? '#9ca3af' : '#6b7280',
        gridColor: dark ? '#374151' : '#e5e7eb',
        titleColor: dark ? '#e5e7eb' : '#374151',
    };
}

function formatAmount(value) {
    const num = Number(value) || 0;
    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
    if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
    return num.toLocaleString(undefined, { maximumFractionDigits: 0 });
}

function formatAmountFull(value) {
    return Number(value || 0).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function prepareChartEl(el) {
    if (!el) return false;
    el.innerHTML = '';
    return true;
}

function emptyState(el, message, sub = '') {
    if (!el) return;
    el.innerHTML = `
        <div class="flex h-full min-h-[260px] flex-col items-center justify-center px-4 text-center">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">${message}</p>
            ${sub ? `<p class="mt-1 text-xs text-gray-400">${sub}</p>` : ''}
        </div>`;
}

function baseOptions(height) {
    const theme = chartTheme();
    return {
        chart: {
            fontFamily: 'Inter, Outfit, sans-serif',
            height,
            width: '100%',
            toolbar: { show: false },
            zoom: { enabled: false },
            background: 'transparent',
            redrawOnParentResize: true,
        },
        grid: {
            borderColor: theme.gridColor,
            strokeDashArray: 4,
            padding: { top: 0, right: 10, bottom: 0, left: 10 },
            xaxis: { lines: { show: false } },
            yaxis: { lines: { show: true } },
        },
        xaxis: {
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: theme.labelColor, fontSize: '11px', fontWeight: 500 },
            },
        },
        yaxis: {
            labels: {
                style: { colors: theme.labelColor, fontSize: '11px' },
            },
        },
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '12px',
            fontWeight: 500,
            offsetY: -4,
            labels: { colors: theme.titleColor },
            markers: { radius: 12, width: 8, height: 8 },
        },
        tooltip: { theme: theme.mode },
        theme: { mode: theme.mode },
        dataLabels: { enabled: false },
    };
}

function initInvoiceAmountChart(el, chartData) {
    if (!prepareChartEl(el) || !chartData?.labels?.length) return null;

    const theme = chartTheme();
    const hasData = chartData.amounts.some((v) => v > 0);

    if (!hasData) {
        emptyState(el, 'No invoice amounts yet', 'Create invoices to see the trend');
        return null;
    }

    const chart = new ApexCharts(el, {
        ...baseOptions(280),
        chart: { ...baseOptions(280).chart, type: 'area' },
        colors: ['#465fff'],
        series: [{ name: 'Invoice Amount', data: chartData.amounts }],
        stroke: { curve: 'smooth', width: 2.5 },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
                stops: [0, 90, 100],
            },
        },
        markers: { size: 4, strokeWidth: 0, hover: { size: 6 } },
        xaxis: { ...baseOptions().xaxis, categories: chartData.labels },
        yaxis: {
            labels: {
                style: { colors: theme.labelColor },
                formatter: (val) => formatAmount(val),
            },
            min: 0,
        },
        tooltip: {
            theme: theme.mode,
            y: { formatter: (val) => formatAmountFull(val) },
        },
        legend: { show: false },
    });

    chart.render();
    return chart;
}

function initInvoiceCountChart(el, chartData) {
    if (!prepareChartEl(el) || !chartData?.labels?.length) return null;

    const theme = chartTheme();
    const hasData = chartData.counts.some((v) => v > 0);

    if (!hasData) {
        emptyState(el, 'No invoices yet');
        return null;
    }

    const chart = new ApexCharts(el, {
        ...baseOptions(260),
        chart: { ...baseOptions(260).chart, type: 'bar' },
        colors: ['#7592ff'],
        series: [{ name: 'Invoice Count', data: chartData.counts }],
        plotOptions: {
            bar: {
                borderRadius: 6,
                borderRadiusApplication: 'end',
                columnWidth: '50%',
            },
        },
        xaxis: { ...baseOptions().xaxis, categories: chartData.labels },
        yaxis: {
            labels: {
                style: { colors: theme.labelColor },
                formatter: (val) => Math.round(val),
            },
            min: 0,
            tickAmount: 4,
        },
        tooltip: {
            theme: theme.mode,
            y: { formatter: (val) => `${Math.round(val)} invoice(s)` },
        },
        legend: { show: false },
    });

    chart.render();
    return chart;
}

function initQuotationChart(el, quotationStats) {
    if (!prepareChartEl(el)) return null;

    const theme = chartTheme();
    const series = [
        quotationStats.success || 0,
        quotationStats.pending || 0,
        quotationStats.reject || 0,
    ];
    const labels = ['Success', 'On Progress', 'Reject'];
    const colors = ['#12b76a', '#f79009', '#f04438'];
    const total = series.reduce((a, b) => a + b, 0);

    if (total === 0) {
        emptyState(el, 'No quotations yet', 'Add quotations to see breakdown');
        return null;
    }

    const chart = new ApexCharts(el, {
        chart: {
            type: 'donut',
            height: 300,
            width: '100%',
            fontFamily: 'Inter, Outfit, sans-serif',
            background: 'transparent',
        },
        series,
        labels,
        colors,
        stroke: { width: 0 },
        dataLabels: { enabled: false },
        legend: {
            show: true,
            position: 'bottom',
            horizontalAlign: 'center',
            fontSize: '11px',
            fontWeight: 500,
            offsetY: 4,
            height: 60,
            labels: { colors: theme.titleColor },
            markers: { radius: 12, width: 8, height: 8 },
            formatter: (name, opts) => {
                const val = opts.w.globals.series[opts.seriesIndex];
                const pct = total > 0 ? Math.round((val / total) * 100) : 0;
                return `${name} (${pct}%)`;
            },
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '68%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '13px',
                            fontWeight: 600,
                            color: theme.titleColor,
                            offsetY: -8,
                        },
                        value: {
                            show: true,
                            fontSize: '24px',
                            fontWeight: 700,
                            color: theme.titleColor,
                            offsetY: 4,
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '12px',
                            color: theme.labelColor,
                            formatter: () => total,
                        },
                    },
                },
            },
        },
        tooltip: {
            theme: theme.mode,
            y: { formatter: (val) => `${val} quotation(s)` },
        },
        theme: { mode: theme.mode },
    });

    chart.render();
    return chart;
}

function initPaymentChart(el, paymentData) {
    if (!prepareChartEl(el) || !paymentData?.labels?.length) return null;

    const theme = chartTheme();
    const hasData = paymentData.amounts.some((v) => v > 0);

    if (!hasData) {
        emptyState(el, 'No payments yet', 'Record payments to see collections');
        return null;
    }

    const chart = new ApexCharts(el, {
        ...baseOptions(260),
        chart: { ...baseOptions(260).chart, type: 'bar' },
        colors: ['#12b76a'],
        series: [{ name: 'Payments Received', data: paymentData.amounts }],
        plotOptions: {
            bar: {
                borderRadius: 6,
                borderRadiusApplication: 'end',
                columnWidth: '50%',
            },
        },
        xaxis: { ...baseOptions().xaxis, categories: paymentData.labels },
        yaxis: {
            labels: {
                style: { colors: theme.labelColor },
                formatter: (val) => formatAmount(val),
            },
            min: 0,
            tickAmount: 4,
        },
        tooltip: {
            theme: theme.mode,
            y: { formatter: (val) => formatAmountFull(val) },
        },
        legend: { show: false },
    });

    chart.render();
    return chart;
}

function initPaymentStatusChart(el, stats) {
    if (!prepareChartEl(el)) return null;

    const theme = chartTheme();
    const pending = stats.pending_payment_invoices || 0;
    const cleared = stats.cleared_payment_invoices || 0;
    const total = pending + cleared;

    if (total === 0) {
        emptyState(el, 'No payment status data');
        return null;
    }

    const chart = new ApexCharts(el, {
        chart: {
            type: 'donut',
            height: 260,
            width: '100%',
            fontFamily: 'Inter, Outfit, sans-serif',
            background: 'transparent',
        },
        series: [pending, cleared],
        labels: ['Pending', 'Clear'],
        colors: ['#f79009', '#12b76a'],
        stroke: { width: 0 },
        dataLabels: { enabled: false },
        legend: {
            show: true,
            position: 'bottom',
            horizontalAlign: 'center',
            fontSize: '11px',
            offsetY: 0,
            height: 45,
            labels: { colors: theme.titleColor },
            markers: { radius: 12, width: 8, height: 8 },
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        name: { show: false },
                        value: {
                            show: true,
                            fontSize: '22px',
                            fontWeight: 700,
                            color: theme.titleColor,
                        },
                        total: {
                            show: true,
                            label: 'Invoices',
                            fontSize: '12px',
                            color: theme.labelColor,
                            formatter: () => total,
                        },
                    },
                },
            },
        },
        tooltip: {
            theme: theme.mode,
            y: { formatter: (val) => `${val} invoice(s)` },
        },
        theme: { mode: theme.mode },
    });

    chart.render();
    return chart;
}

let activeCharts = [];
let initialized = false;

function destroyActiveCharts() {
    activeCharts.forEach((chart) => {
        try {
            chart.destroy();
        } catch (e) {}
    });
    activeCharts = [];
}

export function initDashboardCharts() {
    const data = window.dashboardChartData;
    if (!data || !window.ApexCharts) return;

    destroyActiveCharts();

    const charts = [
        initInvoiceAmountChart(document.querySelector('#dashboard-invoice-amount-chart'), data.chart),
        initInvoiceCountChart(document.querySelector('#dashboard-invoice-count-chart'), data.chart),
        initQuotationChart(document.querySelector('#dashboard-quotation-chart'), data.stats.quotations),
        initPaymentChart(document.querySelector('#dashboard-payment-chart'), data.paymentChart),
        initPaymentStatusChart(document.querySelector('#dashboard-payment-status-chart'), data.stats),
    ].filter(Boolean);

    activeCharts = charts;
    initialized = true;

    return charts;
}

export default initDashboardCharts;
