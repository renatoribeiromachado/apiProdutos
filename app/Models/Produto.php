<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    //para cadastro é preciso
    protected $table = "produtos";
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id','codigo', 'nome', 'price', 'imagem','data',//importante todos os campos aqui para cadastrar
    ];

    public function rules()
    {

        return  [
            'user_id' => 'required',
            'codigo'  => 'required',
            'nome'    => 'required',
            'price'   => 'required',
            'imagem'  => ['required'],
            'data'    => 'required'
        ];
    }
}