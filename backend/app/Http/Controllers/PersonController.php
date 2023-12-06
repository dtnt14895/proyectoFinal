<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Person = Person::where('status', 1)->get();
        $Person->load('create_by.person');
        $Person->load('update_by.person');

        return $Person;
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
            $Person = Person::findOrFail($id);
            $Person->load('create_by');
            $Person->load('update_by');
    
            return $Person;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Person ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }

    }

    
    public function create(Request $request)
    {

       
        try {

            $validator = validator($request->all(), [
                'name'=> 'required',
                'lastname'=> 'required', 
                'phone'=> 'required',
                'born'=> 'required|date',
                'create_by'=>'exists:users,id',
                'update_by'=> 'exists:users,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            
            Person::create($request->all());
           
            return response()->json(['msj' => 'Person creado correctamente'], 200);

            $log = new LogController();
            $respuesta = $log->create("Ha creado la persona".$request->name." ".$request->lastname);
            return response()->json(['msj' => 'Persona creada correctamente','log' => $respuesta->original['msj']], 200);


        
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
            return response()->json(['error' => 'No se pudo registrar el Person'.$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la accion realizada' . $e->getMessage()], 500);
        }
    }

    public function update($id,Request $request)
    {
        
        try {
            $validator = validator($request->all(), [
                'name'=> 'required',
                'lastname'=> 'required', 
                'phone'=> 'required',
                'born'=> 'required|date',
                'create_by'=>'exists:users,id',
                'update_by'=> 'exists:users,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $Person = Person::findOrFail($id);
            $Person->update($request->all());
            $Person->save();

           

            return response()->json(['msj' => 'Person actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Person ' . $id . ' no existe no fue encontrado'], 404);
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
            $Person = Person::findOrFail($id);
            if ($Person->status) {
                $Person->status = 0;
                $Person->save();
                return response()->json(['msj' => 'Person eliminado correctamente'], 200);
            }
            return response()->json(['msj' => 'Este Person ya ha sido eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Person ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }
}
