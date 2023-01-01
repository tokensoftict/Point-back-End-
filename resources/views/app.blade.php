<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Point</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/scss/app.scss')
</head>
<body>
<div id="app"></div>
<script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
<script>window.base_url = '{{ asset('') }}'</script>
@vite('resources/js/app.js')
</body>
</html>
