<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
   @yield('custom-css')

   @include('layouts.header')

   @yield('content')

   @include('layouts.footer')
   
   @include('layouts.cart-sidebar')
   @include('layouts.script')

   @yield('custom-js')
</body>
</html>
