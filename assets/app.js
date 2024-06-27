import './styles/app.scss';

import Chart from 'chart.js/auto'

(async function() {
    new Chart(
        document.getElementById('mental-health'),
        {
            type: 'line',
            options: {
                responsive: true,
                aspectRatio: 1,
                plugins: {
                    legend: {
                        display: false
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            display: true,
                            color: 'rgba(131,43,13,0.5)',
                            font: {
                                size: 16,
                                weight: 'bold',
                            },
                            padding: -40,
                            z: 5,
                            backdropPadding: 50
                        }
                    },
                    y: {
                        display: false,
                        suggestedMin: 0,
                        suggestedMax: 10
                    }
                }
            },
            data: {
                labels: chartsLabels,
                datasets: [
                    {
                        label: 'Score actuel',
                        data: currentMentalHealthDiagnostic,
                        cubicInterpolationMode: 'monotone',
                        tension: 0.4,
                        fill: false,
                        borderColor: 'rgb(69,141,142)',
                        borderWidth: 14
                    }, {
                        label: 'score',
                        data: patientMentalHealDiagnosticsValues,
                        cubicInterpolationMode: 'monotone',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(251,79,20,0.5)',
                        borderColor: 'rgba(251,79,20,1)',
                        borderWidth: 6

                    }
                ]
            }
        }
    );

    new Chart(
        document.getElementById('risk'),
        {
            type: 'line',
            options: {
                responsive: true,
                aspectRatio: 1,
                plugins: {
                    legend: {
                        display: false
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            display: true,
                            color: 'rgba(131,43,13,0.5)',
                            font: {
                                size: 16,
                                weight: 'bold',
                            },
                            padding: -40,
                            z: 5,
                            backdropPadding: 50
                        }
                    },
                    y: {
                        display: false,
                        suggestedMin: 0,
                        suggestedMax: 10
                    }
                }
            },
            data: {
                labels: chartsLabels,
                datasets: [
                    {
                        label: 'Score actuel',
                        data: currentRiskDiagnostic,
                        cubicInterpolationMode: 'monotone',
                        tension: 0.4,
                        fill: false,
                        borderColor: 'rgb(69,141,142)',
                        borderWidth: 14
                    }, {
                        label: 'score',
                        data: patientRiskDiagnosticsValues,
                        cubicInterpolationMode: 'monotone',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(251,79,20,0.5)',
                        borderColor: 'rgba(251,79,20,1)',
                        borderWidth: 6

                    }
                ]
            }
        }
    );
})();