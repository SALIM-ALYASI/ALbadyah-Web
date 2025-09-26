<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    /**
     * عرض جميع المحافظات
     */
    public function index()
    {
        $governorates = Governorate::all();
        return view('governorates.index', compact('governorates'));
    }

    /**
     * عرض نموذج إضافة محافظة جديدة
     */
    public function create()
    {
        return view('governorates.create');
    }

    /**
     * حفظ محافظة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'website_url' => 'nullable|url',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // رفع الصورة إذا تم اختيارها
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // إنشاء المجلد إذا لم يكن موجوداً
            $uploadPath = public_path('images/governorates');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // حفظ الصورة في مجلد public
            $image->move($uploadPath, $imageName);
            $data['image_path'] = 'images/governorates/' . $imageName;
        }

        Governorate::create($data);

        return redirect()->route('governorates.index')
            ->with('success', 'تمت إضافة المحافظة بنجاح');
    }

    /**
     * عرض محافظة محددة
     */
    public function show($id)
    {
        $governorate = Governorate::findOrFail($id);
        return view('governorates.show', compact('governorate'));
    }

    /**
     * عرض نموذج تعديل محافظة
     */
    public function edit($id)
    {
        $governorate = Governorate::findOrFail($id);
        return view('governorates.edit', compact('governorate'));
    }

    /**
     * تعديل محافظة
     */
    public function update(Request $request, $id)
    {
        $governorate = Governorate::findOrFail($id);

        $request->validate([
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'website_url' => 'nullable|url',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // رفع الصورة الجديدة إذا تم اختيارها
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($governorate->image_path) {
                $oldImagePath = public_path($governorate->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // إنشاء المجلد إذا لم يكن موجوداً
            $uploadPath = public_path('images/governorates');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // حفظ الصورة في مجلد public
            $image->move($uploadPath, $imageName);
            $data['image_path'] = 'images/governorates/' . $imageName;
        }

        $governorate->update($data);

        return redirect()->route('governorates.index')
            ->with('success', 'تم تعديل بيانات المحافظة بنجاح');
    }

    /**
     * حذف محافظة
     */
    public function destroy($id)
    {
        $governorate = Governorate::findOrFail($id);
        $governorate->delete();

        return redirect()->route('governorates.index')
            ->with('success', 'تم حذف المحافظة بنجاح');
    }
}

