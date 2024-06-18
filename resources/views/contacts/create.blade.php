<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Novo Contato') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('contacts.store') }}">
                        @csrf

                        <div>
                            <label for="nome"
                                class="block font-medium text-sm text-gray-700">{{ __('Nome') }}</label>
                            <input id="nome" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="text" name="nome" value="{{ old('nome') }}" required autofocus />
                            @error('nome')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="cpf"
                                class="block font-medium text-sm text-gray-700">{{ __('CPF') }}</label>
                            <input id="cpf" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="text" name="cpf" value="{{ old('cpf') }}" required
                                data-inputmask="'mask': '999.999.999-99'" />
                            @error('cpf')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="telefone"
                                class="block font-medium text-sm text-gray-700">{{ __('Telefone') }}</label>
                            <input id="telefone" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="tel" name="telefone" value="{{ old('telefone') }}" required
                                data-inputmask="'mask': '(99) 99999-9999'" />
                            @error('telefone')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="uf"
                                class="block font-medium text-sm text-gray-700">{{ __('UF') }}</label>
                            <input id="uf" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="text" name="uf" value="{{ old('uf') }}" />
                        </div>

                        <div class="mt-4">
                            <label for="cidade"
                                class="block font-medium text-sm text-gray-700">{{ __('Cidade') }}</label>
                            <input id="cidade" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="text" name="cidade" value="{{ old('cidade') }}" />
                        </div>

                        <div class="mt-4 relative">
                            <label for="endereco"
                                class="block font-medium text-sm text-gray-700">{{ __('Endereço') }}</label>
                            <input id="endereco" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="text" name="endereco" value="{{ old('endereco') }}" required
                                autocomplete="off" />
                            <div id="endereco-sugestoes"
                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1">
                            </div>
                            @error('endereco')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="cep"
                                class="block font-medium text-sm text-gray-700">{{ __('CEP') }}</label>
                            <input id="cep" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                type="text" name="cep" value="{{ old('cep') }}" required
                                data-inputmask="'mask': '99999999'" />
                            @error('cep')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Criar Contato') }}
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
            const ufInput = document.getElementById('uf');
            const cidadeInput = document.getElementById('cidade');
            const enderecoInput = document.getElementById('endereco');
            const cepInput = document.getElementById('cep');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const enderecoSugestoesContainer = document.getElementById('endereco-sugestoes');

            function buscaEnderecos() {
                const uf = ufInput.value;
                const cidade = cidadeInput.value;
                const endereco = enderecoInput.value;

                if (!uf || !cidade || !endereco) {
                    return;
                }

                fetch(`/enderecos?uf=${uf}&cidade=${cidade}&trecho_endereco=${endereco}`)
                    .then(response => response.json())
                    .then(enderecos => {
                        enderecoSugestoesContainer.innerHTML = '';

                        if (enderecos.length === 0) {
                            enderecoSugestoesContainer.classList.add('hidden');
                        } else {
                            enderecoSugestoesContainer.classList.remove('hidden');

                            const listaSugestoes = document.createElement('ul');
                            listaSugestoes.classList.add('list-none', 'py-2');

                            enderecos.slice(0, 3).forEach((endereco, index) => {
                                const itemSugestao = document.createElement('li');
                                itemSugestao.textContent = `${endereco.logradouro}, ${endereco.cep} - ${endereco.localidade}, ${endereco.uf}`;
                                itemSugestao.classList.add('cursor-pointer', 'hover:bg-gray-100', 'px-4', 'py-2');
                                itemSugestao.setAttribute('data-index', index);
                                itemSugestao.addEventListener('click', () => {
                                    enderecoInput.value = endereco.logradouro;
                                    cepInput.value = endereco.cep;
                                    enderecoSugestoesContainer.classList.add('hidden');
                                });

                                listaSugestoes.appendChild(itemSugestao);
                            });

                            enderecoSugestoesContainer.appendChild(listaSugestoes);
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar sugestões:', error);
                    });
            }

            function preencherCamposEndereco(endereco) {
                enderecoInput.value = `${endereco.logradouro}, ${endereco.bairro}`;
                cepInput.value = endereco.cep;
                latitudeInput.value = endereco.latitude || '';
                longitudeInput.value = endereco.longitude || '';
            }

            ufInput.addEventListener('input', buscaEnderecos);
            cidadeInput.addEventListener('input', buscaEnderecos);
            enderecoInput.addEventListener('input', buscaEnderecos);

            document.addEventListener('DOMContentLoaded', () => {
                Inputmask().mask(document.querySelectorAll('input'));
            });
        </script>
    @endpush
</x-app-layout>
