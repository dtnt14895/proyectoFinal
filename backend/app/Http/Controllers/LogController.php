<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return Log::with('user.rol', 'user.person')->get();

    }

    
    public function show($id)
    {

        $validator = validator(['id' => $id], [
            'id' => 'required|numeric'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $Log = Log::findOrFail($id);
            $Log->load('user');

            return $Log;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Log ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }

    }

    public function create($descripcion)
    {

        $request = new Request();
        
        $request->merge([
            'description' => $descripcion,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'so' => request()->header('X-User-OS'),
            'browser' => request()->header('X-User-Browser'),
        ]);
    
        try {

            $validator = validator($request->all(), [
                'description'=> 'required',
                'user_id'=> 'required|exists:users,id',
                'ip'=> 'required',
                'so'=> 'required',
                'browser'=> 'required'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            Log::create($request->all());
           
          
            return response()->json(['msj' => $descripcion], 200);
        
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'No se pudo registrar el Log'.$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la accion realizada' . $e->getMessage()], 500);
        }
    }

    public function update($id,Request $request)
    {
        
        try {
            $validator = validator($request->all(), [
                'description'=> 'required',
                'user_id'=> 'required|exists:users,id',
                'ip'=> 'required',
                'so'=> 'required',
                'browser'=> 'required'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $Log = Log::findOrFail($id);
            $Log->update($request->all());
            $Log->save();

           

            return response()->json(['msj' => 'Log actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Log ' . $id . ' no existe no fue encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }

    public function destroy($id)
    {
        $validator = validator(['id' => $id], [
            'id' => 'required|numeric'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $Log = Log::findOrFail($id);
            if ($Log->status) {
                $Log->status = 0;
                $Log->save();
                return response()->json(['msj' => 'Log eliminado correctamente'], 200);
            }
            return response()->json(['msj' => 'Este Log ya ha sido eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Log ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }
}
