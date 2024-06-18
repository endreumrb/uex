<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Contato') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('contacts.update', $contact) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="nome"
                                class="block font-medium text-sm text-gray-700">{{ __('Nome') }}</label>
                            <input id="nome" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="text" name="nome" value="{{ $contact->nome }}" required autofocus />
                            @error('nome')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="cpf"
                                class="block font-medium text-sm text-gray-700">{{ __('CPF') }}</label>
                            <input id="cpf" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="text" name="cpf" value="{{ $contact->cpf }}"
                                data-inputmask="'mask': '999.999.999-99'" required />
                            @error('cpf')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="telefone"
                                class="block font-medium text-sm text-gray-700">{{ __('Telefone') }}</label>
                            <input id="telefone" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="tel" name="telefone" value="{{ $contact->telefone }}"
                                data-inputmask="'mask': '(99) 99999-9999'" required />
                            @error('telefone')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="endereco"
                                class="block font-medium text-sm text-gray-700">{{ __('Endereço') }}</label>
                            <div class="relative">
                                <input id="endereco" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                    type="text" name="endereco" value="{{ $contact->endereco }}" required />
                                <div id="endereco-sugestoes"
                                    class="absolute z-10 w-full bg-white rounded-md shadow-lg mt-1 hidden"></div>
                            </div>
                            @error('endereco')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="cep"
                                class="block font-medium text-sm text-gray-700">{{ __('CEP') }}</label>
                            <input id="cep" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="text" name="cep" value="{{ $contact->cep }}"
                                data-inputmask="'mask': '99999999'" required />
                            @error('cep')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="latitude"
                                class="block font-medium text-sm text-gray-700">{{ __('Latitude') }}</label>
                            <input id="latitude" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="text" name="latitude" value="{{ $contact->latitude }}" />
                            @error('latitude')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="longitude"
                                class="block font-medium text-sm text-gray-700">{{ __('Longitude') }}</label>
                            <input id="longitude" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="text" name="longitude" value="{{ $contact->longitude }}" />
                            @error('longitude')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Atualizar Contato') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.6/dist/inputmask.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Inputmask().mask(document.querySelectorAll('input'));
            });
        </script>
    @endpush
</x-app-layout>
