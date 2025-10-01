<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TouristImageNew;
use App\Helpers\ImageHelper;

class FixImagePaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:fix-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تصحيح مسارات الصور في قاعدة البيانات';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء تصحيح مسارات الصور...');
        
        $images = TouristImageNew::all();
        $fixedCount = 0;
        $errorCount = 0;
        
        foreach ($images as $image) {
            try {
                // تصحيح image_url
                $oldUrl = $image->image_url;
                $newUrl = ImageHelper::correctImageUrl($oldUrl);
                
                // إجبار التحديث إذا كان URL يحتوي على localhost
                if (strpos($oldUrl, 'localhost') !== false || $oldUrl !== $newUrl) {
                    $image->image_url = $newUrl;
                    $image->save();
                    
                    $this->line("تم تصحيح صورة ID {$image->id}: {$oldUrl} -> {$newUrl}");
                    $fixedCount++;
                }
                
                // التحقق من وجود الملف
                if ($image->image_path && !file_exists(storage_path('app/public/' . $image->image_path))) {
                    $this->warn("تحذير: الملف غير موجود لصورة ID {$image->id}: {$image->image_path}");
                }
                
            } catch (\Exception $e) {
                $this->error("خطأ في صورة ID {$image->id}: " . $e->getMessage());
                $errorCount++;
            }
        }
        
        $this->info("تم الانتهاء من تصحيح المسارات.");
        $this->info("عدد الصور المصححة: {$fixedCount}");
        $this->info("عدد الأخطاء: {$errorCount}");
        
        return 0;
    }
}