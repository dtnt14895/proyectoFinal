<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\RolPage;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RolPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $RolPage = RolPage::where('status', 1)->get();
        $RolPage->load('page');
        $RolPage->load('rol');
        $RolPage->load('enlaced.page');
        $RolPage->load('create_by');
        $RolPage->load('update_by');
        $RolPage->load('linkeds.page');
        return $RolPage;
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
            $RolPage = RolPage::findOrFail($id);
            $RolPage->load('page');
            $RolPage->load('rol');
            $RolPage->load('enlaced');
            $RolPage->load('create_by');
            $RolPage->load('update_by');

            return $RolPage;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El RolPage ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }

    }

    
    public function create(Request $request)
    {

       
        try {

            $validator = validator($request->all(), [
                'name'=> 'required',
                'enlaced_to'=> 'required|exists:pages,id',
                'page_id'=> 'required|exists:pages,id',
                'rol_id'=> 'required|exists:rols,id',
                'order'=> 'required|numeric',
                'create_by'=>'exists:users,id',
                'update_by'=> 'exists:users,id'
               
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            
            $RolPage = RolPage::create($request->all());

            
            $RolPage->load('page');
            $RolPage->load('rol');

            $log = new LogController();
            $respuesta = $log->create("Dio permiso a rol ".$RolPage->rol->name." para acceder a ".$RolPage->page->url);
            return response()->json(['msj' => 'Asignacion creada correctamente','log' => $respuesta->original['msj']], 200);

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
            return response()->json(['error' => 'No se pudo registrar el RolPage'.$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la accion realizada' . $e->getMessage()], 500);
        }
    }

    public function update($id,Request $request)
    {
       


        try {
            $validator = validator($request->all(), [
                'name'=> 'required',
                'enlaced_to'=> 'required|exists:pages,id',
                'page_id'=> 'required|exists:pages,id',
                'rol_id'=> 'required|exists:rols,id',
                'order'=> 'required|numeric',
                'create_by'=>'exists:users,id',
                'update_by'=> 'exists:users,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $RolPage = RolPage::findOrFail($id);
            $RolPage->update($request->all());
            $RolPage->save();

           

            return response()->json(['msj' => 'RolPage actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El RolPage ' . $id . ' no existe no fue encontrado'], 404);
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
            $RolPage = RolPage::findOrFail($id);
            if ($RolPage->status) {
                $RolPage->status = 0;
                $RolPage->save();
                return response()->json(['msj' => 'RolPage eliminado correctamente'], 200);
            }
            return response()->json(['msj' => 'Este RolPage ya ha sido eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El RolPage ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }
}
