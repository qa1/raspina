var config = {
    type: 'line',
    data : {
    labels: chart_labels,
    datasets: [{
        label: visit_labels,
        data: visit_data,
        fill: false,
        borderColor: 'rgba(153,187,255,0.5)',
        backgroundColor: 'rgba(153,187,255,0.5)',
        pointBorderColor: 'rgba(102,153,255,0.8)',
        pointBackgroundColor: 'rgba(102,153,255,1)',
        pointBorderWidth: 3
    }, {
        label: visitor_labels,
        fill: false,
        data: visitor_data,
        borderColor: 'rgba(255,153,51,0.5)',
        backgroundColor: 'rgba(255,153,51,0.5)',
        pointBorderColor: 'rgba(255,140,26,0.8)',
        pointBackgroundColor: 'rgba(255,140,26,1)',
        pointBorderWidth: 3,
    }]
},
    options: {
        responsive: true,
        title:{
            display:true,
            text:''
        },
        tooltips: {
            mode: 'label'
        },
        hover: {
            mode: 'dataset'
        },
        scales: {
            xAxes: [{
                display: true,
                scaleLabel: {
                    show: true,
                    labelString: 'Month'
                }
            }],
            yAxes: [{
                display: true,
                scaleLabel: {
                    show: true,
                    labelString: 'Value'
                },
                ticks: {
                    suggestedMin: 0,
                    suggestedMax: chart_max_visit + (chart_max_visit / 5),
                }
            }]
        }
    }
};

window.onload = function() {
    var ctx = document.getElementById("canvas").getContext("2d");
    window.myLine = new Chart(ctx, config);
};
