<?php

namespace App\Core\Modules\Content\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ContentExport extends BaseCommand
{
    protected $group = 'BSCMS';
    protected $name = 'content:export';
    protected $description = 'İçerikleri JSON formatında export eder';
    protected $usage = 'content:export [content_type_slug]';

    public function run(array $params)
    {
        $contentTypeSlug = $params[0] ?? null;

        if (!$contentTypeSlug) {
            CLI::write('Lütfen içerik türü slug\'ı belirtin.', 'red');
            CLI::write('Örnek: php spark content:export blog', 'yellow');
            return;
        }

        $contentTypeRepository = service('contentTypeRepository');
        $contentRepository = service('contentRepository');
        $contentMetaRepository = service('contentMetaRepository');

        $contentType = $contentTypeRepository->findBySlug($contentTypeSlug);

        if (!$contentType) {
            CLI::write('İçerik türü bulunamadı: ' . $contentTypeSlug, 'red');
            return;
        }

        CLI::write('İçerikler export ediliyor...', 'yellow');

        $contents = $contentRepository->getByContentType($contentType->id);
        $export = [];

        foreach ($contents as $content) {
            $meta = $contentMetaRepository->getByContentId($content->id);
            $metaData = [];

            foreach ($meta as $m) {
                $metaData[$m->meta_key] = $m->meta_value;
            }

            $export[] = [
                'id' => $content->id,
                'title' => $content->title,
                'slug' => $content->slug,
                'status' => $content->status,
                'meta' => $metaData,
                'created_at' => $content->created_at,
                'updated_at' => $content->updated_at
            ];
        }

        $filename = 'export_' . $contentTypeSlug . '_' . date('Y-m-d_H-i-s') . '.json';
        $filepath = WRITEPATH . 'exports/' . $filename;

        if (!is_dir(WRITEPATH . 'exports/')) {
            mkdir(WRITEPATH . 'exports/', 0755, true);
        }

        if (file_put_contents($filepath, json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            CLI::write('Export başarılı!', 'green');
            CLI::write('Toplam içerik: ' . count($export), 'green');
            CLI::write('Konum: ' . $filepath, 'green');
        } else {
            CLI::write('Export başarısız!', 'red');
        }
    }
}
