<x-layout>

    @php
        function getImageUrl($image) {
            if(str_starts_with($image, 'http')) {
                return $image;
            }
            return asset('storage/logos') . '/' . $image;
        }
    @endphp

    <!-- Search -->
    @include('partials._search')
    <div class="mx-4">
        <x-card class="p-10">
            <header>
                <h1 class="text-3xl text-center font-bold my-6 uppercase">
                    Manage Gigs
                </h1>
            </header>

            <table class="w-full table-auto rounded-sm">
                <tbody>
                    @unless (count($listings) == 0)
                        @foreach ($listings as $listing)
                        <tr class="border-gray-300">
                            <td class="px-4 py-8 border-t border-b border-gray-300 text-lg">
                                {{ $listing->id }}
                            </td>
                            <td class="px-4 py-8 border-t border-b border-gray-300 text-lg">
                                <a href="{{ route('listing.show', $listing->id) }}">
                                    {{ $listing->title }}
                                </a>
                            </td>
                            <td class="px-4 py-8 border-t border-b border-gray-300 text-lg">
                                <a href="{{ route('listing.edit', $listing->id) }}" class="text-blue-400 px-6 py-2 rounded-xl"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            </td>
                            <td class="px-4 py-8 border-t border-b border-gray-300 text-lg">
                                <form method="POST" action="{{ route('listing.destroy', $listing->id) }}" onsubmit="return confirm('Do you want to delete?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500"><i class="fa-solid fa-trash-can"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @else
                        <p>No Listing Found!</p>
                    @endunless
                </tbody>
            </table>
        </x-card>
    </div>

    <div class="mt-6 p-4">
        {{$listings->links()}}
    </div>
</x-layout>
