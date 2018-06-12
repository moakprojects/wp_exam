var apiKey = 'AIzaSyB4FTmZcK_2H9kh3qm5fErvtGf90xvoFMQ';

var testUrl = window.location.protocol + '//' + window.location.hostname;

var apiUrl = 'https://www.googleapis.com/pagespeedonline/v4/runPagespeed?';

// here we call google API what will provide analyze about the page
var startTime = Date.now();
jQuery.get(apiUrl, {url: testUrl, key: apiKey, snapshots: true, screenshot: true}, function(returnData) {
    var elapsedTime = ((Date.now() - startTime) / 1000).toFixed(2);

    console.log(returnData);

    if(returnData.responseCode >= 400) {
        jQuery('.tryAgainContainer').removeClass('hide');
    } else {

        /* content breakdown chart */

        if(typeof returnData.pageStats.cssResponseBytes !== 'undefined') {
            var cssResponse = parseInt(returnData.pageStats.cssResponseBytes);
        } else {
            var cssResponse = 0;
        }

        if(typeof returnData.pageStats.htmlResponseBytes !== 'undefined') {
            var htmlResponse = parseInt(returnData.pageStats.htmlResponseBytes);
        } else {
            var htmlResponse = 0;
        }

        if(typeof returnData.pageStats.imageResponseBytes !== 'undefined') {
            var imageResponse = parseInt(returnData.pageStats.imageResponseBytes);
        } else {
            var imageResponse = 0;
        }

        if(typeof returnData.pageStats.javascriptResponseBytes !== 'undefined') {
            var javascriptResponse = parseInt(returnData.pageStats.javascriptResponseBytes);
        } else {
            var javascriptResponse = 0;
        }

        if(typeof returnData.pageStats.otherResponseBytes !== 'undefined') {
            var otherResponse = parseInt(returnData.pageStats.otherResponseBytes);
        } else {
            var otherResponse = 0;
        }
        
        var totalResponse = (htmlResponse + cssResponse + javascriptResponse + imageResponse + otherResponse) / 1000;
        totalResponse = Math.round( totalResponse * 10 ) / 10;

        /* pagespeed insight */
    
        var ruleResultData = returnData.formattedResults.ruleResults;

        var pageSpeed = returnData.ruleGroups.SPEED.score;
        var screenshot = returnData.screenshot.data;
        var ruleResults = [];
        var ruleImpactSummary = 0;

        for(var key in ruleResultData) 
        {
            var item = ruleResultData[key];
            ruleImpactSummary += item.ruleImpact;
        }
        
        var i = 0;
        for (var key in ruleResultData) {
            var item = ruleResultData[key];
            var summaryArgs = [];
            var summaryType = null;

            if(typeof item.summary !== 'undefined') {
                if(item.summary.format.indexOf("{{") > -1) {
                    var j = 0;
                    for(var key2 in item.summary.args) {
    
                        summaryType = item.summary.args[key2].type; 
                        summaryArgs[j] = [
                            item.summary.args[key2].key,
                            item.summary.args[key2].value
                        ];
                        j++;
                    }
                }

            }

            var ruleImpactPercent = Math.round((1 - (item.ruleImpact / ruleImpactSummary)) * 100);

            if(typeof item.summary !== 'undefined') {
                ruleResults[i] = [
                    item.localizedRuleName,
                    ruleImpactPercent,
                    item.summary.format,
                    summaryType,
                    summaryArgs
                ];
            } else {
                ruleResults[i] = [
                    item.localizedRuleName,
                    ruleImpactPercent,
                    summaryType,
                    summaryArgs
                ];
            }
            i++;
        }
        
        jQuery(document).ready(function() {
            var data = {
                'action': 'sop',
                'ruleResults': ruleResults,
                'pageSpeed': pageSpeed,
                'elapsedTime': elapsedTime,
                'screenshot': screenshot
            };

            if(pageSpeed >= 80) {
                jQuery("#wp-admin-bar-sop-bar").find('a').eq(0).html("<span class='ab-icon star'></span> <span class='goodTitle'>Your page quality is Good</span>");
            } else if(pageSpeed >= 60) {
                jQuery("#wp-admin-bar-sop-bar").find('a').eq(0).html("<span class='ab-icon halfStar'></span> <span class='mediumTitle'>Your page quality is Medium</span>");
            } else {
                jQuery("#wp-admin-bar-sop-bar").find('a').eq(0).html("<span class='ab-icon emptyStar'></span> <span class='lowTitle'>Your page quality is Low</span>");
            }

            // We send data to the php side with Ajax 
            jQuery('.sopContainer').load(sop_ajax.ajax_url + ' .sopContainer', data, function() {
                jQuery('.start-screen').addClass("hide");
                jQuery('.collapsible').collapsible();
                jQuery('.sopContainer').removeClass("hide");

                buildChart(cssResponse, htmlResponse, javascriptResponse, imageResponse, otherResponse, totalResponse);
            });

            var widgetData = {
                'action' : 'sop_widget',
                'pageSpeed' : pageSpeed,
                'ruleResults' : ruleResults
            }

            jQuery('.widgetContainer').load(sop_ajax.ajax_url + ' .widgetContainer', widgetData, "");
        });
    }
}).fail(function(dataFail) {
    jQuery('.collapsible').collapsible();
    if(dataFail.statusCode >= 400) {
        jQuery('.tryAgainContainer').removeClass('hide');
    }
});

function buildChart(cssResponse, htmlResponse, javascriptResponse, imageResponse, otherResponse, totalResponse) {
    
    var pieColors = (function () {
        var colors = [],
            base = Highcharts.getOptions().colors[0],
            i;
    
        for (i = 0; i < 10; i += 1) {
            // Start out with a darkened base color (negative brighten), and end
            // up with a much brighter color
            colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
        }
        return colors;
    }());
    
    Highcharts.chart('pieChart', {
        colors: Highcharts.map(pieColors, function (color) {
            return {
              radialGradient: {
                cx: 0.5,
                cy: 0.3,
                r: 0.7
              },
              stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
              ]
            };
          }),
        chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie',
          backgroundColor: "transparent"
        },
        title: "",
        tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: true,
              format: '<b>{point.name}</b>: {point.percentage:.0f} %',
              style: {
                color: 'black',
                textOutline: false
              },
              connectorColor: 'silver'
            }
          }
        },
        legend: {
            enabled: false,
        },
        credits: {
            enabled: false
        },
        series: [{
          name: 'Share',
          data: [
            { name: 'CSS', y: cssResponse },
            { name: 'HTML', y: htmlResponse },
            { name: 'Javascript', y: javascriptResponse },
            { name: 'Image', y: imageResponse },
            { name: 'Other', y: otherResponse }
            ]
        }]
      });

      jQuery('#totalContentSize').html("Total content size: " + totalResponse + " Kb");
}