<?php
namespace App\Controller\Admin\Setting;

use App\Factory;
use App\Controller\Admin\AdminHttpController;
use App\Models\HomePageConfig;

class Save extends AdminHttpController
{
    public function run()
    {
        $this->saveSettings();
        $this->response->setRedirect('admin/setting');
    }

    /**
     * update user params: firstname, lastname, phone, user-logo
     */
    private function saveSettings()
    {
        /** @var HomePageConfig $homePageConfig */
        $homePageConfig = Factory::getSingleton(HomePageConfig::class);
        $settings = $homePageConfig->getConfig();
        $settings['description'] = trim($this->request->getParam('description'));

        $sliders = $this->request->getParam('sliders');
        $filesData = $this->getFormattedFilesData();
        foreach ($sliders as $i => $sliderData) {
            $settings['sliders'][$i]['txt'] = trim($sliderData['txt']);
            $settings['sliders'][$i]['show'] = $sliderData['show'];
            if ($filesData[$i]['size'] > 0 && $filesData[$i]['error'] === 0) {
                $sliderImgMediaFolder = '/media/img/home-slider';
                $destFolder = ROOT_DIR . $sliderImgMediaFolder;
                $fileName = $i . '.png';
                $fullPath = $destFolder . DIRECTORY_SEPARATOR . $fileName;
                try {
                    unlink($fullPath);
                } catch (\Exception $exception) {
                    //todo log exception
                }
                move_uploaded_file($filesData[$i]['tmp_name'], $fullPath);
                $settings['sliders'][$i]['img'] = $sliderImgMediaFolder . '/' . $fileName;
            }
        }

        $homePageConfig->saveSettings($settings);
    }

    private function getFormattedFilesData() {
        $filesData = $this->request->getFile('sliders');
        $n = count($filesData['error']);
        $files = [];
        for ($i=0; $i<$n; $i++) {
            $files[$i] = [
                'name' => $filesData['name'][$i]['image'],
                'type' => $filesData['type'][$i]['image'],
                'tmp_name' => $filesData['tmp_name'][$i]['image'],
                'error' => $filesData['error'][$i]['image'],
                'size' => $filesData['size'][$i]['image']
            ];
        }
        return $files;
    }
}