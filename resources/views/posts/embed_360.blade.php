<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embed 360 Viewer</title>
    
    <!-- CSS Pannellum -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
    
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #000;
        }
        #panorama {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div id="panorama"></div>

    <!-- JS Pannellum -->
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            pannellum.viewer('panorama', {
                "type": "equirectangular",
                "panorama": "{{ $url }}",
                "autoLoad": true,
                "compass": false,
                "uiHeaders": false
            });
        });
    </script>
</body>
</html>
