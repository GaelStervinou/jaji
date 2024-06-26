import './styles/app.scss';

import Chart from 'chart.js/auto'

(async function() {
    const data = [
        { year: 2010, count: 10 },
        { year: 2011, count: 20 },
        { year: 2012, count: 15 },
        { year: 2013, count: 25 },
        { year: 2014, count: 22 },
        { year: 2015, count: 22 },
        { year: 2016, count: 28 },
    ];

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
                        suggestedMax: 55
                    }
                }
            },
            data: {
                labels: data.map(row => row.year),
                datasets: [
                    {
                        label: 'Score actuel',
                        data: [, , , , , 22, ],
                        cubicInterpolationMode: 'monotone',
                        tension: 0.4,
                        fill: false,
                        borderColor: 'rgb(69,141,142)',
                        borderWidth: 14
                    }, {
                        label: 'score',
                        data: data.map(row => row.count),
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


    const dataRisk = [
        { year: 2010, count: 30 },
        { year: 2011, count: 12 },
        { year: 2012, count: 15 },
        { year: 2013, count: 20 },
        { year: 2014, count: 22 },
        { year: 2015, count: 30 },
        { year: 2016, count: 28 },
    ];

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
                        suggestedMax: 50
                    }
                }
            },
            data: {
                labels: dataRisk.map(row => row.year),
                datasets: [
                    {
                        label: 'Score actuel',
                        data: [, , , , , 30, ],
                        cubicInterpolationMode: 'monotone',
                        tension: 0.4,
                        fill: false,
                        borderColor: 'rgb(69,141,142)',
                        borderWidth: 14
                    }, {
                        label: 'score',
                        data: dataRisk.map(row => row.count),
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