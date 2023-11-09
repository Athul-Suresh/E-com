<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function showUploadForm()
    {
        return view('upload.form');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('image')) {
            // dd($request->file('image'));
            $image = $request->file('image');

            // Validate the uploaded image (optional)
            $validatedData = $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Generate a unique filename for the image
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();

            // Store the image in the 'public' disk under the 'uploads' directory
            $imagePath = $image->storeAs('uploads', $imageName, 'public');

            // Optionally, you can set the visibility of the stored file to public
            Storage::disk('public')->setVisibility($imagePath, 'public');
            return view('welcome');
        }else{
            return redirect()->back()->with('error', 'No image file found.');
        }
    }

    public function apiUpload(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $image->storeAs('uploads', $imageName);

            // Other logic or save the image path to database

            return response()->json([
                'status' => 'success',
                'message' => 'Image uploaded successfully.',
                'image' => $imageName,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No image file found.',
        ]);
    }

    // public function getImage($imageName)
    // {
    //     $imagePath = 'uploads/' . $imageName;

    //     if (Storage::exists($imagePath)) {
    //         // Return the image URL
    //         // Option 1: Return URL
    //         // return response()->json([
    //         //     'status' => 'success',
    //         //     'image_url' => Storage::url($imagePath),
    //         // ]);

    //         // Option 2: Return the image file
    //         return response()->file(storage_path('app/' . $imagePath));
    //     }

    //     return response()->json([
    //         'status' => 'error',
    //         'message' => 'Image not found.',
    //     ]);
    // }

    public function getImage($imageName)
    {
        $imagePath = 'uploads/' . $imageName;

        if (Storage::disk('public')->exists($imagePath)) {
            $file = Storage::disk('public')->get($imagePath);
            $mimeType = Storage::disk('public')->mimeType($imagePath);

            return response($file, 200)->header('Content-Type', $mimeType);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Image not found.',
        ]);
    }
}
