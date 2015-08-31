<?php


namespace controllers;


class ItemSetController extends Controller
{
    private static $actions = ['index', 'upload'];

    /**
     * @inheritDoc
     */
    function hasAction($action)
    {
        return in_array($action, self::$actions);
    }

    /**
     * @inheritDoc
     */
    function hasRightAccess($action)
    {
        return true;
    }

    function index()
    {
        $twig = $this->twigInit();

        $template = $twig->loadTemplate('upload.html.twig');
        echo $template->render([]);
    }

    /**
     * Verify the item set uploaded and create an item set with our models
     * This help to add security as all data will be filtered
     */
    function upload()
    {
        if (isset($_POST['submit'])) {
            if(isset($_FILES['itemset'])){
                if ($_FILES['itemset']['error'] == UPLOAD_ERR_OK               //checks for errors
                    && is_uploaded_file($_FILES['itemset']['tmp_name'])) { //checks that file is uploaded
                    if ($_FILES['userfile']['size'] < 51200 ){
                        echo file_get_contents($_FILES['uploadedfile']['tmp_name']);
                    }
                }
            }
        }
    }
}