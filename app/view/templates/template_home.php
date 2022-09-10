<!DOCTYPE html>
<html lang="<?=$this->e($info->_e('page_lang'));?>">
<head>
<meta charset="utf-8">
<title><?=$this->e($info->_e('page_title'));?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">   
<?php $this->insert('inc::styles'); ?>
<?=$this->section('scripts')?>
</head>
<body class="landing-page">     
<main><?=$this->section('content')?></main>
<?php
$server = new \Moviao\Http\ServerInfo();
$suffix = $server->getServerSuffix();
if ($suffix !== 'LOCALHOST') {
?>
<!-- Matomo -->
<!--<script type="text/javascript">-->
<!--    var _paq = _paq || [];-->
<!--    /* tracker methods like "setCustomDimension" should be called before "trackPageView" */-->
<!--    _paq.push(['trackPageView']);-->
<!--    _paq.push(['enableLinkTracking']);-->
<!--    (function() {-->
<!--        var u="//www.moviao.com/analytics/";-->
<!--        _paq.push(['setTrackerUrl', u+'piwik.php']);-->
<!--        _paq.push(['setSiteId', '1']);-->
<!--        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];-->
<!--        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);-->
<!--    })();-->
<!--</script>-->
<!-- End Matomo Code -->
<?php } ?>
</body>
</html>