$(document).ready(function() {
    $('#myStat').circliful({
        animationStep: 12,
        percent: 100,
        foregroundColor: '#1bab9e',
        text: total_cost,
        noPercentageSign: true,
    });
});
