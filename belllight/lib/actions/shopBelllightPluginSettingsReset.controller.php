<?php

/**
 * Класс для сброса файлов к оригиналам
 *
 * @author Steemy, created by 23.03.2018
 * @link http://steemy.ru/
 */

class shopBelllightPluginSettingsResetController extends waJsonController
{

    public function execute()
    {
        $constClass = new shopBelllightPluginConst();
        $namePlugin = $constClass->getNamePlugin();
        $fileForEditAndSave = $constClass->getFileForEditAndSave();

        $templ = waRequest::post('templ');

        if ($templ == 'templates_all')
        {
            foreach ($fileForEditAndSave as $key => $value) {
                $this->save($namePlugin, $value);
            }
        }
        elseif (!empty($fileForEditAndSave[$templ]))
        {
            $templOriginal = $this->save($namePlugin, $fileForEditAndSave[$templ]);

            if ($templOriginal) {
                $this->response = array(
                    'status' => true,
                    'templ_original' => $templOriginal
                );
            } else {
                $this->response = array(
                    'status' => false,
                    'error' => 'Не загрузился ориганал!'
                );
            }
        }
    }

    /**
     * Сбрасывает шаблон на оригинал и возвращает его
     * @param $namePlugin
     * @param $value
     * @return bool|string
     */
    private function save($namePlugin, $value)
    {
        $templOriginal = '';
        $path = 'plugins/' . $namePlugin . '/' . $value;

        $pathOriginal = wa()->getAppPath($path, 'shop');
        $pathData = wa()->getDataPath($path, 'shop');

        try {
            $templOriginal = file_get_contents($pathOriginal);
            file_put_contents($pathData, $templOriginal);
        } catch (Exception $e) {
            $this->errors['messages'][] = 'Не удается сохранить файлы';
        }

        return $templOriginal;
    }
}