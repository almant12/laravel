<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="../../../public/images/favicon.ico" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        laravel: "#ef3b2d",
                    },
                },
            },
        };
    </script>
    <title>LaraGigs | Find Laravel Jobs & Projects</title>
</head>
<body class="mb-48">

<nav class="flex justify-between items-center mb-4">
    <a href="/"
    ><img class="w-24" src="{{asset('images/logo.png')}}" alt="" class="logo"
        /></a>
    <ul class="flex items-center space-x-6 mr-6 text-lg">
            @if(auth()->user() && auth()->user()->verified == true)
            <li>
            <img style="height: 50px;width: 50px" class="rounded-full w-96 h-96"
            src="{{auth()->user()->logo ? asset('storage/'.auth()->user()->logo) : asset('images/no-user.png')}}">
            </li>
        <li>
            <span class="font-bold uppercase">
                <a href="{{route('user.edit',['user'=>\Illuminate\Support\Facades\Auth::user()->id])}}">
                Welcome {{auth()->user()->name}} {{auth()->user()->lastname}}
            </a>
            </span>
        </li>
        <li>
            <a href="{{route('listing.manage')}}" class="hover:text-laravel"
            ><i class="fa-solid fa-gear"></i> Manage Listings</a
            >
        </li>
            <li>
                <form action="{{route('user.logout')}}" method="POST">
                    @csrf
                    <button type="submit" class="hover:text-laravel">
                        <i class="fa-solid fa-door-closed"></i>
                        Logout
                    </button>
                </form>
            </li>
            @else
        <li>
            <a href="{{route('user.register')}}" class="hover:text-laravel"
            ><i class="fa-solid fa-user-plus"></i> Register</a
            >
        </li>
        <li>
            <a href="/login" class="hover:text-laravel"
            ><i class="fa-solid fa-arrow-right-to-bracket"></i>
                Login</a
            >
        </li>
            @endif
    </ul>
</nav>

<main>
{{$slot}}
</main>

<footer
    class="relative w-full flex items-center justify-start font-bold bg-laravel text-white h-24 mt-24 opacity-90 md:justify-center"
>
    <p class="ml-2">Copyright &copy; 2022, All Rights reserved</p>

    @auth
    <a href="{{route('listing.create')}}"
        class="absolute top-1/3 right-10 bg-black text-white py-2 px-5"
    >Post Job</a>
    @endauth
</footer
    class="relative w-full flex items-center justify-start font-bold bg-laravel text-white h-24 mt-24 opacity-90 md:justify-center">
<x-flash-message/>
</body>
</html>
