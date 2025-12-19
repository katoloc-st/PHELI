<head>
   <!-- Google Fonts: Barlow -->
   <link href="https://fonts.googleapis.com/css?family=Barlow:400,500,600,700&display=swap" rel="stylesheet">
      <!-- Global site tag (gtag.js) - Google Analytics -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="Webartinfo">
      <meta name="author" content="Webartinfo">
      <title>PHELI - Thu gom phế liệu online</title>
      <!-- Favicon Icon -->
      <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
      <!-- Bootstrap core CSS -->
      <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
      <!-- Material Design Icons -->
      <link href="{{ asset('vendor/icons/css/materialdesignicons.min.css') }}" media="all" rel="stylesheet" type="text/css" />
      <!-- Select2 CSS -->
      <link href="{{ asset('vendor/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />
      <link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet" />
      <!-- Custom styles for this template -->
      <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <!-- Liên kết đến Font Awesome để sử dụng các biểu tượng mạng xã hội -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Additional styles from child views -->
        @stack('styles')
   </head>
