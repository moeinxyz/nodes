<footer class="padding">
    <div class="container">
        <div class="row"> 
            <div class="col-xs-12">
                <small><?= Yii::t('app','footer'); ?></small>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <a class="github-button" href="https://github.com/moein7tl/nodes" data-count-href="/moein7tl/nodes/stargazers" data-count-api="/repos/moein7tl/nodes#stargazers_count" data-count-aria-label="# stargazers on GitHub" aria-label="Star moein7tl/nodes on GitHub">Star</a>
                <a class="github-button" href="https://github.com/moein7tl/nodes/issues" data-count-api="/repos/moein7tl/nodes#open_issues_count" data-count-aria-label="# issues on GitHub" aria-label="Issue moein7tl/nodes on GitHub">Issue</a>
                <a class="github-button" href="https://github.com/moein7tl/nodes/fork" data-count-href="/moein7tl/nodes/network" data-count-api="/repos/moein7tl/nodes#forks_count" data-count-aria-label="# forks on GitHub" aria-label="Fork moein7tl/nodes on GitHub">Fork</a>
                <script async defer src="https://buttons.github.io/buttons.js"></script>
            </div>
        </div>
    </div>
</footer>

<?php
$js=<<<JS
window.heap=window.heap||[],heap.load=function(e,t){window.heap.appid=e,window.heap.config=t=t||{};var r=t.forceSSL||"https:"===document.location.protocol,a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=(r?"https:":"http:")+"//cdn.heapanalytics.com/js/heap-"+e+".js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n);for(var o=function(e){return function(){heap.push([e].concat(Array.prototype.slice.call(arguments,0)))}},p=["addEventProperties","addUserProperties","clearEventProperties","identify","removeEventProperty","setEventProperties","track","unsetEventProperty"],c=0;c<p.length;c++)heap[p[c]]=o(p[c])};
heap.load("3179509252");
JS;

$this->registerJs($js);
?>