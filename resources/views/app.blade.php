<!DOCTYPE html>
<html>
  <head>
    <title>Donquijote</title>
    <link href="{{ asset('css/app.css')}}" rel="stylesheet">
  </head>
  <body>
    <div id="app"></div>

    <script>
      window.loggedIn = {{ json_encode(Auth::check()) }}
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
  </body>
</html>
