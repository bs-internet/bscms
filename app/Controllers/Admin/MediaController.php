<?php

namespace App\Controllers\Admin;

use App\Repositories\Interfaces\MediaRepositoryInterface;
use App\Libraries\ImageProcessor;

class MediaController extends BaseController
{
    protected MediaRepositoryInterface $mediaRepository;
    protected ImageProcessor $imageProcessor;

    public function __construct()
    {
        $this->mediaRepository = service('mediaRepository');
        $this->imageProcessor = new ImageProcessor();
    }

    public function index()
    {
        $media = $this->mediaRepository->getAll();

        // Toplam disk kullanımı
        $totalSize = 0;
        foreach ($media as $item) {
            $totalSize += $item->filesize;
            
            // Eğer görsel ise boyutların da dosya boyutunu hesapla
            if (strpos($item->mimetype, 'image/') === 0) {
                $sizes = $this->imageProcessor->getAvailableSizes($item->filepath);
                foreach ($sizes as $sizeName => $sizeUrl) {
                    if ($sizeName !== 'full') {
                        $sizeFile = str_replace(base_url(), FCPATH, $sizeUrl);
                        if (file_exists($sizeFile)) {
                            $totalSize += filesize($sizeFile);
                        }
                    }
                }
            }
        }

        return view('admin/media/index', [
            'media' => $media,
            'totalSize' => $totalSize,
            'totalSizeFormatted' => $this->formatBytes($totalSize)
        ]);
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }    

    public function upload()
    {
        $file = $this->request->getFile('file');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Geçersiz dosya.'
            ])->setStatusCode(400);
        }

        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Desteklenmeyen dosya türü.'
            ])->setStatusCode(400);
        }

        $uploadPath = FCPATH . 'uploads/';
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Orijinal dosya adını oluştur
        $newName = $file->getRandomName();
        
        // Dosyayı kaydet (ORİJİNAL KORUNUR)
        if (!$file->move($uploadPath, $newName)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Dosya yüklenemedi.'
            ])->setStatusCode(500);
        }

        $filepath = 'uploads/' . $newName;
        $sizes = ['full' => base_url($filepath)];

        // Eğer görsel ise boyutları oluştur
        if (strpos($file->getMimeType(), 'image/') === 0) {
            $processedSizes = $this->imageProcessor->process($newName);
            
            // URL'leri oluştur
            foreach ($processedSizes as $sizeName => $sizePath) {
                $sizes[$sizeName] = base_url($sizePath);
            }
        }

        // Veritabanına kaydet
        $media = $this->mediaRepository->create([
            'filename' => $file->getClientName(),
            'filepath' => $filepath,
            'mimetype' => $file->getMimeType(),
            'filesize' => $file->getSize()
        ]);

        if (!$media) {
            // Hata durumunda tüm dosyaları temizle
            if (strpos($file->getMimeType(), 'image/') === 0) {
                $this->imageProcessor->deleteAllSizes($filepath);
            } else {
                unlink($uploadPath . $newName);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Medya kaydedilemedi.'
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'media' => [
                'id' => $media->id,
                'filename' => $media->filename,
                'url' => $sizes['full'],
                'mimetype' => $media->mimetype,
                'sizes' => $sizes
            ]
        ]);
    }
    public function delete(int $id)
    {
        $media = $this->mediaRepository->findById($id);

        if (!$media) {
            return redirect()->back()->with('error', 'Medya bulunamadı.');
        }

        if (strpos($media->mimetype, 'image/') === 0) {
            $this->imageProcessor->deleteAllSizes($media->filepath);
        } else {
            $filepath = FCPATH . $media->filepath;
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }

        $result = $this->mediaRepository->delete($id);

        if (!$result) {
            return redirect()->back()->with('error', 'Medya silinemedi.');
        }

        return redirect()->to('/admin/media')->with('success', 'Medya başarıyla silindi.');
    }
}