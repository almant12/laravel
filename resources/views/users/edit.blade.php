<x-layout>
    <x-card class="p-10 max-w-lg mx-auto mt-24">
        <header class="text-center">
            <h2 class="text-2xl font-bold uppercase mb-1">Edit Profile</h2>
        </header>

        <form method="POST" action="{{route('user.update',['user'=>$user->id])}}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-6">
                <label for="name" class="inline-block text-lg mb-2">Username</label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="name"
                       value="{{$user->name}}" />

                @error('name')
                <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="lastname" class="inline-block text-lg mb-2">Lastname</label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="lastname"
                       placeholder="Example: Senior Laravel Developer" value="{{$user->lastname}}" />

                @error('lastname')
                <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="logo" class="inline-block text-lg mb-2">
                    Profile Img
                </label>
                <input type="file" class="border border-gray-200 rounded p-2 w-full" name="logo" />

                <img class="w-48 mr-6 mb-6"
                     src="{{$user->logo ? asset('storage/' . $user->logo) : asset('/images/no-user.png')}}" alt="" />

                @error('logo')
                <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>


            <div class="mb-6">
                <button class="bg-laravel text-white rounded py-2 px-4 hover:bg-black">
                    Update User
                </button>

                <a href="{{route('index')}}" class="text-black ml-4"> Back </a>
            </div>
        </form>
    </x-card>
</x-layout>
