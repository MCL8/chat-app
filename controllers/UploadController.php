<?php

class UploadController
{
    public function actionUpload()
    {
        if (!empty($_FILES)) {
            if (is_uploaded_file($_FILES['uploadFile']['tmp_name'])) {
                $ext = pathinfo($_FILES['uploadFile']['name'], PATHINFO_EXTENSION);
                $allowExt = array('jpg', 'png');

                if (in_array($ext, $allowExt)) {
                    $sourcePath = $_FILES['uploadFile']['tmp_name'];
                    $fileName = $_FILES['uploadFile']['name'];
                    $targetPath = ROOT . '/upload/' .$fileName;

                    if (move_uploaded_file($sourcePath, $targetPath)) {
                        include ROOT . '/views/layouts/image_message.php';
                    }
                }
            }
        }

        return true;
    }
}
