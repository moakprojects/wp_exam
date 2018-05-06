var apiKey = 'AIzaSyB4FTmZcK_2H9kh3qm5fErvtGf90xvoFMQ';

var testUrl = 'http://www.erstebank.hu';

console.log('ww', testUrl);

var apiUrl = 'https://www.googleapis.com/pagespeedonline/v4/runPagespeed?';

var startTime = Date.now();
jQuery.get(apiUrl, {url: testUrl, key: apiKey, snapshots: true, screenshot: true}, function(returnData) {
    var elapsedTime = ((Date.now() - startTime) / 1000).toFixed(2);

    if(returnData.responseCode >= 400) {
        jQuery('.tryAgainContainer').removeClass('hide');
    } else {
        console.log("retu", returnData);
    
        var ruleResultData = returnData.formattedResults.ruleResults;

        console.log(ruleResultData);

        var pageSpeed = returnData.ruleGroups.SPEED.score;
        var screenshot = returnData.screenshot.data;
        var ruleResults = [];
        var ruleImpactSummary = 0;

        for(var key in ruleResultData) 
        {
            var item = ruleResultData[key];
            ruleImpactSummary += item.ruleImpact;
        }

        console.log(ruleImpactSummary);
        
        var i = 0;
        for (var key in ruleResultData) {
            var item = ruleResultData[key];
            var summaryArgs = [];
            var summaryType = null;

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

            var ruleImpactPercent = Math.round((1 - (item.ruleImpact / ruleImpactSummary)) * 100);
            ruleResults[i] = [
                item.localizedRuleName,
                ruleImpactPercent,
                item.summary.format,
                summaryType,
                summaryArgs
            ];
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

            jQuery('.sopContainer').load(sop_ajax.ajax_url + ' .sopContainer', data, function() {
                jQuery('.start-screen').addClass("hide");
                jQuery('.collapsible').collapsible();
                jQuery('.sopContainer').removeClass("hide");
            });
        });
    }
});