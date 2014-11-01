<?php

namespace Zoka\ZambGenerators;

use \Way\Generators\Generator as WayGenerator;
use \Way\Filesystem\Filesystem as WayFilesystem;


/**
 * Class Generator
 * Generators for Zamb CRUD framework inside Laravel
 * @package Zoka\ZambGenerators
 */
class Generator
{

    protected static $CONTROLLER_ROOT = '';
    protected static $VIEW_ROOT = '';
    protected static $REPOSITORY_ROOT = '';
    protected static $MODEL_ROOT = '';


    protected $resourceName;

    /**
     * ['name', 'collection', 'resource', 'model']
     * @var array
     */
    public $templateData = array();


    public function __construct()
    {

        $this->setupPaths();

        $this->templateData = $this->getTemplateData();
        $this->generator = new WayGenerator(new WayFilesystem());
    }


    public function setResourceName($resourceName)
    {
        $this->resourceName = $resourceName;
    }

    /**
     *
     */
    protected function setupPaths()
    {
        self::$CONTROLLER_ROOT = app_path('controllers') . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR;
        self::$MODEL_ROOT = app_path('models') . DIRECTORY_SEPARATOR;
        self::$VIEW_ROOT = app_path('views') . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR;
        self::$REPOSITORY_ROOT = app_path() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Repository' . DIRECTORY_SEPARATOR;
    }

    public function generateZambCrudController()
    {
        echo 'Generating CRUD controller: ' . $this->templateData['name'] . PHP_EOL;

        $filePathToGenerate = self::$CONTROLLER_ROOT . 'Admin' . $this->templateData['name'] . 'Controller.php';
        $templateData = $this->templateData;
        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'CRUDControllerTemplate.php.txt';

        $this->generator->make(
            $templatePath,
            $templateData,
            $filePathToGenerate
        );
    }

    public function generateZambModel()
    {
        echo 'Generating Model: ' . $this->templateData['model'] . PHP_EOL;

        $filePathToGenerate = self::$MODEL_ROOT . $this->templateData['model'] . '.php';
        $templateData = $this->templateData;
        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'ModelTemplate.php.txt';

        $this->generator->make(
            $templatePath,
            $templateData,
            $filePathToGenerate
        );
    }

    public function generateZambViews()
    {
        echo 'Generating views for: ' . $this->templateData['model'] . PHP_EOL;

        $templateData = $this->templateData;
        $baseFilePathToGenerate = self::$VIEW_ROOT . $templateData['name'];

        //Create dir if needed
        if (!is_dir($baseFilePathToGenerate)) {
            // dir doesn't exist, make it
            mkdir($baseFilePathToGenerate);
        }
        $baseFilePathToGenerate .= DIRECTORY_SEPARATOR;


        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'ViewTemplates' . DIRECTORY_SEPARATOR . 'create_updateTemplate.php.txt';
        $filePathToGenerate = $baseFilePathToGenerate . 'create_update.blade.php';
        $this->generator->make(
            $templatePath,
            $templateData,
            $filePathToGenerate
        );

        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'ViewTemplates' . DIRECTORY_SEPARATOR . 'deleteTemplate.php.txt';
        $filePathToGenerate = $baseFilePathToGenerate . 'delete.blade.php';
        $this->generator->make(
            $templatePath,
            $templateData,
            $filePathToGenerate
        );

        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'ViewTemplates' . DIRECTORY_SEPARATOR . 'indexTemplate.php.txt';
        $filePathToGenerate = $baseFilePathToGenerate . 'index.blade.php';
        $this->generator->make(
            $templatePath,
            $templateData,
            $filePathToGenerate
        );
    }

    public function generateZambRepository()
    {
        echo 'Generating repository for: ' . $this->templateData['model'] . PHP_EOL;

        $filePathToGenerate = self::$REPOSITORY_ROOT . $this->templateData['model'] . 'Repository.php';
        $templateData = $this->templateData;
        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'RepositoryTemplate.php.txt';

        $this->generator->make(
            $templatePath,
            $templateData,
            $filePathToGenerate
        );
    }

    public function printZambRoutes()
    {
        echo 'Routes for: ' . $this->templateData['resource'] . PHP_EOL;

        echo "\t/*" . PHP_EOL;
        echo "\t|--------------------------------------------------------------------------" . PHP_EOL;
        echo "\t| {$this->templateData['model']} routes" . PHP_EOL;
        echo "\t|--------------------------------------------------------------------------" . PHP_EOL;
        echo "\t*/" . PHP_EOL;
        echo "\tRoute::controller('{$this->templateData['collection']}', 'Admin{$this->templateData['name']}Controller', array(" . PHP_EOL;
        echo "\t\t" . "'getIndex' => 'Admin.{$this->templateData['name']}.Index'," . PHP_EOL;
        echo "\t\t" . "'getCreate' => 'Admin.{$this->templateData['name']}.Create'," . PHP_EOL;
        echo "\t\t" . "'postStore' => 'Admin.{$this->templateData['name']}.Store'," . PHP_EOL;
        echo "\t\t" . "'getShow' => 'Admin.{$this->templateData['name']}.Show'," . PHP_EOL;
        echo "\t\t" . "'getEdit' => 'Admin.{$this->templateData['name']}.Edit'," . PHP_EOL;
        echo "\t\t" . "'postUpdate' => 'Admin.{$this->templateData['name']}.Update'," . PHP_EOL;
        echo "\t\t" . "'getDelete' => 'Admin.{$this->templateData['name']}.Delete'," . PHP_EOL;
        echo "\t\t" . "'postDestroy' => 'Admin.{$this->templateData['name']}.Destroy'," . PHP_EOL;
        echo "\t\t" . "'getData' => 'Admin.{$this->templateData['name']}.Data'," . PHP_EOL;
        echo "\t));" . PHP_EOL . PHP_EOL;
    }

    /**
     * Fetch the template data
     *
     * @return array
     */
    protected function getTemplateData()
    {
        // LessonsController
        $name = ucwords($this->resourceName);

        // lessons
        $collection = strtolower(str_replace('Controller', '', $name));

        // lesson
        $resource = str_singular($collection);

        // Lesson
        $model = ucwords($resource);

        $route = strtolower($name);

        return compact('name', 'collection', 'resource', 'model', 'route');
    }

} 