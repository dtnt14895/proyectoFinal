<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Page = Page::where('status', 1)->get();
        $Page->load('create_by');
        $Page->load('update_by');
        return $Page;
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
            $Page = Page::findOrFail($id);
            $Page->load('create_by');
            $Page->load('update_by');

            return $Page;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'La pagina ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }

    }

    
    public function create(Request $request)
    {
        try {

            $validator = validator($request->all(), [
                'url'=> 'required',
                'name'=> 'required',
                'description'=> 'required',
                'icon'=> 'required',
                'type'=> 'required',
                'create_by'=>'exists:users,id',
                'update_by'=> 'exists:users,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            
            Page::create($request->all());

            $log = new LogController();
            $respuesta = $log->create("Creo la pagina ".$request->name);
            return response()->json(['msj' => 'Pagina creada correctamente','log' => $respuesta->original['msj']], 200);

        
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
            return response()->json(['error' => 'No se pudo registrar el Page'.$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la accion realizada' . $e->getMessage()], 500);
        }
    }

    public function update($id,Request $request)
    {
        
        try {
            $validator = validator($request->all(), [
                'url'=> 'required',
                'name'=> 'required',
                'description'=> 'required',
                'icon'=> 'required',
                'type'=> 'required',
                'create_by'=>'exists:users,id',
                'update_by'=> 'exists:users,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $Page = Page::findOrFail($id);
            $Page->update($request->all());
            $Page->save();

           

            return response()->json(['msj' => 'Page actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Page ' . $id . ' no existe no fue encontrado'], 404);
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
            $Page = Page::findOrFail($id);
            if ($Page->status) {
                $Page->status = 0;
                $Page->save();
                return response()->json(['msj' => 'Page eliminado correctamente'], 200);
            }
            return response()->json(['msj' => 'Este Page ya ha sido eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Page ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }
}
