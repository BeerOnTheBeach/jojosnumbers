<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jojos Numbers</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="src/css/bootstrap.min.css">
    <link rel="stylesheet" href="src/css/main.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="src/js/jquery-3.5.1.min.js"></script>
    <script src="src/js/bootstrap.min.js"></script>

    <link rel="icon" type="image/png" href="src/images/favicon.jpg"/>
</head>
<body>
<?php include 'src/snippets/header.php';
?>
<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
    <div id="tradingview_03350"></div>
    <div class="tradingview-widget-copyright"><a href="https://de.tradingview.com/symbols/NASDAQ-AAPL/" rel="noopener" target="_blank"><span class="blue-text">AAPL Chart</span></a> von TradingView</div>
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    <script type="text/javascript">
        new TradingView.widget(
            {
                "width": 980,
                "height": 610,
                "symbol": "NASDAQ:AAPL",
                "interval": "D",
                "timezone": "Etc/UTC",
                "theme": "light",
                "style": "1",
                "locale": "de_DE",
                "toolbar_bg": "#f1f3f6",
                "enable_publishing": false,
                "allow_symbol_change": true,
                "container_id": "tradingview_03350"
            }
        );
    </script>
</div>
<!-- TradingView Widget END -->
</body>
</html>