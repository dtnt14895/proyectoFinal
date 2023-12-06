<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $User = User::where('status', 1)->get();
        $User->load('create_by');
        $User->load('update_by');
        $User->load('person');
        $User->load('rol');
        return $User;
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
            $User = User::findOrFail($id);
            $User->load('create_by');
            $User->load('update_by');
            $User->load('person');
            $User->load('rol');
            return $User;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El User ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }

    }


    public function create(Request $request)
    {
        try {

                       
            $request->merge([
                'password' => bcrypt($request->password)
            ]);

            $validator = validator($request->all(), [
                'person_id'=> 'required|exists:persons,id',
                'email'=> 'required|email',
                'password'=> 'required',
                'rol_id'=> 'exists:rols,id',
                'create_by'=>'exists:users,id',
                'update_by'=> 'exists:users,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

           
            $User = User::create($request->all());
            $User->load('person');
            $User->load('rol');
           
            $log = new LogController();
            $respuesta = $log->create("Ha creado el usuario ".$request->email." para ".$User->person->name." con el rol ".$User->rol->name);
            return response()->json(['msj' => 'Usuario creado correctamente','log' => $respuesta->original['msj']], 200);
        
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
            return response()->json(['error' => 'No se pudo registrar el User'.$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la accion realizada' . $e->getMessage()], 500);
        }
    }


    public function update($id,Request $request)
    {
        
        try {
            $validator = validator($request->all(), [
                'person_id'=> 'required|exists:persons,id',
                'email'=> 'required|email',
                'password'=> 'required',
                'rol_id'=> 'required|exists:rols,id',
                'create_by'=>'exists:users,id',
                'update_by'=> 'exists:users,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $User = User::findOrFail($id);
            $User->update($request->all());
            $User->save();

           

            return response()->json(['msj' => 'User actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El User ' . $id . ' no existe no fue encontrado'], 404);
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
            $User = User::findOrFail($id);
            if ($User->status) {
                $User->status = 0;
                $User->save();
                return response()->json(['msj' => 'User eliminado correctamente'], 200);
            }
            return response()->json(['msj' => 'Este User ya ha sido eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El User ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }
}
