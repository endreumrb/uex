<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Contatos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 gap-6 flex" style="height: 75vh;">
                    <div class="">
                        <!-- Formulário de pesquisa -->
                        <form method="GET" action="{{ route('contacts.index') }}">
                            <div class="flex items-center gap-2">
                                <input type="text" name="search" placeholder="Pesquisar por nome ou CPF"
                                    class="form-input rounded-md shadow-sm block w-full"
                                    value="{{ request('search') }}">
                                <button type="submit"
                                    class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>

                                </button>
                                <button type="submit"
                                    class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>

                                </button>
                            </div>
                        </form>

                        <!-- Tabela de contatos -->
                        <div class="mt-6">
                            <table class="divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a
                                                href="{{ route('contacts.index', ['sort' => 'nome', 'order' => $sort === 'nome' && $order === 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'order'])) }}">
                                                Nome
                                                @if ($sort === 'nome')
                                                    <i class="fas fa-sort-{{ $order === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a
                                                href="{{ route('contacts.index', ['sort' => 'cpf', 'order' => $sort === 'cpf' && $order === 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'order'])) }}">
                                                CPF
                                                @if ($sort === 'cpf')
                                                    <i class="fas fa-sort-{{ $order === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <span class="sr-only"></span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($contacts as $contact)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $contact->nome }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $contact->cpf }}
                                            </td>
                                            <td class="px-2 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('contacts.edit', $contact) }}"
                                                    class="text-gray-600 hover:text-gray-900"><svg
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Paginação -->
                            <div class="mt-4">
                                {{ $contacts->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 h-full">
                        <!-- Mapa -->
                        <div id="map" class="w-full h-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer
            loading=lazy></script>
        <script>
            var contacts = @json($contacts);

            let map;

            async function initMap() {
                const {
                    Map
                } = await google.maps.importLibrary("maps");

                // Obter o último contato inserido
                var lastContact = contacts.data[contacts.data.length - 1];

                map = new Map(document.getElementById("map"), {
                    center: {
                        lat: parseFloat(lastContact.latitude),
                        lng: parseFloat(lastContact.longitude)
                    },
                    zoom: 8,
                });

                contacts.data.forEach(function(contact) {
                    var marker = new google.maps.Marker({
                        position: {
                            lat: parseFloat(contact.latitude),
                            lng: parseFloat(contact.longitude)
                        },
                        map: map,
                        title: contact.nome
                    });

                    var infoWindow = new google.maps.InfoWindow({
                        content: '<div><strong>' + contact.nome + '</strong><br>' + contact.endereco +
                            '</div>'
                    });

                    marker.addListener('click', function() {
                        infoWindow.open(map, marker);
                    });
                });
            }

            initMap();
        </script>
    @endpush
</x-app-layout>
