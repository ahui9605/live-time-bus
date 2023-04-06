<?php
namespace App\HttpController;

use App\Constant\Error;

/**
 * 附件
 */
class Attachment extends Base
{
    protected $needLogins = ['upload'];
    
    /**
     * 上传
     */
    public function upload()
    {
        $request = $this->request();
        $data = [
            'type' => $request->getRequestParam('type'),
            'file' => $request->getUploadedFile('file')
        ];

        $validate = new \EasySwoole\Validate\Validate();
        $validate->addColumn('type')->inArray(['image'], true, 'the type must be image');
        if ($data['type'] == 'image') {
            $validate->addColumn('file')->allowFileType(['image/png','image/jpeg'], 'only support png or jepg');
        }
        
        $bool = $validate->validate($data);
        if (!$bool) {
            return $this->jsonReturn(Error::PARAM_ERROR, $validate->getError()->__toString());
        }

        try {
            $hash = md5_file($data['file']->getTempName());
            $ext = pathinfo($data['file']->getClientFilename(), PATHINFO_EXTENSION);
            $filepath = sprintf('/%s/%d/%s', $data['type'], $this->user['id'], $hash . '.' . $ext);
            $instance = \EasySwoole\EasySwoole\Config::getInstance();
            $documentRoot = $instance->getConf('MAIN_SERVER.SETTING.document_root');
            $realfilepath = $documentRoot . $filepath;
            if (!file_exists($realfilepath)) {
                $data['file']->moveTo($realfilepath);
            }

            return $this->jsonReturn(Error::SUCCESS, 'succeed', [
                'filename' => basename($filepath),
                'ext' => $ext,
                'filepath' => $instance->getConf('SERVER_HOST') . $filepath,
            ]);
        } catch (\Exception $e) {
            \EasySwoole\EasySwoole\Logger::getInstance()->error(json_encode([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 256));

            return $this->jsonReturn(Error::FAILED, 'image upload failed');
        }
    }
}