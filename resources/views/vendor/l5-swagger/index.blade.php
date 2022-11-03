<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ config("l5-swagger.documentations.{$documentation}.api.title") }}</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Source+Code+Pro:300,600|Titillium+Web:400,600,700" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ l5_swagger_asset($documentation, 'swagger-ui.css') }}" >
  <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-32x32.png') }}" sizes="32x32" />
  <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-16x16.png') }}" sizes="16x16" />
  <style>
    html
    {
        box-sizing: border-box;
        overflow: -moz-scrollbars-vertical;
        overflow-y: scroll;
    }
    *,
    *:before,
    *:after
    {
        box-sizing: inherit;
    }

    body {
      margin:0;
      background: #fafafa;
    }
  </style>
</head>

<body>

<div id="swagger-ui"></div>

<script src="{{ l5_swagger_asset($documentation, 'swagger-ui-bundle.js') }}"> </script>
<script src="{{ l5_swagger_asset($documentation, 'swagger-ui-standalone-preset.js') }}"> </script>
<script>
window.onload = function() {
    const ui = SwaggerUIBundle({
        dom_id: '#swagger-ui',
        url: {{ Js::from($urlToDocs) }},
        operationsSorter: {{ Js::from($operationsSorter) }},
        configUrl: {{ Js::from($configUrl ?? null) }},
        validatorUrl: {{ Js::from($validatorUrl ?? null) }},
        oauth2RedirectUrl: {{ Js::from(route('l5-swagger.'.$documentation.'.oauth2_callback', [], $useAbsolutePath)) }},
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset,
        ],
        plugins: [
            SwaggerUIBundle.plugins.DownloadUrl,
        ],
        layout: 'StandaloneLayout',
        docExpansion : {{ Js::from(config('l5-swagger.defaults.ui.display.doc_expansion', 'none')) }},
        filter: {{ Js::from(!!config('l5-swagger.defaults.ui.display.filter')) }},
        persistAuthorization: {{ Js::from(!!config('l5-swagger.defaults.ui.authorization.persist_authorization')) }},
    });

    window.ui = ui;
};
</script>
</body>

</html>
