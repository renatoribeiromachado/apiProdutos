<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produto;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    private $produto;
    private $totalPg = 10;
    private $path = 'produtos';

    public function __construct(Produto $produto){

        $this->produto = $produto;
    }

    public function index()
    {
        $data = $this->produto->paginate($this->totalPg);

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        
        $data = [
            'user_id' => 1,
            'codigo'  => $request->codigo,
            'nome'    => $request->nome,
            'price'   => str_replace(',','.', $request->price),
            'data'    => date('Y-m-d'),
        ];

        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {

            $extension = $request->imagem->extension();
            $nameFile  = uniqid(date('HisYmd'));
            $file      = "{$nameFile}.{$extension}";

            $data['imagem'] = $file;

            $upload = $request->imagem->storeAs($this->path, $file);

        }

        $create = $this->produto->create($data);

        return response()->json(['success' => 'Cadastrado com sucesso']);
    }

    public function show($id)
    {
        $produto = $this->produto->find($id);

        if(!$produto)
            return response()->json(['error' => 'Produto não existe']);

        return response()->json(['data' => $produto]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $produto = $this->produto->find($id);
        
        if(!$produto)
            return response()->json(['error' => 'Produto não existe para editar']);
        
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {

            if($produto->imagem)
            {
                if(Storage::exists("{$this->path}/{$produto->imagem}"))
                    Storage::delete("{$this->path}/{$produto->imagem}");
            }

            $extension = $request->imagem->extension();
            $nameFile  = uniqid(date('HisYmd'));
            $file      = "{$nameFile}.{$extension}";

            $data['imagem'] = $file;

            $upload = $request->imagem->storeAs($this->path, $file);
            
        }

        $update = $produto->update($data);

        if($update)
            return response()->json(['success' => 'Atualizado com sucesso']);

        return response()->json(['error' => 'Error ao atualizar']);

    }

    public function destroy($id){

        $produto = $this->produto->find($id);

        if(!$produto)
            return response()->json(['error' => 'Produto não existe para ser deletado']);

        if(Storage::exists("{$this->path}/{$produto->imagem}"))
            Storage::delete("{$this->path}/{$produto->imagem}");

        $delete = $produto->delete();

        if($delete)
            return response()->json(['success' => 'Deletado com sucesso']);

        return response()->json(['error' => 'Não foi possivel deletar']);

    }

}