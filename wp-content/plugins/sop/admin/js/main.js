var apiKey = 'AIzaSyB4FTmZcK_2H9kh3qm5fErvtGf90xvoFMQ';

var testUrl = 'http://www.off-topic.tk';

console.log('ww', testUrl);

var apiUrl = 'https://www.googleapis.com/pagespeedonline/v4/runPagespeed?';

$.get(apiUrl, {url: testUrl, key: apiKey}, function(returnData) {
    
    var ruleResults = returnData.formattedResults.ruleResults;

    console.log("rul", ruleResults);

    $('#f0').html(ruleResults.AvoidLandingPageRedirects.localizedRuleName);
    $('#f1').html(ruleResults.AvoidLandingPageRedirects.ruleImpact);

    $('#s0').html(ruleResults.EnableGzipCompression.localizedRuleName);
    $('#s1').html(ruleResults.EnableGzipCompression.ruleImpact);

    $('#t0').html(ruleResults.LeverageBrowserCaching.localizedRuleName);
    $('#t1').html(ruleResults.LeverageBrowserCaching.ruleImpact);

    $('#fo0').html(ruleResults.MainResourceServerResponseTime.localizedRuleName);
    $('#fo1').html(ruleResults.MainResourceServerResponseTime.ruleImpact);

    $('#fi0').html(ruleResults.MinifyCss.localizedRuleName);
    $('#fi1').html(ruleResults.MinifyCss.ruleImpact);

    $('#si0').html(ruleResults.MinifyHTML.localizedRuleName);
    $('#si1').html(ruleResults.MinifyHTML.ruleImpact);

    $('#se0').html(ruleResults.MinifyJavaScript.localizedRuleName);
    $('#se1').html(ruleResults.MinifyJavaScript.ruleImpact);

    $('#e0').html(ruleResults.MinimizeRenderBlockingResources.localizedRuleName);
    $('#e1').html(ruleResults.MinimizeRenderBlockingResources.ruleImpact);

    $('#n0').html(ruleResults.OptimizeImages.localizedRuleName);
    $('#n1').html(ruleResults.OptimizeImages.ruleImpact);

    $('#te0').html(ruleResults.PrioritizeVisibleContent.localizedRuleName);
    $('#te1').html(ruleResults.PrioritizeVisibleContent.ruleImpact);
});