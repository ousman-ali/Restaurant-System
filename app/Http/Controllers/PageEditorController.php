<?php

namespace App\Http\Controllers;

use App\Models\WebPageSection;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PageEditorController extends Controller
{
    /**
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function editor($id)
    {
        $pageSection = WebPageSection::findOrFail($id);
        return view('user.admin.page-builder', compact('pageSection'));
    }

    /**
     * @param Request $request
     * @param WebPageSection $pageSection
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveSection(Request $request, WebPageSection $section)
    {
        $this->validate($request, [
            'content' => 'required|string',
        ]);

        try {
            $section->update([
                'content' => $request->get('content'),
                'styles' => $request->input('styles')
            ]);

            return response()->json(['success' => true, 'data' => $section]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
