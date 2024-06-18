<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Models\Contact;
use App\Services\GoogleMapsService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * @var GoogleMapsService
     */
    protected $googleMapsService;

    /**
     * ContactController constructor.
     *
     * @param GoogleMapsService $googleMapsService
     */
    public function __construct(GoogleMapsService $googleMapsService)
    {
        $this->googleMapsService = $googleMapsService;
    }

    /**
     * Lista os contatos com opções de pesquisa, ordenação e paginação.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'nome');
        $order = $request->input('order', 'asc');
        $perPage = $request->input('per_page', 10);
    
        $query = auth()->user()->contacts();
    
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('nome', 'like', '%' . $search . '%')
                    ->orWhere('cpf', 'like', '%' . $search . '%');
            });
        }
    
        $query->orderBy($sort, $order);
    
        $contacts = $query->paginate($perPage);
    
        return view('contacts.index', compact('contacts', 'sort', 'order'));
    }

    /**
     * Retorna a view para criar um novo contato.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Armazena um novo contato no banco de dados.
     *
     * @param ContactCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ContactCreateRequest $request)
    {
        $validatedData = $request->validated();

        $endereco = $validatedData['endereco'];
        $cidade = $validatedData['cidade'];
        $cep = $validatedData['cep'];

        $enderecoCompleto = "$endereco, $cidade, $cep";

        [$latitude, $longitude] = $this->googleMapsService->getLatLong($enderecoCompleto);

        $validatedData['latitude']  = $latitude;
        $validatedData['longitude'] = $longitude;
        $validatedData['user_id']   = auth()->user()->id;
        $validatedData['cpf']       = preg_replace('/\D/', '', $validatedData['cpf']);
        $validatedData['telefone']  = preg_replace('/\D/', '', $validatedData['telefone']);

        Contact::create($validatedData);

        return redirect()->route('contacts.index')->with('success', 'Contato criado com sucesso!');
    }

    /**
     * Retorna a view para editar um contato existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Atualiza um contato existente no banco de dados.
     *
     * @param  \App\Http\Requests\ContactUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ContactUpdateRequest $request, $id)
    {
        $validatedData = $request->validated();

        $validatedData['cpf'] = preg_replace('/\D/', '', $validatedData['cpf']);
        $validatedData['telefone']  = preg_replace('/\D/', '', $validatedData['telefone']);

        $contact = Contact::findOrFail($id);
        $contact->update($validatedData);

        return redirect()->route('contacts.index')->with('success', 'Contato atualizado com sucesso!');
    }
}
