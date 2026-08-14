<!DOCTYPE html>
<html lang="en">


@include('frontend.layouts.includes.head')

<body>


    @include('frontend.layouts.includes.header')
    <main>
        @yield('content')

    </main>
    @include('frontend.layouts.includes.footer')
    @include('frontend.layouts.includes.scripts')
</body>

</html>
