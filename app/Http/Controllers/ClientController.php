<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class ClientController extends Controller
{

  public function index()
    {
        return view('Client.index');
    }
    
}