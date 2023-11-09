<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;

class MediaAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function product($filename)
    {
        try {
            $path = 'public/uploads/product/thumbnail/' . $filename;
            return response()->file(storage_path('app/' . $path));
        } catch (FileNotFoundException $exception) {
            return response()->json(['message' => 'Product Image not found'], 404);
        } catch (\Exception $exception) {
            //  $exception->getMessage()
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
    public function products($filename)
    {
        try {
            $path = 'public/uploads/product/gallery/' . $filename;
            return response()->file(storage_path('app/' . $path));
        } catch (FileNotFoundException $exception) {
            return response()->json(['message' => 'Product Gallery not found'], 404);
        } catch (\Exception $exception) {
            //  $exception->getMessage()
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
    public function brand($filename)
    {
        try {
            $path = 'public/uploads/brands/' . $filename;
            return response()->file(storage_path('app/' . $path));
        } catch (FileNotFoundException $exception) {
            return response()->json(['message' => 'Brand Logo not found'], 404);
        } catch (\Exception $exception) {
            //  $exception->getMessage()
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }


}
