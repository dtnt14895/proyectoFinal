<?php

namespace App\Http\Controllers;

use App\Models\Log as ModelsLog;
use App\Models\Rol;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Rol = Rol::where('status', 1)->get();
        $Rol->load('create_by.person');
        $Rol->load('update_by.person');
        return $Rol;
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
            $Rol = Rol::findOrFail($id);
            $Rol->load('create_by');
            $Rol->load('update_by');

            return $Rol;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Rol ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }

    }

    
    public function create(Request $request)
    {

       
        try {

            $validator = validator($request->all(), [
                'name'=> 'required',
                'create_by'=>'exists:users,id',
                'update_by'=> 'exists:users,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            
            Rol::create($request->all());

            $log = new LogController();
            $respuesta = $log->create("Creo el rol ".$request->name);

            return response()->json(['msj' => 'Rol creado correctamente','log' => $respuesta->original['msj']], 200);
          
        
        } catch (QueryException $e) {
            $errormsj = $e->getMessage();
        
            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key '(.*?)'/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? '';
                $duplicateKey = $matches[2] ?? '';
        
                return response()->json(['error' => "No se puede realizar la acción, el valor '$duplicateValue' ya está duplicado en el campo '$duplicateKey'"], 422);
            }
            return response()->json(['error' => 'Error en la acción realizada: ' . $errormsj], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'No se pudo registrar el Rol'.$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la accion realizada' . $e->getMessage()], 500);
        }
    }

    public function update($id,Request $request)
    {
        
        try {
            $validator = validator($request->all(), [
                'name'=> 'required',
                'create_by'=>'exists:users,id',
                'update_by'=> 'exists:users,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $Rol = Rol::findOrFail($id);
            $Rol->update($request->all());
            $Rol->save();

           

            return response()->json(['msj' => 'Rol actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Rol ' . $id . ' no existe no fue encontrado'], 404);
        } catch (QueryException  $e) {
            $errormsj = $e->getMessage();

            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? 'Tienes un valor que';

                return response()->json(['error' => 'Error: ' . $duplicateValue . ' ya esta en uso'], 422);
            }

            return response()->json(['error' => 'Error en la acción realizada'.$errormsj], 500);
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
            $Rol = Rol::findOrFail($id);
            if ($Rol->status) {
                $Rol->status = 0;
                $Rol->save();
                return response()->json(['msj' => 'Rol eliminado correctamente'], 200);
            }
            return response()->json(['msj' => 'Este Rol ya ha sido eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Rol ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }
}
