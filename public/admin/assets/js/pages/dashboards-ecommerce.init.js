/*
Template Name: Tailwick - Admin & Dashboard Template
Author: Themesdesign
Version: 1.1.0
Website: https://themesdesign.in/
Contact: Themesdesign@gmail.com
File: dashboard ecommerce init Js File (customised for dynamic data)
*/

(function () {
    "use strict";

    /**
     * Convert an RGB color string to HEX.
     */
    function rgbToHex(rgb) {
        if (!rgb) {
            return "#000000";
        }

        const rgbValues = rgb.match(/\d+/g);
        if (!rgbValues || rgbValues.length < 3) {
            return "#000000";
        }

        const [r, g, b] = rgbValues.map((value) =>
            Math.max(0, Math.min(255, Number(value) || 0))
        );

        const toHex = (value) => value.toString(16).padStart(2, "0");

        return `#${toHex(r)}${toHex(g)}${toHex(b)}`.toUpperCase();
    }

    /**
     * Resolve chart colour tokens (Tailwind classes or HEX values) to HEX.
     */
    function getChartColorsArray(chartId) {
        const chartElement = document.getElementById(chartId);
        if (!chartElement) {
            return null;
        }

        const colors = chartElement.dataset.chartColors;
        if (!colors) {
            console.warn(`chart-colors attribute not found on: ${chartId}`);
            return null;
        }

        try {
            const parsedColors = JSON.parse(colors);

            return parsedColors.map((colorToken) => {
                const token = colorToken.replace(/\s/g, "");

                if (token.startsWith("#")) {
                    return token;
                }

                const existingElement = document.querySelector(token);
                if (existingElement) {
                    const computed = window.getComputedStyle(existingElement);
                    return computed.backgroundColor.includes("#")
                        ? computed.backgroundColor
                        : rgbToHex(computed.backgroundColor);
                }

                const probe = document.createElement("div");
                probe.className = token;
                document.body.appendChild(probe);
                const computed = window.getComputedStyle(probe);
                const color = computed.backgroundColor.includes("#")
                    ? computed.backgroundColor
                    : rgbToHex(computed.backgroundColor);
                document.body.removeChild(probe);

                return color;
            });
        } catch (error) {
            console.error(`Unable to parse chart colors for ${chartId}`, error);
            return null;
        }
    }

    function parseJSON(value, fallback) {
        if (!value) {
            return fallback;
        }

        try {
            return JSON.parse(value);
        } catch (error) {
            console.warn("Failed to parse JSON value", value, error);
            return fallback;
        }
    }

    function toNumberArray(values) {
        if (!Array.isArray(values)) {
            return [];
        }

        return values.map((value) => {
            const numeric = Number(value);
            return Number.isFinite(numeric) ? numeric : 0;
        });
    }

    function formatNumber(value, fractionDigits) {
        return new Intl.NumberFormat(undefined, {
            minimumFractionDigits: fractionDigits,
            maximumFractionDigits: fractionDigits,
        }).format(value);
    }

    function formatCurrency(value) {
        const isInteger = Number.isInteger(value);
        const digits = isInteger ? 0 : 2;
        return `$${formatNumber(value, digits)}`;
    }

    function formatAxisValue(value) {
        const absolute = Math.abs(value);

        if (absolute >= 1_000_000_000) {
            return `${(value / 1_000_000_000).toFixed(1)}B`;
        }

        if (absolute >= 1_000_000) {
            return `${(value / 1_000_000).toFixed(1)}M`;
        }

        if (absolute >= 1_000) {
            return `${(value / 1_000).toFixed(1)}k`;
        }

        return formatNumber(value, 0);
    }

    function renderNoData(element) {
        if (!element) {
            return;
        }

        element.innerHTML =
            '<div class="py-6 text-center text-slate-500 dark:text-zink-200">No data available</div>';
        element.classList.remove("apex-charts");
    }

    function renderChart(element, options) {
        if (!element || typeof ApexCharts === "undefined") {
            return null;
        }

        const chart = new ApexCharts(element, options);
        chart.render();
        return chart;
    }

    document.addEventListener("DOMContentLoaded", function () {
        if (typeof ApexCharts === "undefined") {
            console.warn("ApexCharts library is not loaded.");
            return;
        }

        // Order Statistics (Delivered vs Processing)
        (function initialiseOrderStatisticsChart() {
            const element = document.querySelector("#orderStatisticsChart");
            if (!element) {
                return;
            }

            const colors = getChartColorsArray("orderStatisticsChart") || [
                "#0ea5e9",
                "#8b5cf6",
            ];
            const delivered = Number(element.dataset.delivered || 0);
            const processing = Number(element.dataset.processing || 0);
            const values = [delivered, processing];

            if (values.every((value) => value === 0)) {
                renderNoData(element);
                return;
            }

            const options = {
                series: [
                    {
                        name: "Orders",
                        data: values,
                    },
                ],
                chart: {
                    type: "bar",
                    height: 320,
                    toolbar: {
                        show: false,
                    },
                },
                plotOptions: {
                    bar: {
                        columnWidth: "45%",
                        borderRadius: 6,
                        distributed: true,
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                colors: colors,
                xaxis: {
                    categories: ["Delivered", "Processing"],
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    labels: {
                        style: {
                            fontSize: "13px",
                        },
                    },
                },
                yaxis: {
                    labels: {
                        formatter: (value) => formatNumber(value, 0),
                    },
                },
                grid: {
                    strokeDashArray: 4,
                },
                tooltip: {
                    y: {
                        formatter: (value) => `${formatNumber(value, 0)} orders`,
                    },
                },
                legend: {
                    show: false,
                },
            };

            renderChart(element, options);
        })();




        // Order Status Distribution (Donut)
        (function initialiseOrderStatusChart() {
            const element = document.querySelector("#orderStatusChart");
            if (!element) {
                return;
            }

            const labels = parseJSON(element.dataset.labels, []);
            const series = toNumberArray(parseJSON(element.dataset.series, []));

            if (!labels.length || !series.length || series.every((value) => value === 0)) {
                renderNoData(element);
                return;
            }

            const colors = getChartColorsArray("orderStatusChart") || [
                "#0ea5e9",
                "#f97316",
                "#22c55e",
                "#eab308",
                "#ef4444",
            ];

            const options = {
                chart: {
                    type: "donut",
                    height: 320,
                },
                labels: labels,
                series: series,
                colors: colors,
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    width: 0,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: "70%",
                        },
                    },
                },
                legend: {
                    position: "bottom",
                    horizontalAlign: "center",
                },
                tooltip: {
                    y: {
                        formatter: (value) => formatNumber(value, 0),
                    },
                },
            };

            renderChart(element, options);
        })();
    });
})();
