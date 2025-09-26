<?php

namespace App\Http\Controllers;

use App\Models\Wilayat;
use App\Models\Governorate;
use Illuminate\Http\Request;

class WilayatController extends Controller
{
    /**
     * عرض جميع الولايات
     */
    public function index()
    {
        $wilayats = Wilayat::with('governorate')->get();
        return view('wilayats.index', compact('wilayats'));
    }

    /**
     * عرض نموذج إضافة ولاية جديدة
     */
    public function create()
    {
        $governorates = Governorate::all();
        return view('wilayats.create', compact('governorates'));
    }

    /**
     * حفظ ولاية جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar'       => 'required|string|max:255',
            'name_en'       => 'required|string|max:255',
            'website_url'   => 'nullable|url',
            'image_url'     => 'nullable|url',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'governorate_id'=> 'required|exists:governorates,id',
        ]);

        $data = $request->all();

        // رفع الصورة إذا تم اختيارها
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/wilayats', $imageName, 'public');
            $data['image_path'] = $imagePath;
            
            // إزالة image_url إذا تم رفع صورة محلية
            unset($data['image_url']);
        }

        Wilayat::create($data);

        return redirect()->route('wilayats.index')
            ->with('success', 'تمت إضافة الولاية بنجاح');
    }

    /**
     * عرض ولاية محددة
     */
    public function show($id)
    {
        $wilayat = Wilayat::with('governorate')->findOrFail($id);
        return view('wilayats.show', compact('wilayat'));
    }

    /**
     * عرض نموذج تعديل ولاية
     */
    public function edit($id)
    {
        $wilayat = Wilayat::findOrFail($id);
        $governorates = Governorate::all();
        return view('wilayats.edit', compact('wilayat', 'governorates'));
    }

    /**
     * تعديل ولاية
     */
    public function update(Request $request, $id)
    {
        $wilayat = Wilayat::findOrFail($id);

        $request->validate([
            'name_ar'       => 'sometimes|string|max:255',
            'name_en'       => 'sometimes|string|max:255',
            'website_url'   => 'nullable|url',
            'image_url'     => 'nullable|url',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'governorate_id'=> 'sometimes|exists:governorates,id',
        ]);

        $data = $request->all();

        // رفع الصورة الجديدة إذا تم اختيارها
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($wilayat->image_path) {
                $oldImagePath = storage_path('app/public/' . $wilayat->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/wilayats', $imageName, 'public');
            $data['image_path'] = $imagePath;
            
            // إزالة image_url إذا تم رفع صورة محلية
            unset($data['image_url']);
        }

        $wilayat->update($data);

        return redirect()->route('wilayats.index')
            ->with('success', 'تم تعديل بيانات الولاية بنجاح');
    }

    /**
     * حذف ولاية
     */
    public function destroy($id)
    {
        $wilayat = Wilayat::findOrFail($id);
        $wilayat->delete();

        return redirect()->route('wilayats.index')
            ->with('success', 'تم حذف الولاية بنجاح');
    }
}
