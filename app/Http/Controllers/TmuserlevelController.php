<?php

namespace App\Http\Controllers;

use App\Models\Tmuser_level;
use Illuminate\Http\Request;
use App\Helpers\Properti_app;
use DataTables;

class TmuserlevelController extends Controller
{

    protected $route = 'akses.';
    protected $view  = 'level.';

    function __construct()
    {
//       $this->middleware('Level');
    }

    public function index()
    {
        $route       = $this->route;
        $load_script = Properti_app::load_js([
            asset('assets/template/js/plugin/datatables/datatables.min.js'),
        ]);
        return view($this->view . 'index', compact('load_script', 'route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function api(Request $request)
     { 
        $data = Tmuser_level::get();
           return DataTables::of($data)
            ->editColumn('nama_level',function($p){
                return '<b>'.$p->description.'</b>';
            })

            ->addIndexColumn()
            ->rawColumns(['nama_level','action'])
            ->editColumn('action', function ($p) {
                return '<button to="' . Url($this->route . '/' . $p->id . '/edit') . '" class="edit btn btn-warning btn-xs"><i class="fa fa-edit"></i>Edit </button>
                        <button data="' . $p->id . '" class="delete btn btn-danger btn-xs"><i class="fa fa-list"></i>Delete</button>';
            })
            ->toJson(); 
     }  

    public function create()
    {
        return view([
            'action' => $this->route . '.store',
            'description' => '',
            'mapping_sie' => '',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'mapping_sie' => 'required',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data   = Tmuser_level::find($id);
        $method = method_field('PATCH');
        return view([
            'action' => $this->view . '.form_add',
            'description' => $data->description,
            'mapping_sie' =>  $data->mapping_site,
            'method' => $method,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $data = Tmuser_level::FindOrfail($request->id);
        $data->delete();
        return response()->json(['message' => "Data rekening kode objek berhasil dihapus."]);
    }
}
